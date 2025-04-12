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
                            <select class="form-control" name="filter_institute" id="filter_institute">
                                <option value="">All</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="assignmentsDatatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    <th>Status</th>
                                    <th>Academic Year</th>
                                    <th>Admission Session</th>
                                    <th>Class Name</th>
                                    <th>Subject Name</th>
                                    <th>Title</th>
                                    <th>Document</th>
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
                    <strong class="form_title"> Add Assignments</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addAssignmentsForm" id="addAssignmentsForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="assignments_id" id="assignments_id">

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
                            <label class="form-label" for="institute_id">Institute <span class="text-danger">*</span></label>
                            <select class="form-select form-control" name="institute_id" id="institute_id">
                                <option value="">Select</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="admission_session_id" class="form-label">Admission Session<span class="text-danger">*</span> </label>
                            <select name="admission_session_id" class=" form-select form-control" id="admission_session_id" required>
                                <option value="">--Select--</option>
                                @foreach($admission_sessions as $admission_session)
                                <option value="{{$admission_session->id}}">{{$admission_session->session_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="class_id" class="form-label">Class Name<span class="text-danger">*</span>
                            </label>
                            <select name="class_id" class=" form-select form-control" id="class_id" required>
                                <option value="">--Select--</option>
                                @foreach($classes as $class)
                                <option value="{{$class->id}}">{{$class->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Subject Name<span class="text-danger">*</span>
                            </label>
                            <select name="subject_id" class=" form-select form-control" id="subject_id" required>
                                <option value="">Select</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="title">Title <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="document">Upload Document <strong class="text-danger">*</strong></label>
                            <input type="file" class="form-control" name="document" id="document" accept=".pdf">
                            <small class="text-danger">
                                Max Size 10MB, Only .pdf files allowed
                            </small>
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
        document.getElementById("addAssignmentsForm").reset();

        $('#class_id option').prop('selected', false);
        $('#subject_id').html("<option value=''>--Select--</option>");
        $('#status option').prop('selected', false);
        $('#academic_year_id option').prop('selected', false);
        $('#admission_session_id option').prop('selected', false);
        $('#subject_id').val(null).trigger('change');
        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Study Material');
        $("#addAssignmentsForm").validate().resetForm();
        $("#addAssignmentsForm").trigger('reset');
    }



    var assignmentsDatatable = $('#assignmentsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ordering: false,
        "ajax": {
            url: base_url + "/ajax/get/all-assignments-setup",
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
                data: 'admission_session',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'class_name',
                name: 'cls.name',
                className: "text-center"
            },
            {
                data: "subject_name",
                name: 's.name',
                className: "text-center",
            },
            {
                data: 'title',
                name: 'title',
                className: "text-center"
            },
            {
                data: 'document_button',
                name: 'document',
                className: "text-center"
            },


        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        assignmentsDatatable.ajax.reload();
    })

    $("#addAssignmentsForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            academic_year_id: {
                required: true
            },
            institute_id: {
                required: true
            },
            class_id: {
                required: true
            },
            admission_session_id: {
                required: true
            },
            subject_id: {
                required: true
            },
            title: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addAssignmentsForm'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/assignments-setup/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/assignments-setup/store';
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
                        assignmentsDatatable.ajax.reload();
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
    $(document).on('click', '.editAssignmentsBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/assignments-details',
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
                    $('#assignments_id').val(btoa(data.id));
                    $('#academic_year_id option[value="' + data.academic_year_id + '"]').prop('selected', true);
                    $('#institute_id option[value="' + data.institute_id + '"]').prop('selected', true);
                    $('#class_id option[value="' + data.class_id + '"]').prop('selected', true);
                    $('#admission_session_id option[value="' + data.admission_session_id + '"]').prop('selected', true);
                    
                    getSubjectsListUsingClassId(data.class_id, 'subject_id', data.subject_id);
                    $('#title').val(data.title);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                    $('.form_title').html('Edit Study Material');
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

    $(document).on('click', '.deleteAssignmentsBtn', function() {
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
                        url: base_url + '/ajax/assignments-setup/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                assignmentsDatatable.ajax.reload();
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


    // ============================================
    /**
     * On change in course, get the course subjects and non language subjects
     */
    $(document).on('change', '#institute_id', function() {
        let institute_id = $(this).val();

        if (institute_id) {
            getCoursesListUsingInstituteId(institute_id, 'course', '');
            // Get the admission sessions
            getAdmissionSesionsData()
        } else {
            setDefaultSelect('course');
        }
    })

    // ============================================
    /**
     * On change in course, get the course subjects and non language subjects
     */
    $(document).on('change', '#course', function() {
        let course = $('#course').val();
        let subject_type = $('#subject_type').val();


        if (course) {
            // Get the admission sessions
            getAdmissionSesionsData()

            if (subject_type) {
                getSubjectsListUsingCourseSubType(course, subject_type, 'subject_id', '');
            }
        }
    })

    // ============================================
    /**
     * On change in subject type, get the course subjects and non language subjects
     */
    $(document).on('change', '#subject_type', function() {
        let course = $('#course').val();
        let subject_type = $('#subject_type').val();

        if (course && subject_type) {
            getSubjectsListUsingCourseSubType(course, subject_type, 'subject_id', '');
        }
    })

    // ============================================
    /**
     * On change academic year
     */
    $(document).on('change', '#academic_year', function() {
        // Get the admission sessions
        getAdmissionSesionsData()
    });


    $(document).on('change', '#class_id', function() {
        let class_id = $(this).val();
        $('#subject_id').html("<option value=''>--Select--</option>").select2();
        if (class_id) {
            getSubjectsListUsingClassId(class_id, 'subject_id', '');
        }
    })

    function getSubjectsListUsingClassId(class_id, elementId, selectedId = '') {
        $('#' + elementId).html("<option value=''>--Select--</option>")
        $.ajax({
            url: base_url + '/ajax/get/subjects',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                class_id: btoa(class_id)
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    let courseSubjects = response.data;
                    if (courseSubjects.length > 0) {
                        let language_subject = '';
                        courseSubjects.forEach((subject) => {
                            if (selectedId == subject.subject_id) {
                                language_subject += `<option value="${subject.subject_id}" selected>${subject.subject_name}</option>`;
                            } else {
                                language_subject += `<option value="${subject.subject_id}">${subject.subject_name}</option>`;
                            }
                        })
                        $('#' + elementId).append(language_subject);
                        $('#' + elementId).select2();
                    }
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
</script>
@endsection