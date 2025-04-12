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
                                    <div class="shadow bg-light d-flex ps-3 rounded">

                                        <div class="mt-3 ps-1 pe-3 align-self-center">
                                            <input type="radio" name="mcq_answer_{{ $question->id }}" id="mcq_answer_{{ $question->id }}" value="{{ $i }}" data-question-id="{{ $question->id }}" class="custom-2-radio mcq-question-radio" @if($selectedAnswer==$i) checked @endif disabled />
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
                                    <button type="button" class="btn btn-danger">
                                        Answer not uploaded
                                    </button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


@endsection

@section('pages-scripts')
<script>
</script>
@endsection