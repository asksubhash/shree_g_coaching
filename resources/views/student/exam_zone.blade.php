@extends('layouts.master_layout')

@section('content')
<div class="page-content">
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
    <div class="row mt-3">
        <div class="col-12">
            @if(isset($studentEnrollment) && !empty($studentEnrollment) && $exam->is_published == 1)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-uppercase fw-bold text-danger">Exam Zone ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-danger">
                        Important Note:
                    </h6>
                    <ul>
                        <li>
                            Once you click on the start exam button, the exam will start.
                        </li>
                        <li>
                            Do not leave the window until exam completed.
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="8%">S.No.</th>
                                        <th>Exam Code</th>
                                        <th width="22%">Subject/Paper</th>
                                        <th>Exam Date</th>
                                        <th>Exam Time</th>
                                        <th width="12%">Duration</th>
                                        <th width="17%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $count = 1;
                                    @endphp

                                    @foreach ($studentSubjects as $subject)

                                    @php
                                    $isExamActive = AppHelper::checkExamActive($subject);
                                    @endphp

                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ (isset($subject->exam_date) && !empty($subject->exam_date))? date('d M, Y', strtotime($subject->exam_date)):'' }}</td>
                                        <td>{{ $subject->exam_time }}</td>
                                        <td>
                                            @if($subject->exam_duration)
                                            {{ $subject->exam_duration }} Hours
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isExamActive)
                                            <a href="{{ url('student/exam/start?exam_id='.base64_encode($exam->id).'&subject_id='.base64_encode($subject->id).'&subject_type='.base64_encode('LANGUAGE')) }}" class="btn btn-success btn-sm">
                                                <i class="bx bx-paper-plane"></i> Start Exam
                                            </a>
                                            @else
                                            <button type="button" class="btn btn-secondary btn-sm" disabled><i class="bx bx-x"></i> Exam Not Available</button>
                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach

                                    @foreach ($nlStudentSubjects as $subject)
                                    @php
                                    $isExamActive = AppHelper::checkExamActive($subject);
                                    @endphp

                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ (isset($subject->exam_date) && !empty($subject->exam_date))? date('d M, Y', strtotime($subject->exam_date)):'' }}</td>
                                        <td>{{ $subject->exam_time }}</td>
                                        <td>
                                            @if($subject->exam_duration)
                                            {{ $subject->exam_duration }} Hours
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isExamActive)
                                            <a href="{{ url('student/exam/start?exam_id='.base64_encode($exam->id).'&subject_id='.base64_encode($subject->id).'&subject_type='.base64_encode('NON_LANGUAGE')) }}" class="btn btn-success btn-sm">
                                                <i class="bx bx-paper-plane"></i> Start Exam
                                            </a>
                                            @else
                                            <button type="button" class="btn btn-secondary btn-sm" disabled><i class="bx bx-x"></i> Exam Not Available</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold">
                        <span class="text-danger"><i class="bx bx-trash"></i> Exam are not available right now, please come after sometime.</span>
                    </h6>
                </div>
            </div>
            @endif

        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    @endsection

    @section('pages-scripts')
    <script>
    </script>
    @endsection