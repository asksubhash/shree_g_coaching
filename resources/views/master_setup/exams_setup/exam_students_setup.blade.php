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
            <!-- Language Subjects -->
            <div class="card p-3">
                <h6 class="card-title text-danger mb-3">
                    Exam Students Enrollment
                </h6>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Exam Name</label>
                            <input type="text" name="exam_name" id="exam_name" class="form-control" value="{{ $exam->exam_name }}" disabled />
                            <input type="hidden" name="exam_id" id="exam_id" value="{{ $exam->id }}" />
                        </div>

                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Academic Year</label>
                            <select class="form-control" name="academic_year_id" id="academic_year_id">
                                <option value="">---Select---</option>
                                @foreach($academic_years as $ay)
                                @if($ay->id == $exam->academic_year_id)
                                <option value="{{$ay->id}}" selected>{{$ay->academic_year}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label for="admission_session" class="form-label">Admission Session</label>
                            <input type="text" name="admission_session" id="admission_session" class="form-control" value="{{ $exam->admission_session_id }}" readonly>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Institute</label>
                            <select class="form-control select2" name="institute_id" id="institute_id">
                                <option value="">---Select---</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name .' ('.$institute->institute_code.')'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Course</label>
                            <select name="course" class="form-select form-control" id="course" required>
                                <option value="">Select</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3 text-center">
                            <button type="button" class="btn btn-primary" id="btn-get-students">
                                <i class="bx bx-paper-plane"></i> Get Students
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="examsStudentsForEnrollmentDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th><input type="checkbox" value="1" id="checkbox_select_all" /> Select All </th>
                                <th>Roll Number</th>
                                <th class="text-left">Student Name</th>
                                <th>Enrolled Status</th>
                            </tr>
                        </thead>
                    </table>
                    <hr>
                    <div class="text-right">
                        <p class="text-danger">
                            By clicking this button, all the checked marked students will be enrolled for this <strong>{{ $exam->exam_name }}</strong> exam. And admit card of students will be issued.
                        </p>
                        <button class="btn btn-success" type="button" id="btn_enroll_students_for_exam">
                            <i class="bx bx-paper-plane"></i> Enroll Students
                        </button>
                    </div>
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
    $(document).ready(function() {
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

        /** Datatable */
        var examsStudentsForEnrollmentDatatable = $('#examsStudentsForEnrollmentDatatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            scrollX: true,
            paging: false,
            scrollCollapse: true,
            ordering: false,
            "ajax": {
                url: base_url + "/exams-setup/all-students-for-enrollment",
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.exam_id = $('#exam_id').val();
                    d.academic_year_id = $('#academic_year_id').val();
                    d.admission_session = $('#admission_session').val();
                    d.institute_id = $('#institute_id').val();
                    d.course = $('#course').val();
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
                    data: 'checkbox',
                    name: 'id',
                    className: "text-center"
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
                    data: 'enrolled_status_desc',
                    name: 'name',
                    className: "text-left"
                },
            ]
        });

        /** On click button */
        $("#btn-get-students").on('click', function() {
            let exam_id = $('#exam_id').val();
            let academic_year_id = $('#academic_year_id').val();
            let admission_session = $('#admission_session').val();
            let institute_id = $('#institute_id').val();
            let course = $('#course').val();

            if (exam_id && academic_year_id && admission_session && institute_id && course) {
                examsStudentsForEnrollmentDatatable.ajax.reload();
            } else {
                toastr.error("Please select all fields")
            }
        })

        /** On Click select all checkbox */
        $('#checkbox_select_all').on('click', function() {
            if ($(this).is(':checked')) {
                $('.student_enroll_checkbox').prop('checked', true);
            } else {
                $('.student_enroll_checkbox').prop('checked', false);
            }
        })

        /** Enroll students */
        $("#btn_enroll_students_for_exam").on('click', function() {
            let enrolledStudents = [];
            let exam_id = $('#exam_id').val();

            $('.student_enroll_checkbox').each(function() {
                let esObj = {
                    studentId: $(this).val()
                };
                if ($(this).is(':checked')) {
                    esObj.isChecked = 1;
                } else {
                    esObj.isChecked = 0;
                }
                enrolledStudents.push(esObj);
            });

            if (enrolledStudents.length > 0) {
                $.ajax({
                    url: base_url + '/exams-setup/enroll-students-for-exam',
                    type: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        exam_id: exam_id,
                        enrolled_students: JSON.stringify(enrolledStudents)
                    },
                    success: function(response) {
                        if (response.status == true) {
                            toastr.success(response.message);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                html: response.message
                            }).then(() => {
                                window.location.reload();
                            })
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
            } else {
                toastr.error("Please select student for enrollment");
            }
        })

        /**
         * Form submission
         */
        /** On submit the  form */
        $("#exam_subject_timings_form").validate({
            errorClass: 'validation-error w-100',
            rules: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('exam_subject_timings_form'));
                // Check the operation type
                var url;
                var operationType = $('#estf_operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/exam-subject-timing/update';
                } else if (operationType == 'ADD') {
                    url = base_url + '/exam-subject-timing/store';
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
                            toastr.success(response.message);
                            window.location.reload();
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
    })
</script>
@endsection