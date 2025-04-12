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
                                <th width="15%">Name</th>
                                <th width="20%">Email ID</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>DOB</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Is Approved</th>
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
            url: base_url + "/new_application/inter/fetch-all",
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
            {
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return `<span class="badge bg-warning bx bx-5x"><i class="bx bx-check-shield"></i></span>`;
                }
            },
        ],
        "columnDefs": [{
            "targets": [0, 1, 8, 9],
            "orderable": false,
            "sorting": false
        }]
    });

    // ---------------------------------------------
    // approve student
    $(document).on('click', '.approveStudent', function() {
        var id = $(this).attr('id');
        if (id) {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'You want to approve this record?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#555',
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.value) {
                    $.ajax({
                        url: base_url + '/high-school/approve',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                studentDetailsDatatable.ajax.reload();
                            } else if (response.status == false) {
                                toastr.error(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(errors) {
                            toastr.error(errors.statusText)
                        }
                    });
                }
            });
        } else {
            toastr.error('Something went wrong. Please try again.');
        }
    });
</script>
@endsection