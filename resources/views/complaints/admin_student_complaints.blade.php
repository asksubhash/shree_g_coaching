@extends('layouts.master_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card p-3">

                <div class="">
                    <table class="table table-bordered table-striped w-100" id="complaintDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th>Record Status</th>
                                <th>Complaint Status</th>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                <th>Title</th>
                                <th>Complaint Date</th>
                                <th>Document</th>
                                <th style="max-width: 40%;">Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('pages-scripts')
<script>
    /**
     * DATATABLE
     */
    var complaintDatatable = $('#complaintDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: false,
        scrollCollapse: false,
        ordering: false,
        "ajax": {
            url: base_url + "/admin/complaints/students/fetch-for-datatable",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.filter_institute = $('#filter_institute').val();
            }
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
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'complaint_status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'student_name',
                name: 'student_name',
                className: "text-center"
            },
            {
                data: 'roll_number',
                name: 'roll_number',
                className: "text-center"
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.complaint_title;
                }
            },

            {
                data: 'complaint_date',
                name: 'complaint_date',
                className: "text-center"
            },
            {
                data: 'complaint_document_button',
                name: 'complaint_document',
                className: "text-center"
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.complaint_description
                }
            },


        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        complaintDatatable.ajax.reload();
    })
</script>
@endsection