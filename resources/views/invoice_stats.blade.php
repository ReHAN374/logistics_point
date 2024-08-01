@extends('body.master')
@section('admin')
    <div class="content">

        <div class="row mb-3 mx-3">
            <form action="{{ route('invoice_stats') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="customer">Invoice #</label>
                        <select class="form-control" id="customer_id" name="customer_id">
                            <option value="">Select Invoice</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->invoice_id }}">{{ $invoice->invoice_no }}</option>
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
                            <th scope="col" class="text-center">Invoice #</th>
                            <th scope="col" class="text-center">Invoice Date</th>
                            <th scope="col" class="text-center">Customer</th>
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
                                <th class="text-center">{{ $invoice->grand_total }}</th>
                                <th class="text-center">{{ $invoice->user_name }}</th>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="getIssueNotes({{ $invoice->id }})">
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
                        Issue Note Deatils</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="page-content container">
                        <div class="container px-0">
                            <div class="row">
                                <div class="col-5 col-lg-5">
                                    <table>
                                        <tr>
                                            <td>Issue Note #</td>
                                            <td>:</td>
                                            <td id="issue_note_no" class="pl-2"></td>
                                        </tr>
                                        <tr>
                                            <td>Invoice #</td>
                                            <td>:</td>
                                            <td id="invoice_no" class="pl-2"></td>
                                        </tr>
                                        <tr>
                                            <td>Customer</td>
                                            <td>:</td>
                                            <td id="customer_name" class="pl-2"></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-7 col-lg-7">
                                    <table>
                                        <tr>
                                            <td>Created By</td>
                                            <td>:</td>
                                            <td id="created_by" class="pl-2"></td>
                                        </tr>
                                        <tr>
                                            <td>Issue Status</td>
                                            <td>:</td>
                                            <td class="pl-2"><span id="issue_status" class="badge badge-info"
                                                    style="font-size: 16px"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Created Date Time</td>
                                            <td>:</td>
                                            <td id="created_at" class="pl-2"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-12">
                                    <div class="mt-4">
                                        <table class="table table-bordered" id="invoice_item_table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Warehouse</th>
                                                    <th>Product Code</th>
                                                    <th>Product</th>
                                                    <th class='text-center'>O/Qty</th>
                                                    <th class='text-center'>I/Qty</th>
                                                    <th class='text-center'>B/Qty</th>
                                                    <th class='text-center'>A/Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12 col-lg-12">
                                    <div class="mt-4">
                                        <table class="table table-bordered" id="balance_note_item_table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Balance Note #</th>
                                                    <th>Created User</th>
                                                    <th>Product</th>
                                                    <th class='text-center'>O/Qty</th>
                                                    <th class='text-center'>I/Qty</th>
                                                    <th class='text-center'>B/Qty</th>
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
