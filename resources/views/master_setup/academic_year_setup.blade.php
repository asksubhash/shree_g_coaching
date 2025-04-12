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

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="academic_yearsDatatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    <th>Status</th>
                                    <th>Academic Year</th>
                                    <th>Current Year</th>
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
                    <strong class="form_title"> Add Academic Years</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addAcademicYearsForm" id="addAcademicYearsForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="academic_years_id" id="academic_years_id">

                        <div class="mb-3">
                            <label class="form-label" for="academic_year">Academic Year <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="academic_year" id="academic_year">
                            <small class="text-danger">Ex: 2024-2025</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="end_date">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="active_status">Current Year <strong class="text-danger">*</strong></label>
                            <select name="active_status" id="active_status" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach (Config::get('constants.current_year_status') as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
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
                            <button type="reset" class="btn btn-default" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
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
    function formReset() {
        document.getElementById("addAcademicYearsForm").reset();

        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add AcademicYear');
        $("#addAcademicYearsForm").validate().resetForm();
        $("#addAcademicYearsForm").trigger('reset');
    }

    function getAdmissionSesionsData() {
        let course_id = $('#course').val();
        let academic_year_id = $('#academic_year_id').val();
        let institute_id = $('#institute_id').val();

        if (academic_year_id != '' && institute_id != '' && course_id != '') {
            fetchAndLoadAdmissionSesionsData(course_id, academic_year_id, institute_id, 'admission_session_id', '');
        } else {
            $('#admission_session_id').html('<option value="">Selects</option>');
        }
    }

    var academic_yearsDatatable = $('#academic_yearsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ordering: false,
        "ajax": {
            url: base_url + "/ajax/get/all-academic-year-setup",
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
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },

            {
                data: 'academic_year',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'active_status_desc',
                name: 'id',
                className: "text-center"
            },
        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        academic_yearsDatatable.ajax.reload();
    })

    $.validator.addMethod("greaterThan", function(value, element, param) {
        var startDate = $(param).val();
        return !startDate || !value || new Date(value) > new Date(startDate);
    }, "End date must be after the start date.");
    $("#addAcademicYearsForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            academic_year_id: {
                required: true
            },
            academic_year: {
                required: true
            },
            active_status: {
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
            var formData = new FormData(document.getElementById('addAcademicYearsForm'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/academic-year-setup/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/academic-year-setup/store';
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
                        academic_yearsDatatable.ajax.reload();
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
    $(document).on('click', '.editAcademicYearsBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/academic-year-details',
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
                    $('#academic_years_id').val(btoa(data.id));
                    $('#academic_year').val(data.academic_year);
                    $('#start_date').val(data.start_date);
                    $('#end_date').val(data.end_date);
                    $('#active_status option[value="' + data.active_status + '"]').prop('selected', true);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                    $('.form_title').html('Edit AcademicYear');
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

    $(document).on('click', '.deleteAcademicYearsBtn', function() {
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
                        url: base_url + '/ajax/academic-year-setup/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                academic_yearsDatatable.ajax.reload();
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
</script>
@endsection