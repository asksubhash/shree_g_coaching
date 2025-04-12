@extends('layouts.master_layout')
@section('css')
    <style>
        .form-label {
            margin-bottom: 0.2rem;
        }
    </style>
@endsection
@section('content')
    <div class="page-content">
        <!-- Content Header (Page header) -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center ">
            <div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a
                                href="{{ url()->to(strtolower(auth::user()->role_code) . '/dashboard') }}"><i
                                    class="bx bx-home-alt"></i></a>
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
                                <label class="form-label">Medium of Instruction</label>
                                <p class="mb-0">{{ $user->medium_off_inst }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Academic Year</label>
                                <p class="mb-0">{{ $user->st_academic_year }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Admission Session</label>
                                <p class="mb-0">{{ $user->st_admission_session }}</p>
                            </div>
                            @if (Auth::user()->role->role_code == 'ADMIN')
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label class="form-label">Institute</label>
                                    <p class="mb-0">{{ $user->institute_name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card border-danger border-top border-2 border-0">
                    <div class="card-header card-header-light-bg">
                        <h6 class="mb-0 card-title text-dark fw-bold">
                            <i class="bx bx-file fw-bold"></i> Class / Subject
                            Details:
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Class</label>
                                <div class="mt-2">
                                    <p class="mb-0">{{ $user->course_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Subjects</label>
                                <div class="mt-2">
                                    @if (count($classSubjects) > 0)
                                        @foreach ($classSubjects as $ssm)
                                            <span
                                                class="bg-dark px-3 py-2 rounded me-1 mb-1 d-inline-block text-white">{{ $ssm->subject_name }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Personal Details --}}
            <div class="col-12">
                <div class="card border-danger border-top border-2 border-0">
                    <div class="card-header card-header-light-bg">
                        <h6 class="mb-0 card-title text-dark fw-bold">
                            <i class="bx bx-user fw-bold"></i> Personal Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Student Name</label>
                                <p class="mb-0">{{ $user->name }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Father Name</label>
                                <p class="mb-0">{{ $user->father_name }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Mother Name</label>
                                <p class="mb-0">{{ $user->mother_name }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Gender</label>
                                <p class="mb-0">{{ $user->gender }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">DOB(Date Of Birth)</label>
                                <p class="mb-0">{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Religion</label>
                                <p class="mb-0">{{ $user->religion }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Address</label>
                                <p class="mb-0">{{ $user->address }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Pincode</label>
                                <p class="mb-0">{{ $user->pincode }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">State</label>
                                <p class="mb-0">{{ $user->state_name }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Email</label>
                                <p class="mb-0">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Contact Number</label>
                                <p class="mb-0">{{ $user->contact_number }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Category</label>
                                <p class="mb-0">{{ $user->category }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label">Aadhar Card Number</label>
                                <p class="mb-0">{{ $user->aadhar_number }}</p>
                            </div>
                        </div>
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
                                    <img src="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'] . '/' . $user->photo) }}"
                                        alt="Image" class="img-thumbnail w-100 h-75" />
                                </div>

                                <div class="mt-2 text-center">
                                    <a href="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'] . '/' . $user->photo) }}"
                                        class=" btn btn-danger btn-sm" download>
                                        <i class='bx bx-download'></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12 mb-3">
                                <label class="form-label">Aadhar Card </label>
                                <div>
                                    <object
                                        data="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'] . '/' . $user->aadhar) }}"
                                        class="img-thumbnail w-100 h-75"></object>
                                </div>
                                <div class="mt-2 text-center">
                                    <a href="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'] . '/' . $user->aadhar) }}"
                                        class=" btn btn-danger btn-sm" download>
                                        <i class='bx bx-download'></i> Download
                                    </a>
                                </div>

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
