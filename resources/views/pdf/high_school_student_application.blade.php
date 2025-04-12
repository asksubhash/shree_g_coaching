@extends('layouts.pdf_generator_layout')

@section('content')

<div>
    <div class="text-center" style="margin-bottom: 10px;">
        <img src="./website_assets/images/pdf-heading.jpg" alt="Image" class="logo-img" />
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title stu-card-title mb-3">Student Details</h4>
            <table class="w-100 table-for-students-data">
                <tr>
                    <td class="w-33"><strong class="form-label text-danger">Roll No.</strong></td>
                    <td>
                        <p class="mb-0 text-danger fw-bold">
                            @if($user->roll_number)
                            {{ $user->roll_number }}
                            @else
                            Not Allocated
                            @endif
                        </p>
                    </td>
                    <td rowspan="6" class="w-25 text-center" style="vertical-align: top;">
                        @php
                        $photoPath = Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/'.$user->photo;
                        @endphp

                        @if(Storage::exists('public/'.$photoPath))
                        <img src="./storage/{{$photoPath}}" alt="Image" class="stu-profile-img" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-33"><strong class="form-label">Application No.</strong></td>
                    <td>
                        <p class="mb-0">{{ $user->application_no }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Name of Student</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ $user->name }}</p>
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
                    <td><strong class="form-label">Academic Year & Session</strong></td>
                    <td>

                        <p class="mb-0">{{ $user->st_academic_year }}, {{ $user->st_admission_session }}</p>
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
                    <td class="w-33">
                        <strong class="form-label">Language Subjects</strong>
                    </td>
                    <td>
                        <div class="mt-2">
                            @if (count($courseSubjects) > 0)
                            @foreach ($courseSubjects as $key => $ssm)
                            <span class="subject-chips">{{ $ssm->subject_name }}</span>@if(count($courseSubjects) > $key+1),@endif
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
                            @foreach ($nonLanguageSubjects as $key => $ssm)
                            <span class="subject-chips">{{ $ssm->subject_name }}</span>@if(count($nonLanguageSubjects) > $key+1),@endif
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