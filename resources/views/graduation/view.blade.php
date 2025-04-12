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
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Academic Year</label>
                            <p class="mb-0">{{ $user->st_academic_year }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Institute</label>
                            <p class="mb-0">{{ $user->institute_name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Course</label>
                            <p class="mb-0">{{ $user->course_name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Admission Session</label>
                            <p class="mb-0">{{ $user->st_admission_session }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-danger border-top border-2 border-0">
                <div class="card-header card-header-light-bg">
                    <h6 class="mb-0 card-title text-dark fw-bold">
                        <i class="bx bx-file fw-bold"></i> Personal Details:
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Student Name</label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Father Name</label>
                            <p>{{ $user->father_name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Mother Name</label>
                            <p>{{ $user->mother_name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Student Name</label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Gender</label>
                            <p>{{ $user->gender }}</p>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Date Of Birth</label>
                            <p>{{ $user->dob }}</p>
                        </div>


                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Address</label>
                            <p>{{ $user->address }}</p>
                        </div>


                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Pincode</label>
                            <p>{{ $user->pincode }}</p>
                        </div>


                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">State</label>
                            <p>{{ $user->state_name }}</p>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Email Address</label>
                            <p>{{ $user->email }}</p>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Contact Number</label>
                            <p>{{ $user->contact_number }}</p>
                        </div>


                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Category</label>
                            <p>{{ $user->category }}</p>
                        </div>


                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Aadhar Card Number</label>
                            <p>{{ $user->aadhar_number }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-danger border-top border-2 border-0">
                <div class="card-header card-header-light-bg">
                    <h6 class="mb-0 card-title text-dark fw-bold">
                        <i class="bx bx-file fw-bold"></i> Academic Details:
                    </h6>
                </div>


                <div class="card-body">
                    <table class=" table table-bordered radius-10">
                        <thead>
                            <tr>
                                <th colspan="3" class="bg-dark text-white">
                                    10th
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label for="" class="form-label">Passing Year</label>
                                    <p class="mb-0">
                                        {{ $user->ac_ten_year }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Subject</label>
                                    <p class="mb-0">
                                        {{ $user->ac_ten_subj }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Board/University</label>
                                    <p class="mb-0">
                                        {{ $user->ac_ten_board }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="" class="form-label">Name of Board/University</label>
                                    <p class="mb-0">
                                        {{ $user->ac_ten_board_name }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Marksheet/Certificate</label>
                                    <p class="mb-0">
                                        <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_METRIC_MARKSHEET_VIEW_PATH'].'/' . $user->ac_ten_sheet) }}" class=" btn btn-danger btn-sm" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <th colspan="3" class="bg-dark text-white">
                                    12th
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label for="" class="form-label">Passing Year</label>
                                    <p class="mb-0">
                                        {{ $user->ac_twelve_year }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Subject</label>
                                    <p class="mb-0">
                                        {{ $user->ac_twelve_subj }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Board/University</label>
                                    <p class="mb-0">
                                        {{ $user->ac_twelve_board }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="" class="form-label">Name of Board/University</label>
                                    <p class="mb-0">
                                        {{ $user->ac_twelve_board_name }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Marksheet/Certificate</label>
                                    <p class="mb-0">
                                        <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_TWELVE_MARKSHEET_VIEW_PATH'].'/' . $user->ac_twelve_sheet) }}" class=" btn btn-danger btn-sm" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <th colspan="3" class="bg-dark text-white">
                                    Other
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label for="" class="form-label">Passing Year</label>
                                    <p class="mb-0">
                                        {{ $user->ac_other_year }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Subject</label>
                                    <p class="mb-0">
                                        {{ $user->ac_other_subj }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Board/University</label>
                                    <p class="mb-0">
                                        {{ $user->ac_other_board }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="" class="form-label">Name of Board/University</label>
                                    <p class="mb-0">
                                        {{ $user->ac_other_board_name }}
                                    </p>
                                </td>
                                <td>
                                    <label for="" class="form-label">Marksheet/Certificate</label>
                                    @if (isset($user->ac_other_sheet) && !empty($user->ac_other_sheet))
                                    <p class="mb-0">
                                        <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_OTHER_MARKSHEET_VIEW_PATH'].'/' . $user->ac_other_sheet) }}" class=" btn btn-danger btn-sm" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
                                    </p>
                                    @endif
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card border-danger border-top border-2 border-0">
                <div class="card-header card-header-light-bg">
                    <h6 class="mb-0 card-title text-dark fw-bold">
                        <i class="bx bx-file fw-bold"></i> Document submit options
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label class="form-label">Photo </label>

                            <div>
                                <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="img-thumbnail w-100" />
                            </div>

                            <div class="mt-2 text-center">
                                <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                    <i class='bx bx-download'></i> Download
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                            <label class="form-label">Aadhar </label>

                            <div>
                                <object data="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $user->aadhar) }}" class="img-thumbnail w-100" style="height: 280px;"></object>
                            </div>

                            <div class="mt-2 text-center">
                                <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $user->aadhar) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                    <i class='bx bx-download'></i> Download
                                </a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @endsection