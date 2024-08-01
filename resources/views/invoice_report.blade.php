@extends('body.master')
@section('admin')
    <div class="content">

        <div class="row mb-3 mx-3">
            <form action="{{ route('invoice_report') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="customer">Customer:</label>
                        <select class="form-control" id="customer_id" name="customer_id">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="col-md-3">
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

        <div class="row">
            <div class="col-12">

                <table class="table table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th scope="col">Invoice #</th>
                            <th scope="col" class="text-center">Invoice Date</th>
                            <th scope="col" class="text-center">Customer</th>
                            <th scope="col" class="text-center">Vat #</th>
                            <th scope="col" class="text-center">Vat Amount</th>
                            <th scope="col" class="text-center">Total</th>
                            <th scope="col" class="text-center">Sale User</th>
                            <th scope="col" class="text-center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $key => $invoice)
                            <tr>
                                <th scope="row">{{ $invoices->firstItem() + $key }}</th>
                                <th class="text-center">{{ $invoice->invoice_no }}</th>
                                <th class="text-center">{{ $invoice->invoice_date }}</th>
                                <th class="text-center">{{ $invoice->customer_name }}</th>
                                <th class="text-center">{{ $invoice->vat_no }}</th>
                                <th class="text-center">{{ $invoice->vat_amount }}</th>
                                <th class="text-center">{{ $invoice->grand_total }}</th>
                                <th class="text-center">{{ $invoice->user_name }}</th>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="getInvoiceItems({{ $invoice->id }})">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    @if (isset($invoices))
                        {!! $invoices->links() !!}
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="modal right fade" id="showInvoiceItemModal" tabindex="-1" role="dialog"
        aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title page-title text-secondary-d1" id="addProductModalLabel">
                        Invoice Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="page-content container">
                        <div class="container px-0">
                            <div class="row mt-4">
                                <div class="col-12 col-lg-12">
                                    <div class="mt-4">
                                        <table class="table table-bordered" id="invoice_item_table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Warehouse</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
