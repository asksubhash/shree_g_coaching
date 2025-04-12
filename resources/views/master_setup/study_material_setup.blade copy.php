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

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="studyMatDatatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    <th>Status</th>
                                    <th>Institute</th>
                                    <th>Course</th>
                                    <th>Subject Type</th>
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
                    <strong class="form_title"> Add Study Material</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addStudyMaterialForm" id="addStudyMaterialForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="study_material_id" id="study_material_id">

                        <div class="mb-3">
                            <label class="form-label" for="institute_id">Institute <span class="text-danger">*</span></label>
                            <select class="form-select form-control" name="institute_id" id="institute_id">
                                <option value="">Select</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label">Course<span class="text-danger">*</span>
                            </label>
                            <select name="course" class="form-select form-control" id="course" required>
                                <option value="">Select</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subject_type" class="form-label">Subject Type<span class="text-danger">*</span>
                            </label>
                            <select name="subject_type" class=" form-select form-control" id="subject_type" required>
                                <option value="">Select</option>
                                <option value="LANGUAGE">Language</option>
                                <option value="NON_LANGUAGE">Non-Language</option>
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
        document.getElementById("addStudyMaterialForm").reset();

        $('#course option').prop('selected', false);
        $('#subject_type option').prop('selected', false);
        $('#status option').prop('selected', false);
        $('#subject_id').val(null).trigger('change');


        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Study Material');
        $("#addStudyMaterialForm").validate().resetForm();
        $("#addStudyMaterialForm").trigger('reset');
    }

    function getCoursesListUsingInstituteId(instituteId, elementId, selectedId = '') {
        $.ajax({
            url: base_url + '/ajax/course/get-list-using-institute-id',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                institute_id: instituteId
            },
            success: function(response) {
                if (response.status == true) {

                    let courses = response.data.courses;

                    // StudyMaterial Language Subjects
                    if (courses.length > 0) {
                        let courseOptions = `<option value="">Select</option>`;
                        courses.forEach((course) => {
                            if (selectedId && course.id == selectedId) {
                                courseOptions += `<option value="${course.id}" selected>${course.course_name} (${course.course_code})</option>`;
                            } else {
                                courseOptions += `<option value="${course.id}">${course.course_name} (${course.course_code})</option>`;
                            }
                        })

                        $('#' + elementId).html(courseOptions);
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

    function getSubjectsListUsingCourseSubType(courseId, subjectType, elementId, selectedId = '') {
        $.ajax({
            url: base_url + '/ajax/course/get/subjects-and-nl-subjects',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                course_id: courseId
            },
            success: function(response) {

                if (response.status == true) {

                    let data = response.data;
                    let courseSubjects = data.courseSubjects;
                    let courseNLSubjects = data.courseNLSubjects;

                    if (subjectType == 'LANGUAGE') {
                        // Course Language Subjects
                        if (courseSubjects.length > 0) {
                            let language_subject = '';
                            courseSubjects.forEach((subject) => {
                                if (selectedId == subject.id) {
                                    language_subject += `<option value="${subject.id}" selected>${subject.name}</option>`;
                                } else {
                                    language_subject += `<option value="${subject.id}">${subject.name}</option>`;
                                }
                            })

                            $('#' + elementId).html(language_subject);
                            $('#' + elementId).select2();
                        }
                    }

                    if (subjectType == 'NON_LANGUAGE') {
                        // Course Non Language Subjects
                        if (courseNLSubjects.length > 0) {
                            let non_language_subject = '';
                            courseNLSubjects.forEach((subject) => {
                                if (selectedId == subject.id) {
                                    non_language_subject += `<option value="${subject.id}" selected>${subject.name}</option>`;
                                } else {
                                    non_language_subject += `<option value="${subject.id}">${subject.name}</option>`;
                                }

                            })
                            $('#' + elementId).html(non_language_subject);
                            $('#' + elementId).select2();
                        }
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

    var studyMatDatatable = $('#studyMatDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ordering: false,
        "ajax": {
            url: base_url + "/ajax/get/all-study-material-setup",
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
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return (data.institute_name) ? data.institute_name : '';
                }
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.course_name + ' <br /> (' + data.course_code + ')'
                }
            },
            {
                data: 'subject_type',
                name: 'subject_type',
                className: "text-center"
            },
            {
                data: null,
                name: 'subject_id',
                className: "text-center",
                render: function(data, type, row, meta) {
                    let subjectName = '';
                    if (data.subject_type == 'LANGUAGE') {
                        subjectName = data.language_subject;
                    }
                    if (data.subject_type == 'NON_LANGUAGE') {
                        subjectName = data.non_language_subject;
                    }
                    return subjectName;
                }
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
        studyMatDatatable.ajax.reload();
    })

    $("#addStudyMaterialForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            institute_id: {
                required: true
            },
            course: {
                required: true
            },
            subject_type: {
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
            var formData = new FormData(document.getElementById('addStudyMaterialForm'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/study-material-setup/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/study-material-setup/store';
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
                        studyMatDatatable.ajax.reload();
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
    $(document).on('click', '.editStudyMaterialBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/study-material-details',
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

                    $('#study_material_id').val(btoa(data.id));

                    $('#institute_id option[value="' + data.institute_id + '"]').prop('selected', true);

                    getCoursesListUsingInstituteId(data.institute_id, 'course', data.course_id);

                    $('#subject_type option[value="' + data.subject_type + '"]').prop('selected', true);
                    getSubjectsListUsingCourseSubType(data.course_id, data.subject_type, 'subject_id', data.subject_id);

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

    $(document).on('click', '.deleteStudyMaterialBtn', function() {
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
                        url: base_url + '/ajax/study-material-setup/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                studyMatDatatable.ajax.reload();
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

        if (course && subject_type) {
            getSubjectsListUsingCourseSubType(course, subject_type, 'subject_id', '');
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
</script>
@endsection