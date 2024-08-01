<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicesItem;
use App\Models\IssueNote;
use App\Models\IssueNoteItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::join('users', 'users.id', 'invoices.sales_user')->where('invoices.is_active', 1)->select('invoices.*', 'users.customer_name as user_name')->orderBy('invoices.id', 'desc')->paginate(5);
        $products = Product::where('is_active', 1)->groupBy('product_code')->orderBy('id', 'desc')->get();
        $warehouses = Warehouse::where('is_active', 1)->orderBy('id', 'desc')->get();
        $customers = User::where('user_type', 3)->where('is_active', 1)->orderBy('id', 'desc')->get();
        return view('invoice', ["invoices" => $invoices, "products" => $products, "warehouses" => $warehouses, "customers" => $customers]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string|max:255',
                'warehouse_id' => 'required|integer',
                'customer_address' => 'required|string|max:255',
                'vat_no' => 'nullable|string|max:50',
                'vat_amount' => 'required|numeric|min:0',
                'grand_total' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                date_default_timezone_set('Asia/Colombo');
                $date_time = date('Y-m-d H:i:s');

                $invoice = Invoice::create([
                    "warehouse_id" => $request->warehouse_id,
                    "sales_user" => auth()->user()->id,
                    "invoice_no" => 'INV-' . rand(1, 10000),
                    "invoice_date" => $date_time,
                    "customer_name" => $request->customer_name,
                    "customer_address" => $request->customer_address,
                    "vat_no" => $request->vat_no,
                    "vat_amount" => $request->vat_amount,
                    "grand_total" => $request->grand_total,
                    "printed_at" => $date_time
                ]);

                $invoice_id = isset($invoice) && intval($invoice->id) > 0 ? $invoice->id : 0;

                // Create the IssueNote
                $issueNote = IssueNote::create([
                    'invoice_id' => $invoice_id,
                    'issue_note_no' => 'IN-' . rand(1, 10000),
                    'customer_name' => $request->customer_name,
                    "created_by" => auth()->user()->id,
                    "issued_by" => null,
                ]);

                if (isset($invoice_id)) {
                    foreach ($request->items as $item) {
                        InvoicesItem::create([
                            "warehouse_id" => $item['warehouse_id'],
                            "invoice_id" => $invoice_id,
                            "product_name" => $item['product_name'],
                            "product_code" => $item['product_code'],
                            "qty" => $item['qty'],
                            "unit_price" => $item['unit_price'],
                            "sub_total" => $item['qty'] * $item['unit_price']
                        ]);

                        $product = Product::where('product_code', $item['product_code'])->where('warehouse_id', $item['warehouse_id'])->orderBy('id', 'desc')->first();
                        // Create the related IssueNoteItems
                        IssueNoteItem::create([
                            'issue_note_id' => $issueNote->id,
                            'warehouse_id' => $item['warehouse_id'],
                            'stock_no' => $item['product_code'],
                            'description' => $item['product_name'],
                            'unit_of_measure' => $product->product_unit,
                            'order_qty' => $item['qty'],
                            'issued_qty' => 0,
                            'balance_qty' => 0
                        ]);
                    }


                    $last_invoice = Invoice::join('users', 'users.id', 'invoices.sales_user')
                        ->select('invoices.*', 'users.customer_name as user_name')
                        ->where('invoices.is_active', 1)
                        ->where('invoices.id', $invoice_id)
                        ->orderBy('invoices.id', 'desc')
                        ->first();

                    $last_invoice_item = InvoicesItem::join('warehouses', 'warehouses.id', 'invoices_items.warehouse_id')->join('products', 'products.product_code', 'invoices_items.product_code')
                        ->select('invoices_items.*', 'warehouses.name as warehouse_name', 'products.product_name')
                        ->where('invoices_items.invoice_id', $invoice_id)
                        ->where('invoices_items.is_active', 1)
                        ->groupBy('invoices_items.product_code')
                        ->orderBy('invoices_items.id', 'desc')
                        ->get();


                    return response()->json(['success' => 1, 'data' => [
                        'last_invoice' => $last_invoice,
                        'last_invoice_items' => $last_invoice_item
                    ]], 200);
                } else {
                    return response()->json(['success' => 0, 'data' => 'invoice not saved!'], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        $last_invoice_item = InvoicesItem::join('warehouses', 'warehouses.id', 'invoices_items.warehouse_id')
            ->join('products', 'products.product_code', 'invoices_items.product_code')
            ->select('invoices_items.*', 'warehouses.name as warehouse_name', 'products.product_name', 'products.product_unit', 'products.product_code', 'products.warehouse_id')
            ->where('invoices_items.invoice_id', $request->id)
            ->where('invoices_items.is_active', 1)
            ->groupBy('invoices_items.warehouse_id', 'invoices_items.product_code')
            ->orderBy('invoices_items.id', 'desc')
            ->get();

        return response()->json(['data' => $last_invoice_item], 200);
    }

    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {

                $invoice = Invoice::findOrFail($request->id);

                if ($invoice->hasIssuedItems()) {
                    Alert::error('Oops!', 'Invoice cannot be deleted because it has issued items');
                    return redirect()->back();
                }

                $invoice->is_active = 0;
                $invoice->save();

                Alert::success('Invoice deleted successfully!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::error('Oops!', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function invoice_report(Request $request)
    {
        try {
            $customers = User::where('user_type', 3)->where('is_active', 1)->get();

            $invoicesQuery = Invoice::join('users', 'users.id', 'invoices.sales_user')
                ->where('invoices.is_active', 1)
                ->select('invoices.*', 'users.customer_name as user_name');

            // Apply filters if they are provided
            if ($request->customer_id) {
                $invoicesQuery->where('invoices.customer_name', 'like', "%" . $request->customer_id . "%");
            }

            if ($request->start_date && $request->end_date) {
                $invoicesQuery->whereBetween('invoices.invoice_date', [$request->start_date, $request->end_date]);
            }
            $invoices = $invoicesQuery->orderBy('invoices.id', 'desc')->paginate(5);
            return view('invoice_report', ["invoices" => $invoices, "customers" => $customers]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function invoice_sale_report(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'start_date' => 'string|max:255',
                'end_date' => 'string|max:255'
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {
                date_default_timezone_set('Asia/Colombo');
                $date = date('Y-m-d');

                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');

                $invoicesQuery = Invoice::where('is_active', 1);

                if ($startDate && $endDate) {
                    $invoicesQuery->whereBetween('invoice_date', [$startDate, $endDate]);
                } else {
                    $invoicesQuery->whereDate('invoice_date', $date);
                }

                $invoice_data = $invoicesQuery->orderBy('invoices.id', 'desc')->get();

                $totalSales = $invoice_data->sum('grand_total');
                $numInvoices = $invoice_data->count();

                $topCustomers = $invoice_data->groupBy('customer_name')
                    ->map(function ($group) {
                        return [
                            'total_sales' => $group->sum('grand_total'),
                            'num_invoices' => $group->count(),
                        ];
                    })
                    ->sortByDesc('total_sales')
                    ->take(5);

                $totalVAT = $invoice_data->sum('vat_amount');

                $data = [
                    'date' => $date,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'totalSales' => $totalSales,
                    'numInvoices' => $numInvoices,
                    'topCustomers' => $topCustomers,
                    'totalVAT' => $totalVAT,

                ];
                return view('invoice_sale_report', ["data" => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function issue_notes()
    {
        $issue_notes = IssueNote::join('invoices', 'issue_notes.invoice_id', 'invoices.id')
            ->join('issue_note_items', 'issue_notes.id', 'issue_note_items.issue_note_id') // Join the issue_note_items table
            ->select('issue_notes.*', 'issue_note_items.warehouse_id', 'issue_note_items.issue_note_id', 'invoices.invoice_no')
            ->where('issue_note_items.warehouse_id', auth()->user()->warehouse_id) // Filter by the specific warehouse ID
            ->whereNotIn('issue_notes.is_active', [4])
            ->groupBy('issue_notes.invoice_id')
            ->orderBy('issue_notes.id', 'desc')
            ->paginate(5);

        return view('issue_note', ["issue_notes" => $issue_notes]);
    }

    public function issue_note_items(Request $request)
    {
        $issue_note_items = IssueNoteItem::join('warehouses', 'warehouses.id', '=', 'issue_note_items.warehouse_id')
            ->join('products', function ($join) {
                $join->on('issue_note_items.stock_no', '=', 'products.product_code')
                    ->on('issue_note_items.warehouse_id', '=', 'products.warehouse_id');
            })
            ->select('issue_note_items.*', 'warehouses.name as warehouse_name', 'products.product_code', 'products.stock_available', 'products.product_unit_price')
            ->where('issue_note_items.warehouse_id', $request->warehouse_id)
            ->where('issue_note_items.issue_note_id', $request->issue_note_id)
            ->where('issue_note_items.is_active', 1)
            ->orderBy('products.product_code', 'desc')
            ->get();

        $issue_note = IssueNote::join('invoices', 'issue_notes.invoice_id', 'invoices.id')->join('users', 'users.id', 'issue_notes.created_by')
            ->select('issue_notes.*', 'invoices.invoice_no', 'users.customer_name as created_user')
            ->where('issue_notes.id', $request->issue_note_id)
            ->whereNotIN('issue_notes.is_active', [4])
            ->orderBy('issue_notes.id', 'desc')
            ->first();

        if ($issue_note) {
            // Append the formatted_created_at attribute to the JSON response
            $issue_note->formatted_created_at = $issue_note->formatted_created_at;
        }

        return response()->json(['issue_note_items' => $issue_note_items, 'issue_note' => $issue_note], 200);
    }

    public function submit_issue_items(Request $request)
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $date_time = now(); // Use Carbon for date handling

            $issue_note_data = IssueNote::where('issue_note_no', $request->issue_note_no)
                ->whereIn('is_active', [0, 3])
                ->latest('id')
                ->first();

            if (!$issue_note_data) {
                return response()->json(['error' => 'No issue note found'], 200);
            }

            foreach ($request->items as $product) {
                $product_stock = Product::where('product_code', $product['product_code'])
                    ->where('warehouse_id', auth()->user()->warehouse_id)
                    ->latest('id')
                    ->first();

                if (!$product_stock) {
                    return response()->json(['error' => 'No product found'], 200);
                }

                $product_stock->update([
                    'stock_available' => abs($product_stock->stock_available - $product['ordered_qty']),
                    'updated_at' => $date_time
                ]);

                // Retrieve the current issued_by array or create a new one if it doesn't exist
                $issued_by_array = $issue_note_data->issued_by ?? [];
                // Add the current user's ID to the array if not already present
                if (!in_array(auth()->user()->id, $issued_by_array)) {
                    $issued_by_array[] = auth()->user()->id;
                }

                $issue_note_data->update([
                    'issued_by' => $issued_by_array,
                    'is_active' => 3,
                    'updated_at' => $date_time
                ]);

                $issue_note_item_data = IssueNoteItem::where('issue_note_id', $issue_note_data->id)
                    ->where('warehouse_id', auth()->user()->warehouse_id)
                    ->where('stock_no', $product['product_code'])
                    ->latest('id')
                    ->first();

                if ($issue_note_item_data) {
                    $issue_note_item_data->update([
                        'issued_qty' => $product['ordered_qty'],
                        'balance_qty' => floatval($issue_note_item_data->order_qty) - floatval($product['ordered_qty']),
                    ]);
                }
            }


            // Retrieve items with balance_qty > 0 to create balance note
            $items = DB::table('issue_note_items')->where('balance_qty', '>', 0)->where('warehouse_id', auth()->user()->warehouse_id)->where('issue_note_id', $issue_note_data->id)->get();

            if (!$items->isEmpty()) {
                // Create a new record in the issue_notes table
                $balance_order_note = IssueNote::create([
                    'invoice_id' => $issue_note_data->invoice_id,
                    'issue_note_no' => 'BO-' . rand(1, 10000),
                    'customer_name' => $issue_note_data->customer_name,
                    'created_by' => auth()->user()->id,
                ]);

                // Create new records in the issue_note_items table for each retrieved item
                foreach ($items as $item) {
                    DB::table('issue_note_items')->insert([
                        'issue_note_id' => $balance_order_note->id,
                        'warehouse_id' => $item->warehouse_id,
                        'stock_no' => $item->stock_no,
                        'description' => $item->description,
                        'unit_of_measure' => $item->unit_of_measure,
                        'order_qty' => $item->balance_qty,
                    ]);
                }
            }


            // Get the issue_note_ids where all issued_qty are greater than 0
            $all_issue_note_items = IssueNoteItem::where('issue_note_id', $issue_note_data->id)
                ->groupBy('warehouse_id')
                ->havingRaw('MIN(issued_qty) > 0')
                ->pluck('issue_note_id');

            $grouped_issue_note_items = IssueNoteItem::where('issue_note_id', $issue_note_data->id)
                ->selectRaw('COUNT(*) as item_count, warehouse_id')
                ->groupBy('warehouse_id')
                ->get();

            if ($grouped_issue_note_items->count() == $all_issue_note_items->count()) {
                // Update the issued_by column for the retrieved issue_note_ids
                DB::table('issue_notes')
                    ->whereIn('id', $all_issue_note_items)
                    ->update(['is_active' => 1]);
            }

            return response()->json(['success' => 1, 'issue_note_item_data' => $issue_note_item_data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function balance_notes()
    {
        $issue_notes = IssueNote::leftJoin('invoices', 'issue_notes.invoice_id', 'invoices.id')
            ->leftJoin('issue_note_items', 'issue_notes.id', 'issue_note_items.issue_note_id')
            ->select('issue_notes.*', 'issue_note_items.warehouse_id', 'issue_note_items.stock_no', 'issue_note_items.issue_note_id', 'invoices.invoice_no')
            ->where('issue_notes.issue_note_no', 'like', '%BO-%') // Filter for issue notes that start with 'BO-'
            ->where(function ($query) {
                $query->where('issue_note_items.warehouse_id', auth()->user()->warehouse_id)
                    ->orWhereNull('issue_note_items.warehouse_id'); // Include issue notes without warehouse_id in issue_note_items
            })
            ->whereNotIn('issue_notes.is_active', [4])
            ->groupBy('issue_notes.id') // Group by issue_notes.id to avoid issues with aggregate functions
            ->orderBy('issue_notes.id', 'desc')
            ->paginate(5);

        return view('balance_note', ["issue_notes" => $issue_notes]);
    }

    public function balance_note_items(Request $request)
    {
        $issue_note_items = IssueNoteItem::join('warehouses', 'warehouses.id', '=', 'issue_note_items.warehouse_id')
            ->join('products', function ($join) {
                $join->on('issue_note_items.stock_no', '=', 'products.product_code')
                    ->on('issue_note_items.warehouse_id', '=', 'products.warehouse_id');
            })
            ->select('issue_note_items.*', 'warehouses.name as warehouse_name', 'products.product_code', 'products.stock_available', 'products.product_unit_price')
            ->where('issue_note_items.warehouse_id', $request->warehouse_id)
            ->where('issue_note_items.issue_note_id', $request->issue_note_id)
            ->where('issue_note_items.is_active', 1)
            ->orderBy('products.product_code', 'desc')
            ->get();

        $issue_note = IssueNote::join('invoices', 'issue_notes.invoice_id', 'invoices.id')->join('users', 'users.id', 'issue_notes.created_by')
            ->select('issue_notes.*', 'invoices.invoice_no', 'users.customer_name as created_user')
            ->where('issue_notes.id', $request->issue_note_id)
            ->whereNotIN('issue_notes.is_active', [4])
            ->orderBy('issue_notes.id', 'desc')
            ->first();

        if ($issue_note) {
            // Append the formatted_created_at attribute to the JSON response
            $issue_note->formatted_created_at = $issue_note->formatted_created_at;
        }

        $warehouse_array = [];
        $products = null;
        foreach ($issue_note_items as $inotes) {
            $products = Product::where('product_code', $inotes->stock_no)->where('is_active', 1)->orderBy('id', 'desc')->get();
        }

        if ($products != null) {
            foreach ($products as $product) {
                array_push($warehouse_array, $product->warehouse_id);
            }
        }

        $warehouses = Warehouse::whereIN('id', $warehouse_array)->where('is_active', 1)->orderByDesc('id')->get();

        return response()->json(['issue_note_items' => $issue_note_items, 'issue_note' => $issue_note, 'warehouses' => $warehouses], 200);
    }
    public function submit_balance_items(Request $request)
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $date_time = now(); // Use Carbon for date handling

            $issue_note_data = IssueNote::where('issue_note_no', $request->issue_note_no)
                ->whereIn('is_active', [0, 3])
                ->latest('id')
                ->first();

            if (!$issue_note_data) {
                return response()->json(['error' => 'No issue note found'], 200);
            }

            foreach ($request->items as $product) {
                $product_stock = Product::where('product_code', $product['product_code'])
                    ->where('warehouse_id', auth()->user()->warehouse_id)
                    ->latest('id')
                    ->first();

                if (!$product_stock) {
                    return response()->json(['error' => 'No product found'], 200);
                }

                $product_stock->update([
                    'stock_available' => abs($product_stock->stock_available - $product['ordered_qty']),
                    'updated_at' => $date_time
                ]);

                // Retrieve the current issued_by array or create a new one if it doesn't exist
                $issued_by_array = $issue_note_data->issued_by ?? [];
                // Add the current user's ID to the array if not already present
                if (!in_array(auth()->user()->id, $issued_by_array)) {
                    $issued_by_array[] = auth()->user()->id;
                }

                $issue_note_data->update([
                    'issued_by' => $issued_by_array,
                    'is_active' => 3,
                    'updated_at' => $date_time
                ]);

                $issue_note_item_data = IssueNoteItem::where('issue_note_id', $issue_note_data->id)
                    ->where('warehouse_id', auth()->user()->warehouse_id)
                    ->where('stock_no', $product['product_code'])
                    ->latest('id')
                    ->first();

                if ($issue_note_item_data) {
                    $issue_note_item_data->update([
                        'issued_qty' => $product['ordered_qty'],
                        'balance_qty' => floatval($issue_note_item_data->order_qty) - floatval($product['ordered_qty']),
                    ]);
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // Retrieve items with balance_qty > 0 to create balance note
                $items = DB::table('issue_note_items')->where('balance_qty', '>', 0)->where('warehouse_id', auth()->user()->warehouse_id)->where('issue_note_id', $issue_note_data->id)->get();

                if (!$items->isEmpty()) {
                    // Create a new record in the issue_notes table
                    $balance_order_note = IssueNote::create([
                        'invoice_id' => $issue_note_data->invoice_id,
                        'issue_note_no' => 'BO-' . rand(1, 10000),
                        'customer_name' => $issue_note_data->customer_name,
                        'created_by' => auth()->user()->id,
                    ]);

                    // Create new records in the issue_note_items table for each retrieved item
                    foreach ($items as $item) {
                        DB::table('issue_note_items')->insert([
                            'issue_note_id' => $balance_order_note->id,
                            'warehouse_id' => $product['warehouse_id'],
                            'stock_no' => $item->stock_no,
                            'description' => $item->description,
                            'unit_of_measure' => $item->unit_of_measure,
                            'order_qty' => $item->balance_qty,
                        ]);
                    }
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            }


            // Get the issue_note_ids where all issued_qty are greater than 0
            $all_issue_note_items = IssueNoteItem::where('issue_note_id', $issue_note_data->id)
                ->groupBy('warehouse_id')
                ->havingRaw('MIN(issued_qty) > 0')
                ->pluck('issue_note_id');

            $grouped_issue_note_items = IssueNoteItem::where('issue_note_id', $issue_note_data->id)
                ->selectRaw('COUNT(*) as item_count, warehouse_id')
                ->groupBy('warehouse_id')
                ->get();

            if ($grouped_issue_note_items->count() == $all_issue_note_items->count()) {
                // Update the issued_by column for the retrieved issue_note_ids
                DB::table('issue_notes')
                    ->whereIn('id', $all_issue_note_items)
                    ->update(['is_active' => 1]);
            }

            return response()->json(['success' => 1, 'issue_note_item_data' => $issue_note_item_data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function invoice_stats(Request $request)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $invoiceNo = $request->invoice_no;

            $query =  $invoices = Invoice::join('users', 'users.id', 'invoices.sales_user')
                ->select('invoices.*', 'users.customer_name as user_name')->where('invoices.is_active', 1);

            if ($startDate && $endDate) {
                $query->whereBetween('invoice_date', [$startDate, $endDate]);
            }

            if ($invoiceNo) {
                $query->where('invoice_no', $invoiceNo);
            }

            $invoices = $query->paginate(5);


            if (count($invoices) > 0) {
                return view('invoice_stats', ["invoices" => $invoices]);
            } else {
                Alert::warning('Oops..', 'No records found!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::error('Oops..', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function issue_notes_for_report(Request $request)
    {
        $issue_note = IssueNote::join('invoices', 'issue_notes.invoice_id', 'invoices.id')
            ->join('users', 'users.id', 'issue_notes.created_by')
            ->select('issue_notes.*', 'invoices.invoice_no', 'users.customer_name as created_user')
            ->where('issue_notes.invoice_id', $request->invoice_id)
            ->where('issue_notes.issue_note_no', 'like', '%IN-%')
            ->whereNotIN('issue_notes.is_active', [4])
            ->orderBy('issue_notes.id', 'desc')
            ->first();

        if ($issue_note) {
            $issue_note->formatted_created_at = $issue_note->formatted_created_at;
        }

        $note_items = IssueNoteItem::join('warehouses', 'warehouses.id', 'issue_note_items.warehouse_id')
            ->join('products', function ($join) {
                $join->on('issue_note_items.stock_no', '=', 'products.product_code')
                    ->on('issue_note_items.warehouse_id', '=', 'products.warehouse_id');
            })
            ->select('issue_note_items.*', 'warehouses.name', 'products.stock_available')
            ->where('issue_note_items.issue_note_id', $issue_note->id)
            ->where('issue_note_items.is_active', 1)
            ->get();

        $balance_note_items = IssueNote::join('issue_note_items', 'issue_note_items.issue_note_id', 'issue_notes.id')
            ->join('invoices', 'issue_notes.invoice_id', 'invoices.id')
            ->join('users', 'users.id', 'issue_notes.created_by')
            ->join('warehouses', 'warehouses.id', 'issue_note_items.warehouse_id')
            ->join('products', function ($join) {
                $join->on('issue_note_items.warehouse_id', '=', 'products.warehouse_id');
            })
            ->select(
                'issue_notes.issue_note_no',
                'issue_notes.customer_name',
                'users.customer_name as created_user',
                'issue_note_items.id',
                'issue_note_items.stock_no',
                'issue_note_items.description',
                'issue_note_items.order_qty',
                'issue_note_items.unit_of_measure',
                'issue_note_items.issued_qty',
                'issue_note_items.balance_qty',
                'warehouses.name',
            )
            ->where('issue_notes.invoice_id', $request->invoice_id)
            ->where('issue_notes.issue_note_no', 'like', '%BO-%')
            ->whereNotIN('issue_notes.is_active', [4])
            ->groupBy('issue_notes.issue_note_no')
            ->orderBy('issue_note_items.id', 'desc')
            ->get();

        return response()->json(['issue_note' => $issue_note, 'issue_note_items' => $note_items, 'balance_note_items' => $balance_note_items], 200);
    }

    public function invoice_outstanding_stats(Request $request)
    {

        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $customer_name = $request->customer_id;
            $item_name = $request->item_id;


            $query = Invoice::join('invoices_items', 'invoices.id', 'invoices_items.invoice_id')
                ->join('users', 'users.id', 'invoices.sales_user')
                ->select(
                    'invoices.invoice_no',
                    'users.customer_name',
                    'invoices_items.product_name',
                    DB::raw('SUM(invoices.vat_amount) as total_vat'),
                    DB::raw('SUM(invoices_items.qty) as total_qty'),
                    DB::raw('SUM(invoices_items.unit_price) as total_unit_price'),
                    DB::raw('SUM(invoices.grand_total) as total_amount')
                );


            if ($startDate && $endDate) {
                $query->whereBetween('invoice_date', [$startDate, $endDate]);
            }

            if ($customer_name) {
                $query->where('invoices.customer_name', $customer_name);
            }

            if ($item_name) {
                $query->where('invoices_items.product_name', $item_name);
            }

            $outstandings = $query->paginate(5);

            $customers = User::where('user_type', 3)->where('is_active', 1)->orderBy('id', 'desc')->get();
            $products = Product::where('is_active', 1)->groupBy('product_code')->orderBy('id', 'desc')->get();

            if (count($outstandings) > 0) {
                return view('invoice_outstanding', ["outstandings" => $outstandings, "customers" => $customers, "products" => $products]);
            } else {
                Alert::warning('Oops..', 'No records found!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::error('Oops..', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function issue_note_delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {

                $issueNote = IssueNote::findOrFail($request->id);

                if ($issueNote->hasIssuedItems()) {
                    Alert::error('Oops!', 'Issue Note cannot be deleted because it has issued items');
                    return redirect()->back();
                }

                $issueNote->is_active = 0;
                $issueNote->save();

                Alert::success('Issue Note deleted successfully!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::error('Oops!', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function delivery_notes()
    {
        $issue_notes = IssueNote::leftJoin('invoices', 'issue_notes.invoice_id', 'invoices.id')
            ->leftJoin('issue_note_items', 'issue_notes.id', 'issue_note_items.issue_note_id')
            ->select('issue_notes.*', 'issue_note_items.warehouse_id', 'issue_note_items.stock_no', 'issue_note_items.issue_note_id', 'invoices.invoice_no')
            ->where(function ($query) {
                $query->where('issue_note_items.warehouse_id', auth()->user()->warehouse_id)
                    ->orWhereNull('issue_note_items.warehouse_id'); // Include issue notes without warehouse_id in issue_note_items
            })
            ->where('issue_notes.is_active', 1)
            ->groupBy('issue_notes.id') // Group by issue_notes.id to avoid issues with aggregate functions
            ->orderBy('issue_notes.id', 'desc')
            ->paginate(5);

        return view('delivery_note', ["issue_notes" => $issue_notes]);
    }
}
