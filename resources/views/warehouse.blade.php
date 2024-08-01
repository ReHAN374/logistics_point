@extends('body.master')
@section('admin')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#addWarehouseModal">
                    Add Warehouse
                </button>
                <table class="table table-bordered mb-5">
                    <thead>
                        <tr class="table-info">
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center">Created At</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    @if (isset($warehouses))
                        <tbody>
                            @foreach ($warehouses as $key => $warehouse)
                                <tr id="warehouse-row-{{ $warehouse->id }}">
                                    <th scope="row">{{ $warehouses->firstItem() + $key }}</th>
                                    <td>{{ $warehouse->code }}</td>
                                    <td class="text-center">{{ $warehouse->name }}</td>
                                    <td class="text-center">{{ $warehouse->created_at }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"
                                            onclick="getWarehouse({{ $warehouse->id }})">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="swal_confirm('warehouse',{{ $warehouse->id }})">
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
                    @if (isset($warehouses))
                        {!! $warehouses->links() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal right fade" id="addWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Warehouse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('create_warehouse') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="product_name">Name</label>
                                <input type="text" class="form-control" id="warehouse_name" name="warehouse_name"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Warehouse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="editWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Warehouse Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update_warehouse') }}" method="post">
                        @csrf
                        <input type="hidden" id="warehouse_id" name="warehouse_id" value="{{ $warehouse->id }}">
                        <div class="form-group">
                            <label for="product_code">Name</label>
                            <input type="text" class="form-control" id="edit_warehouse_name" name="edit_warehouse_name"
                                required value="{{ $warehouse->name }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Warehouse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
