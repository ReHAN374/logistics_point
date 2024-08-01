@extends('body.master')
@section('admin')
    <div class="content">


        <div class="mb-4 mt-4">
            <form action="{{ route('invoice_sale_report') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h3>Daily Sales Summary Report - {{ $data['date'] }}</h3>
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
                        <button type="submit" class="btn btn-sm btn-info" style="margin-top: 34px"><i
                                class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>
        <hr>
        <div class="row mt-4 mb-4">
            <div class="col-lg-4">
                <h4 class="text-dark">Total Sales: LKR {{ $data['totalSales'] }}</h4>
            </div>
            <div class="col-lg-4">
                <h4 class="text-dark">Number of Invoices: {{ $data['numInvoices'] }}</h4>
            </div>
            <div class="col-lg-4">
                <h4 class="text-dark">VAT Collected: LKR {{ $data['totalVAT'] }}</h4>
            </div>
        </div>

        <h4>Top Customers</h4>

        <table class="table table-bordered">
            <thead>
                <tr class="table-info">
                    <th class="text-center">Customer Name</th>
                    <th class="text-center">Total Sales</th>
                    <th class="text-center">Number of Invoices</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['topCustomers'] as $customerName => $metrics)
                    <tr>
                        <td class="text-center">{{ $customerName }}</td>
                        <td class="text-center">LKR {{ $metrics['total_sales'] }}</td>
                        <td class="text-center">{{ $metrics['num_invoices'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection
