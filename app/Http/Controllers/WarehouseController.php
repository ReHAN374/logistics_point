<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouse = Warehouse::where('is_active', 1)->orderBy('id', 'desc')->paginate(5);
        return view('warehouse', ["warehouses" => $warehouse]);
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
                'warehouse_name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {
                Warehouse::create([
                    "code" => "WH-" . rand(1, 100),
                    "name" => $request->warehouse_name
                ]);

                Alert::success('Warehouse Added Successfully!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::success('Oops!', 'Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
            } else {
                $warehouse_data = Warehouse::findOrFail($request->id);
                return response()->json($warehouse_data);
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
                'warehouse_id' => 'required|integer',
                'edit_warehouse_name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Alert::warning($validator->errors()->first());
                return redirect()->back();
            } else {
                $warehouse = Warehouse::findOrFail($request->warehouse_id);
                if ($warehouse) {
                    $warehouse->name = $request->edit_warehouse_name;
                    $warehouse->save();
                    Alert::success('Warehouse updated successfully!');
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
                $warehouse = Warehouse::findOrFail($request->id);
                $warehouse->is_active = 0;
                $warehouse->save();

                return response()->json(["data" => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
