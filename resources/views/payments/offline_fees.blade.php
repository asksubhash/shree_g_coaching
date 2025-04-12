@extends('layouts.master_layout')
@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('ins_deo/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="offlineFeesDatatable">
                            <thead>
                                <tr>
                                    <th width="8%" class="text-center">S. No.</th>
                                    <th width="12%" class="text-center">Action</th>
                                    <th>Status</th>
                                    <th>Application No.</th>
                                    <th width="15%">Student Name</th>
                                    <th width="20%">Payment Type</th>
                                    <th>Payment Method</th>
                                    <th>Payment Date</th>
                                    <th>Transaction Id</th>
                                    <th>Amount</th>
                                    <th>Late Fees (If Any)</th>
                                    <th>Late Fees Amount</th>
                                    <th>Total</th>
                                    <th>Transaction Details</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('includes.modals.student_fees_modal')


@endsection
@section('pages-scripts')
<script>
    var offlineFeesDatatable = $('#offlineFeesDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: true,
        scrollCollapse: true,
        "ajax": {
            url: base_url + "/payment/fees/offline/get-for-datatable",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
            }
        },
        initComplete: function() {
            // $('[data-toggle="tooltip"]').tooltip()
        },
        "columns": [{
                data: null,
                name: 'id',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'action',
                className: "text-center"
            },
            {
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'application_no',
                name: 'application_no',
                className: "text-left"
            },
            {
                data: 'student_name',
                name: 'name',
                className: "text-left"
            },
            {
                data: 'payment_type',
                name: 'payment_type',
                className: "text-left"
            },
            {
                data: 'payment_method',
                name: 'payment_method',
                className: "text-left"
            },
            {
                data: 'payment_date',
                name: 'payment_date',
                className: "text-left"
            },
            {
                data: 'transaction_id',
                name: 'transaction_id',
                className: "text-center"
            },
            {
                data: 'amount',
                name: 'amount',
                className: "text-center"
            },
            {
                data: null,
                name: 'late_fees_if_any',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return (data.late_fees_if_any && data.late_fees_if_any == 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>';
                }
            },
            {
                data: 'late_fees_amount',
                name: 'late_fees_amount',
                className: "text-center"
            },
            {
                data: null,
                name: 'id',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return (data.late_fees_if_any) ? (parseInt(data.late_fees_amount) + parseInt(data.amount)) : data.amount;
                }
            },
            {
                data: 'transaction_details',
                name: 'transaction_details',
                className: "text-center"
            },
            {
                data: 'remarks',
                name: 'remarks',
                className: "text-center"
            }
        ],
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-12 col-md-4'B><'col-12 col-md-4'l><'col-12 col-md-4'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        buttons: []
    });
</script>

@endsection