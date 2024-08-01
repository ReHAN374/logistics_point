@extends('body.master')
@section('admin')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#addInvoiceModal">
                    Add Purchase Order
                </button>
                <table class="table table-bordered mb-5">
                    <thead>
                        <tr class="table-info">
                            <th scope="col">#</th>
                            <th scope="col">Purchase Order #</th>
                            <th scope="col" class="text-center">Purchase Date</th>
                            <th scope="col" class="text-center">Supplier</th>
                            <th scope="col" class="text-center">Address</th>
                            <th scope="col" class="text-center">Total</th>
                            <th scope="col" class="text-center">Created By</th>
                            <th scope="col" class="text-center">Option</th>
                        </tr>
                    </thead>
                    @if (isset($porders))
                        <tbody>
                            @foreach ($porders as $key => $order)
                                <tr>
                                    <th scope="row">{{ $porders->firstItem() + $key }}</th>
                                    <td>{{ $order->purchase_order_no }}</td>
                                    <td class="text-center">{{ $order->purchase_date }}</td>
                                    <td class="text-center">{{ $order->supplier_name }}</td>
                                    <td class="text-center">{{ $order->supplier_address }}</td>
                                    <td class="text-center">{{ $order->grand_total }}</td>
                                    <td class="text-center">{{ $order->user_name }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-sm"
                                            onclick="getInvoiceItems({{ $order->id }})">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteModal{{ $order->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="d-flex justify-content-center">
                    @if (isset($porders))
                        {!! $porders->links() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title page-title text-secondary-d1" id="addProductModalLabel">
                        Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="page-content container">
                        <div class="container px-0">
                            <div class="row mt-4">
                                <div class="col-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div>
                                                <div class="form-group">
                                                    <label for="supplier_id">Select a Customer</label>
                                                    <select id="supplier_id" name="supplier_id"
                                                        class="form-control form-control-sm"
                                                        onchange="getCustomerData($(this).val())" required>
                                                        <option value="">Select Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">
                                                                {{ $customer->customer_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="text-grey-m2">
                                                <div class="my-1">
                                                    <span id="txtVatNumber"></span>
                                                </div>
                                                <div class="my-1">
                                                    <span id="txtAddress"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="product_id">Select a Product</label>
                                                <select id="product_id" name="product_id"
                                                    class="form-control form-control-sm"
                                                    onchange="getProductByCode($(this).val())" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->product_code }}">
                                                            {{ $product->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="text-grey-m2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="my-1">
                                                            <div class="form-group">
                                                                <label for="unit_price">Unit Price</label>
                                                                <input type="text" id="unit_price" name="unit_price"
                                                                    class="form-control form-control-sm" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="my-1">
                                                            <div class="form-group">
                                                                <label for="unit">Unit of Measure</label>
                                                                <input type="text" id="unit" name="unit"
                                                                    class="form-control form-control-sm" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-8">
                                                        <div class="my-1">
                                                            <div class="form-group">
                                                                <label for="warehouse_id">Select a Warehouse</label>
                                                                <div id="warehouse_content">
                                                                    <select class="form-control form-control-sm">
                                                                        <option value="">Select Warehouse</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="my-1 mt-4">
                                                            <span id="txtAvailbaleQty"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="qty">Quantity</label>
                                                            <input type="number" id="qty" name="qty"
                                                                class="form-control" min="0" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-4">
                                        <table class="table table-bordered" id="invoice_table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th style=display:none;>Product Code</th>
                                                    <th style=display:none;>Warehouse ID</th>
                                                    <th>Warehouse</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Value (Total)</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>


                                        <div class="row border-b-2 brc-default-l2"></div>

                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0"></div>

                                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                                <div class="row my-2">
                                                    <div class="col-7 text-right">
                                                        SubTotal
                                                    </div>
                                                    <div class="col-5">
                                                        <span class="text-120 text-secondary-d1"
                                                            id="sub_total">0.00</span>
                                                    </div>
                                                </div>

                                                <div class="row my-2">
                                                    <div class="col-7 text-right">
                                                        VAT (18%)
                                                    </div>
                                                    <div class="col-5">
                                                        <span class="text-110 text-secondary-d1"
                                                            id="vat_amount">0.00</span>
                                                    </div>
                                                </div>

                                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                                    <div class="col-7 text-right">
                                                        Total Amount
                                                    </div>
                                                    <div class="col-5">
                                                        <span class="text-150 text-success-d3 opacity-2"
                                                            id="grand_total">0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />

                                        <div>
                                            <button type="button"
                                                class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0"
                                                id="btn_porder">Create
                                                Purchase Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <div style="display: none">
        <div id="print_content">
            <div class="page-content container">
                <div class="page-header text-blue-d2">
                    <h1 class="page-title text-secondary-d1">
                        Invoice -
                        <small class="page-info">
                            {{-- <i class="fa fa-angle-double-right text-80"></i> --}}
                            <span id="invoice_id"></span>
                        </small>
                    </h1>
                </div>

                <div class="container px-0">
                    <div class="row mt-4">
                        <div class="col-12 col-lg-12">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <span class="text-sm text-grey-m2 align-middle">To:</span>
                                        <span class="text-600 text-110 text-blue align-middle"
                                            id="invoice_customer_name">Alex Doe</span>
                                    </div>
                                    <div class="text-grey-m2">
                                        <div class="my-1">
                                            <span class="text-600 text-110 text-blue align-middle"
                                                id="invoice_customer_address">Street, City</span>
                                        </div>
                                        <span class="text-600 text-110 text-blue align-middle"
                                            id="invoice_customer_vat_no">Vat #</span>
                                    </div>
                                </div>

                                <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                                    <hr class="d-sm-none" />
                                    <div class="text-grey-m2">
                                        <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                            Invoice User & Date
                                        </div>
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                                class="text-600 text-90" id="invoice_sale_user">invoice sale user</span>
                                        </div>
                                        <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i>
                                            <span class="text-600 text-90" id="invoice_date">invoice date</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="text-95 text-secondary-d3">
                                    <table class="table table-bordered" id="invoice_printable_table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Warehouse</th>
                                                <th>Product</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row border-b-2 brc-default-l2"></div>

                                <div class="row mt-3">
                                    <div class="col-6 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                        Thank you for your business..
                                    </div>

                                    <div class="col-6 col-sm-5 text-grey text-90 order-first order-sm-last">
                                        <div class="row my-2">
                                            <div class="col-7 text-right">
                                                SubTotal
                                            </div>
                                            <div class="col-5">
                                                <span class="text-120 text-secondary-d1"
                                                    id="invoice_sub_total">0.00</span>
                                            </div>
                                        </div>

                                        <div class="row my-2">
                                            <div class="col-7 text-right">
                                                Vat (18%)
                                            </div>
                                            <div class="col-5">
                                                <span class="text-110 text-secondary-d1" id="invoice_vat">0.00</span>
                                            </div>
                                        </div>

                                        <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                            <div class="col-7 text-right">
                                                Grand Total
                                            </div>
                                            <div class="col-5">
                                                <span class="text-150 text-success-d3 opacity-2"
                                                    id="invoice_grand_total">0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($porders as $invoice1)
        <!-- Modal -->
        <div class="modal fade" id="deleteModal{{ $invoice1->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel{{ $invoice1->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $invoice1->id }}">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this record?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('invoice_delete') }}" method="POST">
                            <input type="text" id="id" name="id" value="{{ $invoice1->id }}" hidden>
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach



@endsection
