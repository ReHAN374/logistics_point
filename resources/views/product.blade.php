@extends('body.master')
@section('admin')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#addProductModal">
                    Add Product
                </button>
                <table class="table table-bordered mb-5">
                    <thead>
                        <tr class="table-info">
                            <th scope="col">#</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col">Product Code</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Product Unit</th>
                            <th scope="col" class="text-center">Product Unit Price</th>
                            <th scope="col" class="text-center">Stock Available</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    @if (isset($products))
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr id="product-row-{{ $product->id }}">
                                    <th scope="row">{{ $products->firstItem() + $key }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->product_unit }}</td>
                                    <td class="text-center">{{ number_format($product->product_unit_price, 2) }}</td>
                                    <td class="text-center">{{ number_format($product->stock_available, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"
                                            onclick="getProduct({{ $product->id }})">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="swal_confirm('product',{{ $product->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="d-flex justify-content-center">
                    @if (isset($products))
                        {!! $products->links() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal right fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('create_product') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="warehouse">Warehouse</label>
                                <select class="form-control" name="warehouse_id">
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_code">Product Code</label>
                                <input type="text" class="form-control" id="product_code" name="product_code" required>
                            </div>
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                            <div class="form-group">
                                <label for="product_unit">Product Unit</label>
                                <input type="text" class="form-control" id="product_unit" name="product_unit" required>
                            </div>
                            <div class="form-group">
                                <label for="product_unit_price">Product Unit Price</label>
                                <input type="number" class="form-control" id="product_unit_price" name="product_unit_price"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="stock_available">Stock Available</label>
                                <input type="number" class="form-control" id="stock_available" name="stock_available"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update_product') }}" method="POST">
                        @csrf
                        <input type="hidden" id="product_id" name="product_id" value="0">
                        <div class="form-group">
                            <label for="warehouse">Warehouse</label>
                            <select class="form-control" name="edit_warehouse_id" id="edit_warehouse_id">
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"> {{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_code">Product Code</label>
                            <input type="text" class="form-control" id="edit_product_code" name="edit_product_code"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" id="edit_product_name" name="edit_product_name"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="product_unit">Product Unit</label>
                            <input type="text" class="form-control" id="edit_product_unit" name="edit_product_unit"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="product_unit_price">Product Unit Price</label>
                            <input type="text" class="form-control" id="edit_product_unit_price"
                                name="edit_product_unit_price" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_available">Stock Available</label>
                            <input type="text" class="form-control" id="edit_stock_available"
                                name="edit_stock_available" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
