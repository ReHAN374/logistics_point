@extends('body.master')
@section('admin')
    <div class="content">

        <div class="row mb-3 mx-3">
            <form action="{{ route('invoice_outstanding_stats') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="customer">Customer:</label>
                        <select class="form-control" id="customer_id" name="customer_id">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="product">Product:</label>
                        <select class="form-control" id="item_id" name="item_id">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                    <div class="col-md-2">
                        <label for="submit">&nbsp;</label>
                        <button type="submit" class="btn btn-sm btn-info form-control"><i class="fa fa-search"></i>
                            Search</button>
                    </div>
                </div>
            </form>
        </div>


        <div class="row">
            <div class="col-12">

                <table class="table table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th scope="col" class="text-center">Invoice #</th>
                            <th scope="col" class="text-center">Invoice Date</th>
                            <th scope="col" class="text-center">Total Vat</th>
                            <th scope="col" class="text-center">Total Qty</th>
                            <th scope="col" class="text-center">Total U/price</th>
                            <th scope="col" class="text-center">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outstandings as $key => $outstanding)
                            <tr>
                                <th scope="row">{{ $outstandings->firstItem() + $key }}</th>
                                <th class="text-center">{{ $outstanding->invoice_no }}</th>
                                <th class="text-center">{{ $outstanding->customer_name }}</th>
                                <th class="text-center">{{ $outstanding->total_vat }}</th>
                                <th class="text-center">{{ $outstanding->total_qty }}</th>
                                <th class="text-center">{{ $outstanding->total_unit_price }}</th>
                                <th class="text-center">{{ $outstanding->total_amount }}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    @if (isset($outstandings))
                        {!! $outstandings->links() !!}
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
