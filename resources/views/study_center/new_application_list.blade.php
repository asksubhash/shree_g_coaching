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
                    <table class="table table-bordered table-striped w-100 nowrap" id="studyCenterDataTable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th width="12%" class="text-center">Action</th>
                                <th>Status</th>
                                <th>Is Approved</th>
                                <th>Institute Name</th>
                                <th width="15%">Person Name</th>
                                <th width="20%">Email ID</th>
                                <th>Conatct No</th>
                                <th>Address 1</th>
                                <th>State</th>
                                <th>district</th>
                                <th>City</th>
                                <th>Pin Code</th>

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
    var studyCenterDataTable = $('#studyCenterDataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        "ajax": {
            url: base_url + "/study-center/fetch-new-registers",
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

                    if (data.is_verified == "0") {
                        return `<span class="badge bg-primary"><i class="bx bx-check-shield"></i> Pending</span>`;
                    }

                    if (data.is_verified == "1") {
                        return `<span class="badge bg-success"><i class="bx bx-check"></i> Approved</span>`;
                    }

                    if (data.is_verified == "2") {
                        return `<span class="badge bg-danger"><i class="bx bx-times"></i> Rejected</span>`;
                    }

                    return ``;
                }
            },
            {
                data: 'institute_name',
                name: 'institute_name',
                className: "text-left"
            },
            {
                data: 'name',
                name: 'name',
                className: "text-left"
            },
            {
                data: 'email_id',
                name: 'email_id',
                className: "text-left"
            },
            {
                data: 'contact_no',
                name: 'contact_no',
                className: "text-left"
            },
            {
                data: 'address1',
                name: 'address1',
                className: "text-left"
            },
            {
                data: 'state_name',
                name: 'state_name',
                className: "text-center"
            },
            {
                data: 'district_name',
                name: 'district_name',
                className: "text-center"
            },
            {
                data: 'city_name',
                name: 'city_name',
                className: "text-center"
            },
            {
                data: 'pin_code',
                name: 'pin_code',
                className: "text-center"
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
                        url: base_url + '/graduation/approve',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                studyCenterDataTable.ajax.reload();
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