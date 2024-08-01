@extends('body.master')
@section('admin')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered mb-5">
                    <thead>
                        <tr class="table-info">
                            <th scope="col">#</th>
                            <th scope="col" class="text-center">Issue Note #</th>
                            <th scope="col" class="text-center">Invoice #</th>
                            <th scope="col" class="text-center">Customer</th>
                            <th scope="col" class="text-center">Created By</th>
                            <th scope="col" class="text-center">Issued By</th>
                            <th scope="col" class="text-center">Created At</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Options</th>
                        </tr>
                    </thead>
                    @if (isset($issue_notes))
                        <tbody>
                            @foreach ($issue_notes as $key => $issue_note)
                                <tr>
                                    <th scope="row">{{ $issue_notes->firstItem() + $key }}</th>
                                    <td class="text-center">{{ $issue_note->issue_note_no }}</td>
                                    <td class="text-center">{{ $issue_note->invoice->invoice_no }}</td>
                                    <td class="text-center">{{ $issue_note->invoice->customer_name }}</td>
                                    <td class="text-center">{{ $issue_note->createdBy->customer_name }}</td>
                                    <td class="text-center">
                                        {{ $issue_note->issuedBy != null ? auth()->user()->customer_name : '-' }}
                                    </td>
                                    <td class="text-center">{{ $issue_note->created_at }}</td>
                                    <td class="text-center">
                                        <div class="badge {{ $issue_note->is_active == 0 ? 'badge-warning' : ($issue_note->is_active == 1 ? 'badge-success' : ($issue_note->is_active == 2 ? 'badge-danger' : ($issue_note->is_active == 3 ? 'badge-secondary' : 'badge-danger'))) }}"
                                            style="font-size: 0.9rem;">
                                            {{ $issue_note->is_active == 0 ? 'Pending' : ($issue_note->is_active == 1 ? 'Issued' : ($issue_note->is_active == 2 ? 'Rejected' : ($issue_note->is_active == 3 ? 'Partially Issued' : 'Deleted'))) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-sm"
                                            onclick="getIssueNoteItems({{ $issue_note->warehouse_id }},{{ $issue_note->issue_note_id }})">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteModal{{ $issue_note->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="d-flex justify-content-center">
                    @if (isset($issue_notes))
                        {!! $issue_notes->links() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="showIssueNoteItemModal" tabindex="-1" role="dialog"
        aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title page-title text-secondary-d1" id="addProductModalLabel">
                        Issue Note Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="page-content container">
                        <div class="container px-0">
                            <div class="mt-4">
                                <div id="print_content">
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
                                                            <th class='text-center'>B/Qty</th>
                                                            <th class='text-center'>A/stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-primary mr-2" id="issueItems">Submit Items</button>
                                    <button class="btn btn-info" id="printItems">Print Note</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($issue_notes as $issue_note1)
        <!-- Modal -->
        <div class="modal fade" id="deleteModal{{ $issue_note1->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel{{ $issue_note1->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $issue_note1->id }}">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this record?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('issue_note_delete') }}" method="POST">
                            <input type="text" id="id" name="id" value="{{ $issue_note1->id }}" hidden>
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


@endsection
