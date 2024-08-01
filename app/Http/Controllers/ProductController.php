<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::leftjoin('warehouses', 'products.warehouse_id', 'warehouses.id')
            ->select('products.*', 'warehouses.name')
            ->where('products.is_active', 1)
            ->where('warehouses.is_active', 1)
            ->orderBy('id', 'desc')
            ->paginate(5);

        $warehouse = Warehouse::where('is_active', 1)->get();
        return view('product', ["products" => $products, "warehouses" => $warehouse]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'warehouse_id' => 'required|integer',
                'product_code' => 'required|string|max:20|unique:products,product_code',
                'product_name' => 'required|string|max:255',
                'product_unit' => 'required|string|max:50',
                'product_unit_price' => 'required|numeric|min:0',
                'stock_available' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {
                // check if the product code is exist in the current warehouse. if so it cannot be added 
                $is_product_code_exist = Product::where('product_code', $request->product_code)->where('warehouse_id', $request->warehouse_id)->where('is_active', 1)->orderBy('id', 'desc')->first();
                if (isset($is_product_code_exist->id)) {
                    Alert::warning('Product code cannot be duplicated in same warehouse!');
                    return redirect()->back();
                } else {
                    Product::create([
                        "warehouse_id" => $request->warehouse_id,
                        "product_code" => $request->product_code,
                        "product_name" => $request->product_name,
                        "product_unit" => $request->product_unit,
                        "product_unit_price" => $request->product_unit_price,
                        "stock_available" => $request->stock_available
                    ]);

                    Alert::success('Product Added Successfully!');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Alert::success('Oops!', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $product = Product::where('id', $request->id)->where('is_active', 1)->orderBy('id', 'desc')->first();

            if ($product) {
                return response()->json(['success' => 1, "data" => $product], 200);
            } else {
                return response()->json(['success' => 0, "data" => null], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function single_product_data(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $query = Product::where('product_code', $request->code)->where('is_active', 1);

            if ($request->filled('warehouse_id')) {
                $query->where('warehouse_id', $request->warehouse_id);
            }

            $product = $query->orderBy('id', 'desc')->first();

            $warehouses = Product::where('product_code', $request->code)->where('is_active', 1)->orderByDesc('id')->with('warehouse')->get();

            if ($product) {
                return response()->json(['success' => 1, "data" => ["product" => $product, "warehouses" => $warehouses]], 200);
            } else {
                return response()->json(['success' => 0, "data" => null], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer',
                'edit_warehouse_id' => 'required|exists:products,warehouse_id',
                'edit_product_code' => 'required|string',
                'edit_product_name' => 'required|string|max:255',
                'edit_product_unit' => 'required|string|max:10',
                'edit_product_unit_price' => 'required|numeric|min:0',
                'edit_stock_available' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->route('product');
            } else {
                $is_product_code_exist = Product::where('product_code', $request->edit_product_code)->where('warehouse_id', $request->edit_warehouse_id)->where('is_active', 1)->orderBy('id', 'desc')->first();
                if (isset($is_product_code_exist->id)) {
                    $is_product_code_exist->product_name = $request->edit_product_name;
                    $is_product_code_exist->product_unit = $request->edit_product_unit;
                    $is_product_code_exist->product_unit_price = $request->edit_product_unit_price;
                    $is_product_code_exist->stock_available = $request->edit_stock_available;
                    $is_product_code_exist->save();
                    Alert::success('Product Updated except the product code cannot be duplicated in same warehouse!');
                    return redirect()->back();
                } else {
                    $product = Product::findOrFail($request->product_id);
                    $product->warehouse_id = $request->edit_warehouse_id;
                    $product->product_code = $request->edit_product_code;
                    $product->product_name = $request->edit_product_name;
                    $product->product_unit = $request->edit_product_unit;
                    $product->product_unit_price = $request->edit_product_unit_price;
                    $product->stock_available = $request->edit_stock_available;
                    $product->save();

                    Alert::success('Product updated successfully!');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            Alert::success('Oops!', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                $product = Product::findOrFail($request->id);
                $product->is_active = 0;
                $product->save();

                return response()->json(["data" => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
