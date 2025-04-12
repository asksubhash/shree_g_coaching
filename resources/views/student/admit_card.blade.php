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
            @if(isset($studentEnrollment) && !empty($studentEnrollment))
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold">
                        <span class="text-success"> <i class="bx bx-book"></i> Admit Card issued for Exam</span> <strong>{{ $exam->exam_name }}</strong>
                    </h6>
                </div>
            </div>

            <div class="card" style="border-radius: 30px 30px 0px 0px;">
                <div class="mb-4 admit-card-header">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{ asset('website_assets/images/site-logo-white.png') }}" alt="Image" class="admit-card-header-img" />
                            <h4 class="mt-3 mb-0 text-uppercase text-white fw-bold">Admit Card</h4>
                            <h4 class="mt-0 mb-0 text-uppercase text-white fw-bold">({{ $exam->academic_year }})</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="admit-card">
                        <div class="BoxD border- padding mar-bot">
                            <div class="row">
                                <div class="col-sm-10">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="w-25"><b>ROLL NO : </b></td>
                                                <td class="w-25">{{ $user->roll_number }}</td>
                                                <td class="w-25"><b>D.O.B. : </b></td>
                                                <td class="w-25">{{ ($user->dob)?date('d-m-Y', strtotime($user->dob)):'' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Student Name: </b></td>
                                                <td>{{ $user->name }}</td>
                                                <td class="w-25"><b>Gender : </b></td>
                                                <td>{{ $user->gender }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Father Name: </b></td>
                                                <td>{{ $user->father_name }}</td>
                                                <td><b>Mother Name: </b></td>
                                                <td>{{ $user->mother_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Address: </b></td>
                                                <td>
                                                    {{ $user->address }}, {{ $user->state_name }} - {{ $user->pincode }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Exam Centre: </b></td>
                                                <td>
                                                    {{ $exam->exam_centre }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Exam District: </b></td>
                                                <td>
                                                    {{ $exam->exam_district }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-2 txt-center">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row txt-center">
                                                    <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="w-100" />
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="BoxF border- padding mar-bot txt-center">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="12%">S.No.</th>
                                                <th>Exam Code</th>
                                                <th width="22%">Subject/Paper</th>
                                                <th>Exam Date</th>
                                                <th>Exam Time</th>
                                                <th width="12%">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $count = 1;
                                            @endphp

                                            @foreach ($studentSubjects as $subject)
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
                                            </tr>
                                            @endforeach

                                            @foreach ($nlStudentSubjects as $subject)
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
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ url('student/admit-card/download') }}" class="btn btn-custom" target="_BLANK">
                            <i class="fa fa-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold">
                        <span class="text-danger"><i class="bx bx-trash"></i> Admit Card is not available yet. Please come after sometime.</span>
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