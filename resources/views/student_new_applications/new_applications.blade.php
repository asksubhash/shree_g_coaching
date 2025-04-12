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
                        <table class="table table-bordered table-striped nowrap" id="studentDetailsDatatable">
                            <thead>
                                <tr>
                                    <th width="8%" class="text-center">S. No.</th>
                                    <th class="text-center">Action</th>
                                    <th>Status</th>
                                    <th>Is Approved</th>
                                    <th>Application No.</th>
                                    <th width="15%">Name</th>
                                    <th>Contact Number</th>
                                    <th>Email ID</th>
                                    <th>DOB</th>
                                    <th>State</th>
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

@endsection
@section('pages-scripts')
<script>
    var studentDetailsDatatable = $('#studentDetailsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: false,
        scrollCollapse: false,
        ordering: false,
        "ajax": {
            url: base_url + "/study-center/student/new-applications/fetch-for-datatable",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.type = 'ALL';
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    console.log(data.is_approved)
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
                data: 'application_no',
                name: 'application_no',
                className: "text-left"
            },
            {
                data: 'name',
                name: 'name',
                className: "text-left"
            },
            {
                data: 'contact_number',
                name: 'contact_number',
                className: "text-left"
            },
            {
                data: 'email',
                name: 'email',
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
            }
        ],
        "columnDefs": [{
            "targets": ['_ALL'],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-12 col-md-4'B><'col-12 col-md-4'l><'col-12 col-md-4'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        buttons: []
    });
</script>

@yield('stu-fees-modal-script')
@endsection