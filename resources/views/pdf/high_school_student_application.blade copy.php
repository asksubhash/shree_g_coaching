@extends('layouts.pdf_generator_layout')

@section('content')

<div>
    <div class="text-center" style="margin-bottom: 30px; background-color: #dd3e29; padding: 20px; border-radius: 4px;">
        <img src="{{ asset('website_assets/images/site-logo-white.png') }}" alt="Image" class="logo-img" />
    </div>
    <div class="card">
        <div class="card-body">

            <div>
                <div style="float: left; width: 80%;">
                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">APPLICATION NO. :</strong>
                        <span class="st-det-inline-content">{{ $user->application_no }}</span>
                    </div>

                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">ACADEMIC YEAR :</strong>
                        <span class="st-det-inline-content">{{ $user->st_academic_year }}</span>
                    </div>

                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">SESSION :</strong>
                        <span class="st-det-inline-content">{{ $user->st_admission_session }}</span>
                    </div>

                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">CENTRE CODE :</strong>
                        <span class="st-det-inline-content">{{ $user->application_no }}</span>
                    </div>

                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">STUDY CENTRE NAME :</strong>
                        <span class="st-det-inline-content">{{ $user->institute_name }}</span>
                    </div>

                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">MEDIUM OF INSTRUCTION :</strong>
                        <span class="st-det-inline-content">{{ $user->medium_off_inst }}</span>
                    </div>

                    <div class="st-inline-content-col">
                        <strong class="st-content-heading">COURSE :</strong>
                        <span class="st-det-inline-content">{{ $user->course_name }}</span>
                    </div>
                </div>

                <div style="float: right; width: 20%;">
                    <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="stu-profile-img" />
                </div>
            </div>

            <div style="clear: both; margin: 0pt; padding: 0pt; "></div>

            <hr style="margin-bottom: 35px;">

            @if (isset($user->roll_number) && !empty($user->roll_number))
            <div class="st-content-col">
                <strong class="st-content-heading">ROLL NUMBER</strong>
                <p class="st-det-content">{{ $user->roll_number }}</p>
            </div>
            @endif

            <div class="st-content-col">
                <strong class="st-content-heading">STUDENT NAME</strong>
                <p class="st-det-content">{{ $user->name }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">FATHER NAME</strong>
                <p class="st-det-content">{{ $user->father_name }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">MOTHER NAME</strong>
                <p class="st-det-content">{{ $user->mother_name }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">ADDRESS FOR COMMUNICATION</strong>
                <p class="st-det-content">
                    {{ $user->address }},
                    {{ $user->state_name }} - {{ $user->pincode }}
                </p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">EMAIL</strong>
                <p class="st-det-content">{{ $user->email }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">MOBILE NUMBER</strong>
                <p class="st-det-content">{{ $user->contact_number }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">GENDER</strong>
                <p class="st-det-content">{{ $user->gender }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">DATE OF BIRTH</strong>
                <p class="st-det-content">{{ $user->dob }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">RELIGION</strong>
                <p class="st-det-content">{{ $user->religion }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">CATEGORY</strong>
                <p class="st-det-content">{{ $user->category }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">AADHAAR NUMBER</strong>
                <p class="st-det-content">{{ $user->aadhar_number }}</p>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">LANGUAGE SUBJECTS SELECTED</strong>
                <div class="st-det-content">
                    <div class="mt-2">
                        @if (count($courseSubjects) > 0)
                        @foreach ($courseSubjects as $key => $ssm)
                        <span class="subject-chips">{{ $ssm->subject_name }}</span>@if(count($courseSubjects) > $key+1),@endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="st-content-col">
                <strong class="st-content-heading">NON-LANGUAGE SUBJECTS SELECTED</strong>
                <div class="st-det-content">
                    <div class="mt-2">
                        @if (count($nonLanguageSubjects) > 0)
                        @foreach ($nonLanguageSubjects as $key => $ssm)
                        <span class="subject-chips">{{ $ssm->subject_name }}</span>@if(count($courseSubjects) > $key+1),@endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

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
        * {
            margin: 0px;
            padding: 0px;
        }

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
            /* border: 1px solid lightgrey; */
        }

        .table-for-students-data tr td {
            padding: 0px 8px;
        }

        .table-for-students-data tr td {
            /* border: 1px solid lightgrey !important; */
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

        .st-content-col {
            margin-bottom: 15px;
        }

        .st-inline-content-col {
            margin-bottom: 10px;
        }

        .st-content-heading {
            /* color: #585858; */
            color: #dd3e29;
        }

        .st-det-content {
            display: block !important;
            margin-bottom: 0px;
            margin-top: 5px;
            border: 1px dashed #dd3e29;
            padding: 10px 10px;
            font-weight: bold;
            font-size: 16px;
        }

        .st-det-inline-content {
            margin-bottom: 0px;
            margin-top: 0px;
            /* border-bottom: 1px dashed gray; */
            padding: 10px 10px;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>