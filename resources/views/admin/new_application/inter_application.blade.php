@extends('layouts.master_layout')
@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to(strtolower(auth::user()->role_code) . '/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="studentDetailsDatatable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th width="12%" class="text-center">Action</th>
                                <th>Status</th>
                                <th>Is Approved</th>
                                <th>Institute Name</th>
                                <th width="15%">Name</th>
                                <th width="20%">Email ID</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>DOB</th>
                                <th>State</th>

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
    var studentDetailsDatatable = $('#studentDetailsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        "ajax": {
            url: base_url + "/students/inter/fetch-all",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
            }
        },
        initComplete: function() {
            $('[data-toggle="tooltip"]').tooltip()
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data.is_approved == "0") {
                        return `<span class="badge bg-primary"><i class="bx bx-check-shield"></i> Pending</span>`;
                    }

                    if (data.is_approved == "1") {
                        return `<span class="badge bg-success"><i class="bx bx-check"></i> Approved</span>`;
                    }

                    if (data.is_approved == "2") {
                        return `<span class="badge bg-danger"><i class="bx bx-times"></i> Rejected</span>`;
                    }

                    return ``;
                }
            },

            {
                data: 'institute_name',
                name: 'name',
                className: "text-left"
            },
            {
                data: 'name',
                name: 'name',
                className: "text-left"
            },
            {
                data: 'email',
                name: 'email',
                className: "text-left"
            },
            {
                data: 'father_name',
                name: 'father_name',
                className: "text-left"
            },
            {
                data: 'mother_name',
                name: 'mother_name',
                className: "text-left"
            },
            {
                data: 'dob',
                className: "text-center"
            },
            {
                data: 'state_name',
                name: 'state_name',
                className: "text-center"
            },

        ],
        "columnDefs": [{
            "targets": [0, 1, 8, 9],
            "orderable": false,
            "sorting": false
        }]
    });
</script>
@endsection