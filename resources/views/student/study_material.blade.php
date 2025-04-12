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
            <a href="{{ url()->previous() }}" class="btn btn-secondary"> <i class='bx bx-arrow-back'></i></a>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <!-- Language Subjects Study Material -->
            @if ($studyMaterialsLanSubjects->count() > 0)
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3 text-center">
                        -------- Language Subjects Study Materials --------
                    </h5>
                </div>
                @foreach ($studyMaterialsLanSubjects as $sm)
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class="bx bx-book"></i>
                                </div>
                                <h6 class="mt-1 mb-1 fw-bold">{{ $sm->title }}</h6>
                                <p class="mb-2 text-secondary">
                                    @if ($sm->subject_type == 'LANGUAGE')
                                    ({{ $sm->language_subject }})
                                    @else
                                    N/A
                                    @endif
                                </p>
                                <div>
                                    <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_MATERIAL_VIEW_PATH'].'/' . $sm->document) }}" class="btn btn-custom btn-sm" target="_BLANK">
                                        <i class="bx bx-show"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-12 mb-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class="bx bx-book"></i>
                                </div>
                                <h4 class="my-1">No study materials available for language subjects</h4>
                                <p class="mb-0 text-secondary">Currently there is no study material available. Please contact centre in case of any query.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif

            <!-- Non Language SUbjects Study Material -->
            @if ($studyMaterialsNonLanSubjects->count() > 0)
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3 text-center">
                        -------- Non-Language Subjects Study Materials --------
                    </h5>
                </div>
                @foreach ($studyMaterialsNonLanSubjects as $sm)
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class="bx bx-book"></i>
                                </div>
                                <h6 class="mt-1 mb-1 fw-bold">{{ $sm->title }}</h6>
                                <p class="mb-2 text-secondary">
                                    @if ($sm->subject_type == 'NON_LANGUAGE')
                                    ({{ $sm->non_language_subject }})
                                    @else
                                    N/A
                                    @endif
                                </p>
                                <div>
                                    <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_MATERIAL_VIEW_PATH'].'/' . $sm->document) }}" class="btn btn-custom btn-sm" target="_BLANK">
                                        <i class="bx bx-show"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-12 mb-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class="bx bx-book"></i>
                                </div>
                                <h4 class="my-1">No study materials available for language subjects</h4>
                                <p class="mb-0 text-secondary">Currently there is no study material available. Please contact centre in case of any query.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection