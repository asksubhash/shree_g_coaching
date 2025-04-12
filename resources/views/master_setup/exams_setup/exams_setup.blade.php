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
                    <table class="table table-bordered table-striped w-100 nowrap" id="examsDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th width="12%">Action</th>
                                <th>Status</th>
                                <th>Is Published</th>
                                <th>Academic Year</th>
                                <th>Admission Session</th>
                                <th>Exam Name</th>
                                <th>Start Date</th>
                                <th>Centre</th>
                                <th>District</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Exams</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addExamsForm" id="addExamsForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="exams_id" id="exams_id">

                        <div class="mb-3">
                            <label class="form-label" for="academic_year_id">Academic Year <strong class="text-danger">*</strong></label>
                            <select class="form-control" name="academic_year_id" id="academic_year_id">
                                <option value="">---Select---</option>
                                @foreach($academic_years as $ay)
                                <option value="{{$ay->id}}">{{$ay->academic_year}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="admission_session_id" class="form-label">Admission Session<span class="text-danger">*</span> </label>
                            <input type="text" name="admission_session_id" id="admission_session_id" class="form-control" />
                            <small class="text-danger">
                                Make sure to enter correct Admission Session
                            </small>
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="exam_name">Exam Name <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="exam_name" id="exam_name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="exam_start_date">Exam Start Date <strong class="text-danger">*</strong></label>
                            <input type="date" class="form-control" name="exam_start_date" id="exam_start_date">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="exam_centre">Exam Centre <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="exam_centre" id="exam_centre">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="exam_district">Exam District <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="exam_district" id="exam_district">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="is_published">Published Status <strong class="text-danger">*</strong></label>
                            <select name="is_published" id="is_published" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach (Config::get('constants.status') as $key => $status)
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
        document.getElementById("addExamsForm").reset();

        $('#course option').prop('selected', false);
        $('#subject_type option').prop('selected', false);
        $('#status option').prop('selected', false);
        $('#academic_year_id option').prop('selected', false);
        $('#admission_session_id option').prop('selected', false);
        $('#is_published option').prop('selected', false);

        $('#subject_id').val(null).trigger('change');

        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Exam');
        $("#addExamsForm").validate().resetForm();
        $("#addExamsForm").trigger('reset');
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

    var examsDatatable = $('#examsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ordering: false,
        "ajax": {
            url: base_url + "/ajax/get/all-exams-setup",
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
                data: 'is_published_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'academic_year',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'admission_session_id',
                name: 'admission_session_id',
                className: "text-center"
            },
            {
                data: 'exam_name',
                name: 'exam_name',
                className: "text-center"
            },
            {
                data: 'exam_start_date',
                name: 'exam_start_date',
                className: "text-center"
            },
            {
                data: 'exam_centre',
                name: 'exam_centre',
                className: "text-center"
            },
            {
                data: 'exam_district',
                name: 'exam_district',
                className: "text-center"
            }
        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        examsDatatable.ajax.reload();
    })

    $("#addExamsForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            academic_year_id: {
                required: true
            },
            admission_session_id: {
                required: true
            },
            exam_name: {
                required: true
            },
            exam_start_date: {
                required: true
            },
            exam_centre: {
                required: true
            },
            exam_district: {
                required: true
            },
            is_published: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addExamsForm'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/exams-setup/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/exams-setup/store';
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
                        examsDatatable.ajax.reload();
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
    $(document).on('click', '.editExamsBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/exams-details',
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

                    $('#exams_id').val(btoa(data.id));

                    $('#academic_year_id option[value="' + data.academic_year_id + '"]').prop('selected', true);
                    $('#admission_session_id').val(data.admission_session_id);

                    $('#exam_name').val(data.exam_name);
                    $('#exam_start_date').val(data.exam_start_date);
                    $('#exam_centre').val(data.exam_centre);
                    $('#exam_district').val(data.exam_district);


                    $('#is_published option[value="' + data.is_published + '"]').prop('selected', true);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);

                    $('.form_title').html('Edit Exam');
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

    $(document).on('click', '.deleteExamsBtn', function() {
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
                        url: base_url + '/ajax/exams-setup/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                examsDatatable.ajax.reload();
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