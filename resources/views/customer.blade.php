@extends('body.master')
@section('admin')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#addUserModal">
                    Add User
                </button>
                <table class="table table-bordered mb-5">
                    <thead>
                        <tr class="table-info">
                            <th scope="col">#</th>
                            <th scope="col">User Type</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col">Name</th>
                            <th scope="col">Contact Number</th>
                            <th scope="col">Email</th>
                            <th scope="col">Vat No</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    @if (isset($users))
                        <tbody>
                            @foreach ($users as $key => $data)
                                <tr>
                                    <th scope="row">{{ $users->firstItem() + $key }}</th>
                                    <td>{{ $data->user_type == 1 ? 'Admin' : ($data->user_type == 2 ? 'Sales User' : ' Customer') }}
                                    </td>
                                    <td>{{ $data->name != 0 ? $data->name : '-' }}</td>
                                    <td>{{ $data->customer_name }}</td>
                                    <td>{{ $data->customer_phone_no }}</td>
                                    <td>{{ $data->customer_email }}</td>
                                    <td>{{ $data->customer_vat_no }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"
                                            onclick="getUser({{ $data->id }})">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="swal_confirm('user',{{ $data->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="d-flex justify-content-center">
                    @if (isset($users))
                        {!! $users->links() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal right fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('create_user') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="user_type">User Type</label>
                            <select class="form-control" id="user_type" name="user_type" required>
                                <option value="">Select User Type</option>
                                <option value="1">Admin</option>
                                <option value="2">Sale User</option>
                                <option value="3">Customer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_type">Warehouse</label>
                            <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                                <option value="">Select warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customer_name">Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_address">Address</label>
                            <input type="text" class="form-control" id="customer_address" name="customer_address"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone_no">Contact No</label>
                            <input type="text" class="form-control" id="customer_phone_no" name="customer_phone_no"
                                maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_vat_no">VAT No</label>
                            <input type="text" class="form-control" id="customer_vat_no" name="customer_vat_no" required>
                        </div>
                        <hr style="border-bottom: 1px solid rgb(214, 211, 211)" class="mt-4" />
                        <div class="form-group">
                            <label for="customer_email">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal right fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update_user') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" id="edit_customer_id" name="edit_customer_id"
                            value="0">
                        <div class="form-group">
                            <label for="user_type">User Type</label>
                            <select class="form-control" id="edit_user_type" name="edit_user_type" required>
                                <option value="">Select User Type</option>
                                <option value="1">Admin</option>
                                <option value="2">Sale User</option>
                                <option value="3">Customer</option>
                            </select>
                        </div>
                        <div class="form-group" id="warehouse_content">
                            <label for="user_type">Warehouse</label>
                            <select class="form-control" id="edit_warehouse_id" name="edit_warehouse_id" required>
                                <option value="">Select warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customer_name">Name</label>
                            <input type="text" class="form-control" id="edit_customer_name" name="edit_customer_name"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="customer_address">Address</label>
                            <input type="text" class="form-control" id="edit_customer_address"
                                name="edit_customer_address" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone_no">Contact No</label>
                            <input type="text" class="form-control" id="edit_customer_phone_no" maxlength="10"
                                name="edit_customer_phone_no" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_vat_no">VAT No</label>
                            <input type="text" class="form-control" id="edit_customer_vat_no"
                                name="edit_customer_vat_no" required>
                        </div>
                        <button type="submit" class="btn btn-info">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
