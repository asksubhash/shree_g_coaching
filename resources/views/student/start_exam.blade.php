@extends('layouts.master_layout')

@section('content')
<div class="page-content start-exam-page">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center ">
        <div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to(strtolower(auth::user()->role_code) . '/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ url()->previous() }}" class="btn btn-secondary"> <i class='bx bx-arrow-back'></i></a>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row mt-3 pb-5">
        @if(isset($studentExamDetails->exam_status) && $studentExamDetails->exam_status == 'PENDING')

        @if($questionsCount > 0)
        <div class="col-12">

            <!-- Questions Wrapper -->
            <div>
                @php
                $questionCount = 1;
                @endphp
                <!-- MCQ Questions -->
                @if(count($examMcqQuestions) > 0)

                @php
                // Create a mapping of answers keyed by question_id
                $mcqAnswerMap = collect($mcqAnswers)->keyBy('question_id');
                @endphp

                @foreach ($examMcqQuestions as $key => $question)
                <div class="card">
                    <div class="py-2 px-3 rounded bg-dark mb-3 text-white">
                        <h5 class="mb-0 fw-bold text-white">Question {{ $questionCount++ }})</h5>
                    </div>
                    <div class="card-body">
                        @if($question->question_text)
                        <div>
                            {!! $question->question_text !!}
                        </div>
                        @endif
                        @if($question->question_image)
                        <div class="mt-3">
                            <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['QUESTION_IMAGE_VIEW_PATH'].'/' . $question->question_image) }}" alt="Image" loading="lazy" class="img-thumbnail question_image">
                        </div>
                        @endif

                        <hr class="border-2 border-danger">

                        <div class="row mt-3">
                            @for($i=1; $i < 5; $i++)

                                @php
                                // Get the answer for the current question
                                $answerRecord=$mcqAnswerMap->get($question->id);
                                $selectedAnswer = $answerRecord ? $answerRecord['answer'] : null;
                                @endphp

                                <!-- Option {{ $i }} -->
                                <div class="col-md-6 col-sm-12 col-12 mb-3">
                                    <div class="shadow bg-gray d-flex ps-3 rounded">

                                        <div class="mt-3 ps-1 pe-3 align-self-center">
                                            <input type="radio" name="mcq_answer_{{ $question->id }}" id="mcq_answer_{{ $question->id }}" value="{{ $i }}" data-question-id="{{ $question->id }}" class="custom-radio mcq-question-radio" @if($selectedAnswer==$i) checked @endif />
                                        </div>

                                        <div class="cursor-pointer flex-fill pb-3 pt-3">

                                            @if($question["option_".$i])
                                            <div class="pe-3">
                                                {{ $question["option_".$i] }}
                                            </div>
                                            @endif

                                            @if ($question['option_'.$i.'_image'])
                                            <div class="mt-3 pe-3">
                                                <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'].'/' . $question['option_'.$i.'_image']) }}" alt="Image" loading="lazy" class="img-thumbnail question_option_image">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endfor
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                <!-- TEXT Questions -->
                @if(count($examTextQuestions) > 0)
                <h5 class="fw-bold py-3 px-3 rounded text-center bg-danger text-white text-uppercase mb-3 shadow-lg">
                    Long Questions
                </h5>

                @php
                // Create a mapping of answers keyed by question_id
                $textAnswerMap = collect($textAnswers)->keyBy('question_id');
                @endphp

                @foreach ($examTextQuestions as $key => $question)
                <div class="card">
                    <div class="py-2 px-3 rounded bg-dark mb-3 text-white">
                        Question {{ $questionCount++ }})
                    </div>
                    <div class="card-body">
                        @if($question->question_text)
                        <div>
                            {!! $question->question_text !!}
                        </div>
                        @endif
                        @if($question->question_image)
                        <div class="mt-3">
                            <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['QUESTION_IMAGE_VIEW_PATH'].'/' . $question->question_image) }}" alt="Image" loading="lazy" class="img-thumbnail question_image">
                        </div>
                        @endif

                        <!-- Upload Answers -->
                        <div class="mt-3 shadow bg-gray p-3 rounded">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="d-flex w-100">
                                        <div class="flex-fill">
                                            <input type="file" name="answer_file" id="{{ 'answer_file_'.$question->id }}" class="form-control" accept=".pdf" />
                                            <small class="text-danger">Only PDF files are allowed. Max Size 10MB</small>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-custom btn-upload-answer" data-id="{{ $question->id }}">
                                                Upload Answer
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @php
                                // Get the answer for the current question
                                $answerRecord=$textAnswerMap->get($question->id);
                                $answerFile = $answerRecord ? $answerRecord['answer_document'] : null;
                                @endphp

                                <div class="col-md-6 col-sm-12 col-12 mb-3 ps-md-3" id="answer-uploaded-col-{{ $question->id }}">
                                    @if($answerFile)
                                    <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_QUESTION_ANSWER_VIEW_PATH'].'/' . $answerFile) }}" class="btn btn-success" target="_BLANK">
                                        <i class="bx bx-show"></i> View Upload Answer
                                    </a>
                                    @else
                                    <span class="badge bg-danger">
                                        Answer not uploaded yet.
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <!-- Final Submit Form -->
            <div class="exam-final-submit-col">
                <input type="hidden" id="exam_remaining_time" value="{{ AppHelper::examRemainingTime($examSubjectTimings) }}">

                <div class="d-flex w-100">
                    <div class="flex-fill">
                        <p class="mb-0 fw-bold">
                            Please note that your exam has not been submitted yet. Give all your answers and then final submit the exam. <br> To ensure your answers are recorded, kindly complete all questions and finalize your submission.
                        </p>
                    </div>
                    <div>
                        <!-- Countdown -->
                        <div class="examCountdown" id="examCountdown"></div>
                        <button type="button" class="btn btn-success btn-final-submit">
                            <i class="bx bx-paper-plane"></i> Final Submit Exam
                        </button>
                    </div>
                </div>
            </div>

        </div>
        @else
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-danger fw-bold text-start mb-3">
                        <i class="bx bx-times"></i> No questions are added in this exam, please contact administrator.
                    </h5>
                </div>
            </div>
        </div>
        @endif

        @else
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-success fw-bold text-start mb-3">
                        <i class="bx bx-check-square"></i> Exam is submitted for this subject.
                    </h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="25%">
                                Exam Submitted On
                            </th>
                            <td>
                                {{ $studentExamDetails->exam_submitted_on }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Exam Status
                            </th>
                            <td>
                                {{ $studentExamDetails->exam_status }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    @endsection

    @section('pages-scripts')
    <script>
        /** Save MCQ answer */
        $(".mcq-question-radio").on('click', function() {
            if ($(this).is(':checked')) {
                let optionSelected = parseInt($(this).val());
                let questionId = $(this).data('question-id');

                if ([1, 2, 3, 4].includes(optionSelected)) {
                    $.ajax({
                        url: base_url + '/student/exam/answer/mcq',
                        type: 'POST',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            optionSelected: optionSelected,
                            questionId: questionId
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
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
                    toastr.error('Invalid options provided');
                }
            } else {
                toastr.error('No options are checked');
            }
        })

        $(document).ready(function() {
            $('.btn-upload-answer').on('click', function() {
                var questionId = $(this).data('id');
                var fileInput = $('#answer_file_' + questionId);
                var fileData = fileInput.prop('files')[0];

                if (!fileData) {
                    toastr.error('Please select a file to upload.');
                    return;
                }

                // Check if the file type is PDF
                if (fileData.type !== 'application/pdf') {
                    toastr.error('Only PDF files are allowed. Please select a PDF file.');
                    return;
                }

                // Check if the file size exceeds 10MB (10 * 1024 * 1024 bytes)
                if (fileData.size > 10 * 1024 * 1024) {
                    toastr.error('File size exceeds 10MB. Please select a smaller file.');
                    return;
                }

                var formData = new FormData();
                formData.append('answerFile', fileData);
                formData.append('questionId', questionId);

                $.ajax({
                    url: '/student/exam/answer/text', // Replace with your actual upload URL
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token if needed
                    },
                    success: function(response) {
                        if (response.status == true) {
                            toastr.success(response.message);
                            if (response.answer_file) {
                                $('#answer-uploaded-col-' + questionId).html(`<a href="${response.answer_file}" class="btn btn-success" target="_BLANK">
                                        <i class="bx bx-show"></i> View Upload Answer
                                    </a>`);
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
                    error: function(xhr, status, error) {
                        toastr.error('Error uploading file: ' + error);
                        // Handle error as needed
                    }
                });
            });

            /** 
             * Final submit exam
             */
            $(document).on('click', '.btn-final-submit', function() {
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'You want to submit your exam?',
                    showCancelButton: true,
                    confirmButtonColor: '#15ca20',
                    cancelButtonColor: '#555',
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                }).then((result) => {

                    /* Read more about isConfirmed, isDenied below */
                    if (result.value) {
                        $.ajax({
                            url: base_url + '/student/exam/final-submit',
                            type: 'POST',
                            data: {
                                _token: $('meta[name=csrf-token]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Exam Submitted',
                                        html: response.message
                                    }).then(() => {
                                        window.location.reload();
                                    })
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

            });



            // Get the remaining time in seconds from the server
            let timeRemaining = $('#exam_remaining_time').val();

            // Function to update the countdown every second
            function startCountdown() {
                const countdownElement = document.getElementById('examCountdown');

                const interval = setInterval(() => {
                    // Calculate the hours, minutes, and seconds
                    let hours = Math.floor(timeRemaining / 3600);
                    let minutes = Math.floor((timeRemaining % 3600) / 60);
                    let seconds = timeRemaining % 60;

                    // Display the countdown
                    countdownElement.textContent =
                        'Time Remaining: ' +
                        hours.toString().padStart(2, '0') + ':' +
                        minutes.toString().padStart(2, '0') + ':' +
                        seconds.toString().padStart(2, '0');

                    // Decrement the remaining time
                    timeRemaining--;

                    // If the time runs out, stop the interval and redirect
                    if (timeRemaining < 0) {
                        clearInterval(interval);
                        window.location.href = '/student/exam-zone'; // Replace with your redirect URL
                    }
                }, 1000);
            }

            // Start the countdown
            startCountdown();


        });
    </script>
    @endsection