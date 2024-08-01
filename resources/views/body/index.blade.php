@extends('body.master')
@section('admin')
    <div class="content">
        <h2>Welcome to the Dashboard</h2>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Customer Count</h5>
                        <p class="card-text text-dark">{{ $customers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Product Count</h5>
                        <p class="card-text text-dark">{{ $products }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Invoice Count</h5>
                        <p class="card-text text-dark">{{ $invoices }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">

                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="customer">Salesman:</label>
                            <select class="form-control" id="salesman_id" name="salesman_id">
                                <option value="">-Select-</option>
                                @foreach ($salesman as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('salesman_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->customer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="customer">Warehouse:</label>
                            <select class="form-control" id="warehouse_id" name="warehouse_id">
                                <option value="">-Select-</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-info" style="margin-top: 34px"><i
                                    class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div id="piechart" style="width: 100%; height: 500px;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
