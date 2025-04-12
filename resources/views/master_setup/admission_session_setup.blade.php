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
        <div class="col-md-8 col-12">
            <div class="card p-3">

                @if(Auth::user()->role?->role_code === "ADMIN")
                <div class="mb-3 bg-light card border-0 card-body shadow-none">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <label class="form-label" for="filter_institute">Institute <strong class="text-danger">*</strong></label>
                            <select class="form-control select2" name="filter_institute" id="filter_institute">
                                <option value="">All</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name .' ('.$institute->institute_code.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="admSessionDatatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    @if(Auth::user()->role?->role_code === "ADMIN")
                                    <th>Institute</th>
                                    @endif
                                    <th>Session Name</th>
                                    <th>Session Start Date</th>
                                    <th>Session End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Admission Session</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addAdmSessionForm" id="addAdmSessionForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="admission_session_id" id="admission_session_id">

                        <div class="mb-3">
                            <label class="form-label" for="academic_year_id">Academic Year <strong class="text-danger">*</strong></label>
                            <select class="form-control" name="academic_year_id" id="academic_year_id">
                                <option value="">---Select---</option>
                                @foreach($academic_years as $ay)
                                <option value="{{$ay->id}}">{{$ay->academic_year}}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(Auth::user()->role?->role_code === "ADMIN")
                        <div class="mb-3">
                            <label class="form-label" for="institute_id">Institute <strong class="text-danger">*</strong></label>
                            <select class="form-control select2" name="institute_id" id="institute_id" required>
                                <option value="">---Select---</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name .' ('.$institute->institute_code.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="start_date">Session Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="end_date">Session End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="session_name">Session Name <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="session_name" id="session_name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="status">Status <strong class="text-danger">*</strong></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach (Config::get('constants.status') as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                            <button type="button" class="btn btn-default" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
                        </div>

                    </form>
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
    const currentRole = "{{Auth::user()->role?->role_code}}";
    let columns = [{
            data: null,
            name: 'index',
            className: "text-center",
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: 'action',
            className: "text-center",
            width: '12%'
        }
    ];

    if (currentRole === "ADMIN") {
        columns.push({
            data: 'institute_name',
            name: 'institute_name',
            className: "text-left",
            render: function(data, type, row, meta) {
                return data.institute_name ? data.institute_name : '';
            }
        });
    }

    columns.push({
        data: 'session_name',
        name: 'session_name',
        className: "text-center"
    },
    {
        data: 'start_date',
        name: 'start_date',
        className: "text-left"
    },
    {
        data: 'end_date',
        name: 'end_date',
        className: "text-left"
    },
    
    
    {
        data: 'status_desc',
        name: 'status_desc', // Fixed duplicate `id` issue
        className: "text-center"
    });

    var admSessionDatatable = $('#admSessionDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ajax: {
            url: base_url + "/ajax/get/all-admission-sessions-setup",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.filter_institute = $('#filter_institute').val();
            }
        },
        columns: columns,
        columnDefs: [{
            targets: "_all", // Fixed '_ALL' issue
            orderable: false,
            sorting: false
        }]
    });


    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        admSessionDatatable.ajax.reload();
    })

    $.validator.addMethod("greaterThan", function(value, element, param) {
        var startDate = $(param).val();
        return !startDate || !value || new Date(value) > new Date(startDate);
    }, "End date must be after the start date.");
    $("#addAdmSessionForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            academic_year_id: {
                required: true
            },
            institute_id: {
                required: true
            },
            course: {
                required: true
            },
            session_name: {
                required: true
            },
            status: {
                required: true
            },
            start_date: {
                date: true
            },
            end_date: {
                date: true,
                greaterThan: "#start_date"
            }
        },
        messages: {
            start_date: {
                date: "Please enter a valid date."
            },
            end_date: {
                date: "Please enter a valid date.",
                greaterThan: "End date must be after the start date."
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addAdmSessionForm'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/admission-sessions-setup/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/admission-sessions-setup/store';
            } else {
                return false;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        admSessionDatatable.ajax.reload();
                        toastr.success(response.message);
                        formReset();
                    } else if (response.status == 'validation_errors') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: response.message
                        })
                    } else if (response.status == false) {
                        toastr.error(response.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(error) {
                    toastr.error('Something went wrong. Please try again.')
                }
            });
        }
    });


    // Onclick edit button
    $(document).on('click', '.editCourseBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/admission-sessions-details',
            type: 'POST',
            data: {
                id: btoa(id),
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {

                if (response.status == true) {
                    let data = response.data;

                    // Set the form data
                    formReset();
                    $('#operation_type').val('EDIT');

                    $('#admission_session_id').val(btoa(data.id));
                    $('#academic_year_id option[value="' + data.academic_year_id + '"]').prop('selected', true);
                    $('#institute_id').val(data.institute_id).trigger('change');
                    $('#start_date').val(data.start_date);
                    $('#end_date').val(data.end_date);
                    $('#session_name').val(data.session_name);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                    $('.form_title').html('Edit Admission Session');
                    $('#formSubmitBtn').html('<i class="bx bx-edit"></i> Update');

                } else if (response.status == false) {
                    toastr.error(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(errors) {
                console.log(errors);
            }
        });
    });

    $(document).on('click', '.deleteCourseBtn', function() {
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
                        url: base_url + '/ajax/admission-sessions-setup/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                admSessionDatatable.ajax.reload();
                            } else if (response.status == false) {
                                toastr.error(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(errors) {
                            toastr.error(error)
                        }
                    });
                }

            });
        } else {
            toastr.error('Something went wrong. Please try again.');
        }

    });


    function formReset() {
        document.getElementById("addAdmSessionForm").reset();
        $('#subject').trigger('change');
        $('#institute_id').val(null).trigger('change');
        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Course');
        $("#addAdmSessionForm").validate().resetForm();
    }
</script>
@endsection