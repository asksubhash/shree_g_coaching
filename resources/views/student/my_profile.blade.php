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
            <a href="{{ url()->previous() }}" class="btn btn-primary"> <i class='bx bx-arrow-back'></i></a>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card border-danger border-top border-2 border-0">
                <div class="card-body">

                    <table class="w-100 table table-bordered">
                        <tbody>
                            <tr>
                                <th>
                                    Roll Number
                                </th>
                                <th class="w-50">
                                    {{ $studentDetails->roll_number }}
                                </th>
                                <td rowspan="7" style="width: 20%;">
                                    <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $studentDetails->photo) }}" alt="Image" class="w-100" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Application No.
                                </td>
                                <td>
                                    {{ $studentDetails->application_no }}
                                </td>
                            </tr>

                            @if ($studentDetails->edu_type != 'GRADUATION')
                            <tr>
                                <td>
                                    Medium of Instruction
                                </td>
                                <td>
                                    {{ $studentDetails->medium_off_inst }}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    Academic Year
                                </td>
                                <td>
                                    {{ $studentDetails->st_academic_year }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Institute
                                </td>
                                <td>
                                    {{ $studentDetails->institute_name }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Course
                                </td>
                                <td>
                                    {{ $studentDetails->course_name }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Admission Session
                                </td>
                                <td>
                                    {{ $studentDetails->st_admission_session }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Language Subjects
                                </td>
                                <td>
                                    @if (count($courseSubjects) > 0)
                                    @foreach ($courseSubjects as $key => $ssm)
                                    <span class="me-1">{{ $ssm->subject_name }}</span>@if(count($courseSubjects) > $key+1),@endif
                                    @endforeach
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Non-Language Subjects
                                </td>
                                <td>
                                    @if (count($nonLanguageSubjects) > 0)
                                    @foreach ($nonLanguageSubjects as $key => $ssm)
                                    <span class="me-1">{{ $ssm->subject_name }}</span>@if(count($nonLanguageSubjects) > $key+1),@endif
                                    @endforeach
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <!-- Personal Details -->
                    <h6 class="fw-bold text-danger text-uppercase mb-3">Personal Details</h6>

                    <table class="w-100 table table-bordered">
                        <tr>
                            <th class="w-25">Student Name</th>
                            <td colspan="3">
                                <p class="mb-0">{{ $studentDetails->name }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25">
                                <strong class="form-label">Father Name</strong>
                            </td>
                            <td class="w-25">
                                <p class="mb-0">{{ $studentDetails->father_name }}</p>
                            </td>

                            <td class="w-25">
                                <strong class="form-label">Mother Name</strong>
                            </td>
                            <td class="w-25">
                                <p class="mb-0">{{ $studentDetails->mother_name }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="form-label">Gender</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->gender }}</p>
                            </td>
                            <td>
                                <strong class="form-label">DOB(Date Of Birth)</strong>
                            </td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->dob }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="form-label">Religion</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->religion }}</p>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="4"><strong>Permanent Address of Student</strong></td>
                        </tr>
                        <tr>
                            <td><strong class="form-label">Address</strong></td>
                            <td colspan="3">
                                <p class="mb-0">{{ $studentDetails->address }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="form-label">Pincode</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->pincode }}</p>
                            </td>

                            <td><strong class="form-label">State</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->state_name }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="form-label">Email</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->email }}</p>
                            </td>

                            <td><strong class="form-label">Contact Number</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->contact_number }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="form-label">Category</strong></td>
                            <td>

                                <p class="mb-0">{{ $studentDetails->category }}</p>
                            </td>

                            <td><strong class="form-label">Aadhar Card Number</strong></td>
                            <td>
                                <p class="mb-0">{{ $studentDetails->aadhar_number }}</p>
                                <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $studentDetails->aadhar) }}" class="text-danger" target="_BLANK">
                                    <i class='bx bx-show'></i> View
                                </a>
                            </td>
                        </tr>
                    </table>

                    @if ($studentDetails->edu_type == 'TWELVE')
                    <!-- Twelve Educational Details -->
                    <h6 class="fw-bold text-danger text-uppercase mb-3">Educational Details</h6>

                    <table class="w-100 table table-bordered">
                        <tbody>
                            <tr>
                                <th class="w-25">
                                    Subject
                                </th>
                                <td class="w-25">
                                    {{ $studentDetails->met_subj }}
                                </td>
                                <th class="w-25">
                                    Passing Year
                                </th>
                                <td class="w-25">
                                    {{ $studentDetails->met_year }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Board/
                                    University
                                </th>
                                <td>
                                    {{ $studentDetails->met_board }}
                                </td>
                                <th>
                                    Obtained
                                    Marks
                                </th>
                                <td>
                                    {{ $studentDetails->met_ob_mark }}
                                </td>
                            </tr>
                            <tr>

                                <th>
                                    Maximum
                                    Marks
                                </th>
                                <td>
                                    {{ $studentDetails->met_max_mark }}
                                </td>
                                <th>
                                    Percentage/
                                    Grade
                                </th>
                                <td>
                                    {{ $studentDetails->met_max_per }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif

                    @if ($studentDetails->edu_type == 'GRADUATION')
                    <!-- Graduation Educational Details -->
                    <h6 class="fw-bold text-danger text-uppercase mb-3">Educational Details</h6>

                    <table class="w-100 table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="4" class="bg-light">
                                    10th
                                </th>
                            </tr>
                            <tr>
                                <th class="w-25">
                                    <label for="" class="form-label">Passing Year</label>

                                </th>
                                <td class="w-25">
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_ten_year }}
                                    </p>
                                </td>
                                <th class="w-25">
                                    <label for="" class="form-label">Subject</label>

                                </th>
                                <td class="w-25">
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_ten_subj }}
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    <label for="" class="form-label">Board/University</label>
                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_ten_board }}
                                    </p>
                                </td>
                                <th>
                                    <label for="" class="form-label">Name of Board/University</label>
                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_ten_board_name }}
                                    </p>
                                </td>

                            </tr>

                            <tr>
                                <th colspan="4" class="bg-light">
                                    12th
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <label for="" class="form-label">Passing Year</label>

                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_twelve_year }}
                                    </p>
                                </td>
                                <th>
                                    <label for="" class="form-label">Subject</label>

                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_twelve_subj }}
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    <label for="" class="form-label">Board/University</label>

                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_twelve_board }}
                                    </p>
                                </td>
                                <th>
                                    <label for="" class="form-label">Name of Board/University</label>

                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_twelve_board_name }}
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <th colspan="4" class="bg-light">
                                    Other
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <label for="" class="form-label">Passing Year</label>

                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_other_year }}
                                    </p>
                                </td>
                                <th>
                                    <label for="" class="form-label">Subject</label>

                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_other_subj }}
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    <label for="" class="form-label">Board/University</label>
                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_other_board }}
                                    </p>
                                </td>
                                <th>
                                    <label for="" class="form-label">Name of Board/University</label>
                                </th>
                                <td>
                                    <p class="mb-0">
                                        {{ $studentDetails->ac_other_board_name }}
                                    </p>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection