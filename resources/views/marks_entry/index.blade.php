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
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered" id="marksEntryDatatable">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>Exam Name</th>
                                <th>Exam Date</th>
                                <th>Course Name/Code</th>
                                <th>Student Name/Roll No.</th>
                                <th>Action</th>
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
    // -----------------------------------------
    var marksEntryDatatable = $('#marksEntryDatatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "autoWidth": false,
        "responsive": false,
        "ajax": {
            url: base_url + "/marks-entry/get-all-list",
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return 'Exam Test 1';
                }
            },
            {
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return '12-12-2023';
                }
            },
            {
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return data.course.course_name + '<br />(' + data.course.course_code + ')';
                }
            },
            {
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return 'Static Name' + '<br />(' + data.student_roll_no + ')';
                }
            },

            {
                data: 'action',
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

    $(window).on('load', function() {
        $('.dataTables_wrapper .dt-buttons').append(
            '<a href="' + base_url + '/marks-entry/add" class="btn btn-primary" ><i class="bx bx-plus"></i> Add Marks Entry</a>');
    })

    // {
    //     data: 'subject.name',
    //     className: "text-center"
    // }, {
    //     data: null,
    //     className: "text-center",
    //     render: function(data, type, row, meta) {
    //         return data.marks_obtained + '/' + data.max_marks;
    //     }
    // },
</script>
@endsection