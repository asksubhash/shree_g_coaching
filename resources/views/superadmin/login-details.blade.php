@extends('superadmin.layouts.superadmin_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('superadmin/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ (isset($page_title))?$page_title:'Login Details' }}</li>
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
                    <table class="table table-bordered table-striped w-100 nowrap" id="dataTable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>IP Address</th>
                                <th>Login Time</th>
                                <th width="12%" class="text-center">Status</th>
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
    var dataTable = $('#dataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "autoWidth": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/ajax/get/all-login-details",
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
                data: 'name',
                name: 'name',
                className: "text-center"
            },
            {
                data: 'role',
                name: 'role',
                className: "text-center"
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                className: "text-center"
            },
            {
                data: 'login_datetime',
                name: 'login_datetime',
                className: "text-center"
            },
            {
                data: 'status',
                name: 'status',
                className: "text-center"
            },


        ],
        "columnDefs": [{
            "targets": [0, 1, 2],
            "orderable": false,
            "sorting": false
        }]
    });
</script>
@endsection