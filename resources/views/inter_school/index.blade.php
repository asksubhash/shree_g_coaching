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
                    <ul class="nav nav-tabs nav-danger" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Total Applications</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
                                <div class="d-flex align-items-center text-dark">
                                    <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Payment Done</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#dangercontact" role="tab" aria-selected="false" tabindex="-1">
                                <div class="d-flex align-items-center text-dark">
                                    <div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Total Enrolled</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane fade active show" id="dangerhome" role="tabpanel">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped w-100 nowrap" id="studentDetailsDatatable">
                                    <thead>
                                        <tr>
                                            <th width="8%" class="text-center">S. No.</th>
                                            <th width="12%" class="text-center">Action</th>
                                            <th>Status</th>
                                            <th>Is Approved</th>
                                            <th>Payment Status</th>
                                            <th>Application No.</th>
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
                        <div class="tab-pane fade" id="dangerprofile" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped w-100 nowrap" id="studentPaymentDoneDatatable">
                                    <thead>
                                        <tr>
                                            <th width="8%" class="text-center">S. No.</th>
                                            <th width="12%" class="text-center">Action</th>
                                            <th>Status</th>
                                            <th>Is Approved</th>
                                            <th>Payment Status</th>
                                            <th>Application No.</th>
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
                        <div class="tab-pane fade" id="dangercontact" role="tabpanel">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped w-100 nowrap" id="studentTotalEnrolledDatatable">
                                    <thead>
                                        <tr>
                                            <th width="8%" class="text-center">S. No.</th>
                                            <th width="12%" class="text-center">Action</th>
                                            <th>Status</th>
                                            <th>Is Approved</th>
                                            <th>Payment Status</th>
                                            <th>Application No.</th>
                                            <th>Roll No.</th>
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
    var studentDetailsDatatable = $('#studentDetailsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ordering: false,
        scrollX: false,
        scrollCollapse: false,
        "ajax": {
            url: base_url + "/inter/fetch-all",
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data.payment_received == "0") {
                        return `<span class="badge bg-primary"><i class="bx bx-check-shield"></i> Pending</span>`;
                    }

                    if (data.payment_received == "1") {
                        return `<span class="badge bg-success"><i class="bx bx-check"></i> Received</span>`;
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
        $('#studentDetailsDatatable_wrapper .dt-buttons').append(
            `<a href="{{ url('inter/add') }}" class="btn btn-danger btn-sm"><i class="bx bx-plus"></i> Add New Student</a>`
        );
    })

    // =============================================
    /**
     * Payment Done Datatable
     */
    var studentPaymentDoneDatatable = $('#studentPaymentDoneDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ordering: false,
        scrollX: false,
        scrollCollapse: false,
        "ajax": {
            url: base_url + "/inter/fetch-all",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.type = 'PAYMENT_DONE';
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data.payment_received == "0") {
                        return `<span class="badge bg-primary"><i class="bx bx-check-shield"></i> Pending</span>`;
                    }

                    if (data.payment_received == "1") {
                        return `<span class="badge bg-success"><i class="bx bx-check"></i> Received</span>`;
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

    // =============================================
    /**
     * Total Enrolled Datatable
     */
    var studentTotalEnrolledDatatable = $('#studentTotalEnrolledDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ordering: false,
        scrollX: false,
        scrollCollapse: false,
        "ajax": {
            url: base_url + "/inter/fetch-all",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.type = 'TOTAL_ENROLLED';
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (data.payment_received == "0") {
                        return `<span class="badge bg-primary"><i class="bx bx-check-shield"></i> Pending</span>`;
                    }

                    if (data.payment_received == "1") {
                        return `<span class="badge bg-success"><i class="bx bx-check"></i> Received</span>`;
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
                data: 'roll_number',
                name: 'roll_number',
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
            "targets": [0, 1, 2, 3, 4, 5],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-12 col-md-4'B><'col-12 col-md-4'l><'col-12 col-md-4'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        buttons: []
    });

    $(document).on('click', '.deleteUserBtn', function() {
        var id = $(this).attr('id');
        if (id) {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#555',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.value) {
                    $.ajax({
                        url: base_url + '/inter/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                userDataTable.ajax.reload();
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


    // ---------------------------------------------
    // Add Payment
    $(document).on('click', '.btnAddPayment', function() {
        var id = $(this).attr('id');

        if (id) {
            // Get selected row data
            var selectedRowData = studentDetailsDatatable.row($(this).closest('tr')).data();

            if (selectedRowData) {
                // Assuming 'name' is the property containing the student's name in your data
                var studentName = selectedRowData.name;
                var studentApplicationNo = selectedRowData.application_no;

                // Set the value in the form field
                $('#student_name').val(studentName)
                $('#student_application_no').val(studentApplicationNo);

                $('#operation_type').val('ADD');
                $('#addPaymentModalLabel').html('<i class="bx bx-plus"></i> Add Payment');
                $('#btnPaymentForm').html('<i class="bx bx-paper-plane"></i> Save Details');

                $('#addPaymentModal').modal('show')
            } else {
                // No row selected, handle accordingly (e.g., show an alert)
                toastr.error('Something went wrong, please try again or contact support team.')
            }
        }
    });
</script>

@yield('stu-fees-modal-script')

@endsection