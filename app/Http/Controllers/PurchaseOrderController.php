<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $porders = PurchaseOrder::join('users', 'users.id', 'purchase_orders.user_id')->where('purchase_orders.is_active', 1)->select('purchase_orders.*', 'users.customer_name as user_name')->orderBy('purchase_orders.id', 'desc')->paginate(5);
        $products = Product::where('is_active', 1)->groupBy('product_code')->orderBy('id', 'desc')->get();
        $warehouses = Warehouse::where('is_active', 1)->orderBy('id', 'desc')->get();
        $customers = User::where('user_type', 4)->where('is_active', 1)->orderBy('id', 'desc')->get();
        return view('purchase_order', ["porders" => $porders, "products" => $products, "warehouses" => $warehouses, "customers" => $customers]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'supplier_name' => 'required|string|max:255',
                'supplier_address' => 'required|string|max:255',
                'grand_total' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                date_default_timezone_set('Asia/Colombo');
                $date_time = date('Y-m-d H:i:s');

                $purcahses = PurchaseOrder::create([
                    "user_id" => auth()->user()->id,
                    "purchase_order_no" => 'PO-' . rand(1, 10000),
                    "purchase_date" => $date_time,
                    "supplier_name" => $request->supplier_name,
                    "supplier_address" => $request->supplier_address,
                    "grand_total" => $request->grand_total
                ]);

                $po_id = isset($purcahses) && intval($purcahses->id) > 0 ? $purcahses->id : 0;

                if (isset($po_id)) {
                    foreach ($request->items as $item) {
                        PurchaseOrderItem::create([
                            "purchase_order_id" => $po_id,
                            "warehouse_id" => $item['warehouse_id'],
                            "product_name" => $item['product_name'],
                            "product_code" => $item['product_code'],
                            "qty" => $item['qty'],
                            "unit_price" => $item['unit_price'],
                            "sub_total" => $item['qty'] * $item['unit_price']
                        ]);
                    }


                    $last_po = PurchaseOrder::join('users', 'users.id', 'purchase_orders.user_id')
                        ->select('purchase_orders.*', 'users.customer_name as user_name')
                        ->where('purchase_orders.is_active', 1)
                        ->where('purchase_orders.id', $po_id)
                        ->orderBy('purchase_orders.id', 'desc')
                        ->first();

                    $last_po_item = PurchaseOrderItem::join('warehouses', 'warehouses.id', 'purchase_order_items.warehouse_id')
                        ->join('products', 'products.product_code', 'purchase_order_items.product_code')
                        ->select('purchase_order_items.*', 'warehouses.name as warehouse_name', 'products.product_name')
                        ->where('purchase_order_items.purchase_order_id', $po_id)
                        ->where('purchase_order_items.is_active', 1)
                        ->groupBy('purchase_order_items.product_code')
                        ->orderBy('purchase_order_items.id', 'desc')
                        ->get();


                    return response()->json(['success' => 1, 'data' => [
                        'last_po' => $last_po,
                        'last_po_item' => $last_po_item
                    ]], 200);
                } else {
                    return response()->json(['success' => 0, 'data' => 'Purchase_order not saved!'], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
