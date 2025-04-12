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
                    Language Subjects
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="examsSubjectsDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th width="12%">Action</th>
                                <th>Subject Name</th>
                                <th>Exam Date</th>
                                <th>Exam Time</th>
                                <th>Exam Duration</th>
                                <th>Questions Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($subjects) > 0)
                            @foreach($subjects as $key => $subject)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <a href="{{ url('exams-setup/subjects/question-setup?exam_id='.$exam->id.'&subject_id='.$subject->id.'&subject_type=LANGUAGE') }}" class="btn btn-primary btn-sm">
                                        Setup Questions <i class="bx bx-paper-plane"></i>
                                    </a>
                                    <button type="button" data-exam-id="{{ $exam->id }}" data-subject-id="{{ $subject->id }}" data-subject-type="LANGUAGE" class="btn btn-warning btn-sm btn-setup-exam-timings" data-subject-name="{{ $subject->name }} ({{ $subject->code }})">
                                        Setup Timings <i class="bx bx-paper-plane"></i>
                                    </button>
                                </td>
                                <td>{{ $subject->name }} ({{ $subject->code }})</td>
                                <td>{{ $subject->exam_date }}</td>
                                <td>{{ $subject->exam_time }}</td>
                                <td>{{ $subject->exam_duration }}</td>
                                <td>{{ $subject->questions_count }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Non-Language Subjects -->
            <div class="card p-3">
                <h6 class="card-title text-danger mb-3">
                    Non Language Subjects
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="examsNLSubjectsDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th width="12%">Action</th>
                                <th>Subject Name</th>
                                <th>Exam Date</th>
                                <th>Exam Time</th>
                                <th>Exam Duration</th>
                                <th>Questions Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($nlSubjects) > 0)
                            @foreach($nlSubjects as $key => $subject)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <a href="{{ url('exams-setup/subjects/question-setup?exam_id='.$exam->id.'&subject_id='.$subject->id.'&subject_type=NON_LANGUAGE') }}" class="btn btn-primary btn-sm">
                                        Setup Questions <i class="bx bx-paper-plane"></i>
                                    </a>
                                    <button type="button" data-exam-id="{{ $exam->id }}" data-subject-id="{{ $subject->id }}" data-subject-type="NON_LANGUAGE" class="btn btn-warning btn-sm btn-setup-exam-timings" data-subject-name="{{ $subject->name }} ({{ $subject->code }})">
                                        Setup Timings <i class="bx bx-paper-plane"></i>
                                    </button>
                                </td>
                                <td>{{ $subject->name }} ({{ $subject->code }})</td>
                                <td>{{ $subject->exam_date }}</td>
                                <td>{{ $subject->exam_time }}</td>
                                <td>{{ $subject->exam_duration }}</td>
                                <td>{{ $subject->questions_count }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- MODAL --}}
<div class="modal fade" id="examSubjectTimingModal" tabindex="-1" aria-labelledby="examSubjectTimingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="examSubjectTimingModalLabel">Exam Subject Timings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="javascript:void(0)" class="exam_subject_timings_form" id="exam_subject_timings_form">
                    @csrf
                    <input type="hidden" name="operation_type" id="estf_operation_type" value="ADD">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="">
                    <input type="hidden" name="exam_id" id="exam_id" value="">
                    <input type="hidden" name="subject_type" id="subject_type" value="">
                    <input type="hidden" name="subject_id" id="subject_id" value="">

                    <div class="mb-3">
                        <label class="form-label" for="subject_name">Subject Name</label>
                        <input type="text" class="form-control" name="subject_name" id="subject_name" disabled />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="exam_date">Exam Date</label>
                        <input type="date" class="form-control" name="exam_date" id="exam_date" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="exam_time">Exam Time</label>
                        <input type="time" class="form-control" name="exam_time" id="exam_time" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="exam_duration">Duration</label>
                        <select class="form-control" name="exam_duration" id="exam_duration">
                            <option value="">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3" selected>3</option>
                        </select>
                    </div>

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-paper-plane"></i> Submit</button>
                        <button type="reset" class="btn btn-default"><i class="bx bx-refresh"></i> Reset</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('pages-scripts')
<script>
    $(document).ready(function() {
        $('.btn-setup-exam-timings').on('click', function() {
            let examId = $(this).data('exam-id');
            let subjectId = $(this).data('subject-id');
            let subjectType = $(this).data('subject-type');
            let subjectName = $(this).data('subject-name');


            if (examId && subjectId && subjectType) {
                $('#exam_id').val(examId);
                $('#subject_id').val(subjectId);
                $('#subject_type').val(subjectType);
                $('#subject_name').val(subjectName);
                $('#examSubjectTimingModal').modal('show');
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