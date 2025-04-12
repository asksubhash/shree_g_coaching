@extends('layouts.master_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('admin/dashboard') }}"><i class="bx bx-home-alt"></i></a>
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

                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="examsAttendedDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th width="12%">Action</th>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Exams Completed</th>
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
    var examsAttendedDatatable = $('#examsAttendedDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ordering: false,
        "ajax": {
            url: base_url + "/exams-setup/students/exams-attended/get-datatable-data",
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
                data: 'action',
                className: "text-center",
                width: '12%'
            },
            {
                data: 'roll_number',
                name: 'roll_number',
                className: "text-center"
            },
            {
                data: 'name',
                name: 'name',
                className: "text-center"
            },
            {
                data: 'exams_completed',
                name: 'id',
                className: "text-center"
            }
        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        examsAttendedDatatable.ajax.reload();
    })
</script>
@endsection