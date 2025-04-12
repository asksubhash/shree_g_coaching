@extends('layouts.pdf_generator_layout')

@section('content')

<div>
    <div class="text-center mb-3">
        <img src="{{ asset('website_assets/images/site-logo.png') }}" alt="Image" class="logo-img" />
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title stu-card-title mb-3">Student Details</h4>
            <table class="w-100 table-for-students-data">
                <tr>
                    <td class="w-33"><strong class="form-label">Application No.</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->application_no }}</p>
                    </td>
                    <td rowspan="6" class="w-25 text-center">
                        <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="stu-profile-img" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Medium of Instruction</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ $user->medium_off_inst }}</p>
                    </td>

                </tr>
                <tr>
                    <td><strong class="form-label">Academic Year</strong></td>
                    <td>

                        <p class="mb-0">{{ $user->st_academic_year }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Institute</strong>

                    </td>
                    <td>
                        <p class="mb-0">{{ $user->institute_name }}</p>
                    </td>

                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Course</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ $user->course_name }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Admission Session</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ $user->st_admission_session }}</p>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-light-bg">
            <h4 class="card-title stu-card-title mb-3">Language / Non-Language Details</h4>
        </div>
        <div class="card-body">
            <table class="w-100 table table-for-students-data">
                <tr>
                    <td class="w-50">
                        <strong class="form-label">Language Subjects</strong>
                    </td>
                    <td>
                        <div class="mt-2">
                            @if (count($courseSubjects) > 0)
                            @foreach ($courseSubjects as $ssm)
                            <span class="subject-chips">{{ $ssm->subject_name }},</span>
                            @endforeach
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Non Language</strong>
                    </td>
                    <td>
                        <div class="mt-2">
                            @if (count($nonLanguageSubjects) > 0)
                            @foreach ($nonLanguageSubjects as $ssm)
                            <span class="subject-chips">{{ $ssm->subject_name }},</span>
                            @endforeach
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-light-bg">
            <h4 class="card-title stu-card-title mb-3">Personal Details</h4>
        </div>
        <div class="card-body">
            <table class="w-100 table table-for-students-data">
                <tr>
                    <td class="w-25">
                        <strong class="form-label">Father Name</strong>
                    </td>
                    <td class="w-25">
                        <p class="mb-0">{{ $user->father_name }}</p>
                    </td>

                    <td class="w-25">
                        <strong class="form-label">Mother Name</strong>
                    </td>
                    <td class="w-25">
                        <p class="mb-0">{{ $user->mother_name }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Gender</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->gender }}</p>
                    </td>
                    <td>
                        <strong class="form-label">DOB(Date Of Birth)</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ $user->dob }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Religion</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->religion }}</p>
                    </td>

                </tr>
                <tr>
                    <td colspan="4"><strong>Permanent Address of Student</strong></td>
                </tr>
                <tr>
                    <td><strong class="form-label">Address</strong></td>
                    <td colspan="3">
                        <p class="mb-0">{{ $user->address }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Pincode</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->pincode }}</p>
                    </td>

                    <td><strong class="form-label">State</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->state_name }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Email</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->email }}</p>
                    </td>

                    <td><strong class="form-label">Contact Number</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->contact_number }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Category</strong></td>
                    <td>

                        <p class="mb-0">{{ $user->category }}</p>
                    </td>

                    <td><strong class="form-label">Aadhar Card Number</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->aadhar_number }}</p>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>
@endsection













<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <!-- <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"> -->

    <style>
        body {
            font-family: freesans;
        }

        strong {
            font-weight: bold;
        }

        label {
            font-weight: bold;
            float: unset;
        }

        .w-25 {
            width: 25%;
        }

        .w-33 {
            width: 33%;
        }

        .w-50 {
            width: 50%;
        }

        .w-75 {
            width: 75%;
        }

        .w-100 {
            width: 100%;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mt-3 {
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .table-for-students-data {
            border-spacing: 0;
            width: 100%;
            border: 1px solid lightgrey;
        }

        .table-for-students-data tr td {
            padding: 8px 8px;
        }

        .table-for-students-data tr td {
            border: 1px solid lightgrey !important;
        }

        .form-label {
            font-weight: bold !important;
            margin-bottom: 10px;
        }

        .stu-profile-img {
            width: 100px;
            border: 1px solid #191919;
            background-color: lightgrey;
        }

        .logo-img {
            width: 300px;
        }

        .stu-card-title {
            color: red;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>