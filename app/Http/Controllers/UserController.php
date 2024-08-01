<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {
                $userInfo = User::where('customer_email', $request->email)->where('is_active', 1)->first();
                if (!$userInfo) {
                    Alert::warning('Oops...', "No user found!");
                    return redirect()->back();
                } else {
                    $credentials = [
                        'customer_email' => $request->email,
                        'password' => $request->password
                    ];

                    if (Auth::attempt($credentials)) {
                        $userInfo = Auth::user();
                        return redirect()->route('dashboard');
                    } else {
                        Alert::warning('Oops...', "Incorrect credentials!");
                        return redirect()->back();
                    }
                }
            }
        } catch (Exception $e) {
            Alert::error("Something went wrong!", $e->getMessage());
            return redirect()->back();
        }
    }

    public function customer(Request $request)
    {
        $users = User::leftjoin('warehouses', 'warehouses.id', 'users.warehouse_id')->select('users.*', 'warehouses.name')->where('users.is_active', 1)->orderBy('users.id', 'desc')->paginate(5);
        $warehouses = Warehouse::where('is_active', 1)->orderBy('id', 'desc')->paginate(5);
        return view('customer', ["users" => $users, "warehouses" => $warehouses]);
    }

    private function generateCustomerCode()
    {
        do {
            $randomString = Str::random(5); // 5 characters to make the total length 8 with 'CUS'
            $customerCode = 'CUS-' . $randomString;
        } while (User::where('customer_code', $customerCode)->exists());

        return $customerCode;
    }

    public function create_user(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_type' => 'required|string',
                'warehouse_id' => 'required|integer',
                'customer_name' => 'required|string|max:255',
                'customer_address' => 'required|string|max:255',
                'customer_phone_no' => 'required|string|max:20',
                'customer_vat_no' => 'required|string|max:20',
                'customer_email' => 'required|email|max:255|unique:users,customer_email',
                'password' => 'required|string|min:8'
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {
                // Create a new customer
                User::create([
                    'user_type' => $request->user_type,
                    'warehouse_id' => $request->warehouse_id,
                    'customer_code' => $this->generateCustomerCode(),
                    'customer_name' => $request->customer_name,
                    'customer_address' => $request->customer_address,
                    'customer_phone_no' => $request->customer_phone_no,
                    'customer_vat_no' => $request->customer_vat_no,
                    'customer_email' => $request->customer_email,
                    'password' => Hash::make($request->password),
                ]);

                Alert::success('User Saved!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::success('Oops!', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit_user(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                $customer = User::findOrFail($request->id);
                return response()->json($customer);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function update_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_customer_id' => 'required|integer',
            'edit_warehouse_id' => 'required|integer',
            'edit_user_type' => 'required|integer',
            'edit_customer_name' => 'required|string|max:255',
            'edit_customer_address' => 'required|string|max:255',
            'edit_customer_phone_no' => 'required|string|max:20',
            'edit_customer_vat_no' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            Alert::warning($validator->errors()->first());
            return redirect()->route('customer');
        } else {
            $customer = User::findOrFail($request->edit_customer_id);
            $customer->update([
                'user_type' => $request->edit_user_type,
                'warehouse_id' => $request->edit_warehouse_id,
                'customer_name' => $request->edit_customer_name,
                'customer_address' => $request->edit_customer_address,
                'customer_phone_no' => $request->edit_customer_phone_no,
                'customer_vat_no' => $request->edit_customer_vat_no
            ]);

            Alert::success('User updated successfully!');
            return redirect()->back();
        }
    }

    public function destroy_user(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                $customer = User::findOrFail($request->id);
                $customer->is_active = 0;
                $customer->save();

                return response()->json(['success' => 'User deleted!'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('/');
    }

    public function dashboard(Request $request)
    {

        $invoices = Invoice::where('is_active', 1)->count();
        $products = Product::where('is_active', 1)->count();
        $customers = User::where('is_active', 1)->where('user_type', 3)->count();
        $salesman = User::where('is_active', 1)->where('user_type', 2)->orderBy('id', 'desc')->get();
        $warehouses = Warehouse::where('is_active', 1)->orderBy('id', 'desc')->get();
        date_default_timezone_set('Asia/Colombo');
        $date = date('Y-m-d');

        $sale_user_id = $request->salesman_id;
        $warehouse_id = $request->warehouse_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $invoicesQuery = Invoice::where('is_active', 1);

        if (!empty($sale_user_id)) {
            $invoicesQuery->where('sales_user', $sale_user_id);
        }

        if (!empty($warehouse_id)) {
            $invoicesQuery->whereHas('invoiceItems', function ($query) use ($warehouse_id) {
                $query->where('warehouse_id', $warehouse_id);
            });
        }

        if (!empty($start_date) && !empty($end_date)) {
            $invoicesQuery->whereBetween('invoice_date', [$start_date, $end_date]);
        } else {
            if ($invoicesQuery != null) {
                $invoicesQuery->whereDate('invoice_date', now()->toDateString());
            }
        }

        $invoice_data = $invoicesQuery->get();

        $groupedData = $invoice_data->groupBy(function ($date) {
            return \Carbon\Carbon::parse($date->invoice_date)->format('Y-m-d');
        });

        $data = [
            ['Date', 'Total Sales', 'Invoice Count', 'Total VAT']
        ];

        foreach ($groupedData as $date => $invoice) {
            $totalSales = $invoice->sum('grand_total');
            $numInvoices = $invoice->count();
            $totalVAT = $invoice->sum('vat_amount');

            $data[] = [$date, $totalSales, $numInvoices, $totalVAT];
        }

        return view('body.index', [
            "products" => $products,
            "invoices" => $invoices,
            "customers" => $customers,
            "salesman" => $salesman,
            "warehouses" => $warehouses,
            "data" => $data,
            "salesman_id" => $sale_user_id,
            "warehouse_id" => $warehouse_id,
            "start_date" => $start_date,
            "end_date" => $end_date
        ]);
    }
}
