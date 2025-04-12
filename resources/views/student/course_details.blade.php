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
        {{-- Course Details --}}
        <div class="col-12">
            <div class="card border-danger border-top border-2 border-0">
                <div class="card-header card-header-light-bg">
                    <h6 class="mb-0 card-title text-dark fw-bold">
                        <i class="bx bx-book fw-bold"></i> Course Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Course Name</label>
                            <p class="mb-0">{{ $courseDetails->course_name }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Course Code</label>
                            <p class="mb-0">{{ $courseDetails->course_code }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Duration</label>
                            <p class="mb-0">{{ $courseDetails->duration }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Amount</label>
                            <p class="mb-0">{{ $courseDetails->amount }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Description</label>
                            <p class="mb-0">{{ $courseDetails->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-danger border-top border-2 border-0">
                <div class="card-header card-header-light-bg">
                    <h6 class="mb-0 card-title text-dark fw-bold">
                        <i class="bx bx-file fw-bold"></i> Language / Non-Language
                        Details:
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Language Subjects</label>
                            <div class="mt-2">
                                @if (count($courseSubjects) > 0)
                                @foreach ($courseSubjects as $ssm)
                                <span class="bg-dark px-3 py-2 rounded me-1 text-white">{{ $ssm->subject_name }}</span>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Non Language</label>
                            <div class="mt-2">
                                @if (count($nonLanguageSubjects) > 0)
                                @foreach ($nonLanguageSubjects as $ssm)
                                <span class="bg-dark px-3 py-2 rounded me-1 text-white">{{ $ssm->subject_name }}</span>
                                @endforeach
                                @endif
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