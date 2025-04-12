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
            <div class="card">
                <div class="card-body">
                    @if (session('error_message'))
                    <div class="alert alert-danger mb-3">
                        {{ session('error_message') }}
                    </div>
                    @endif
                    <p>
                        <i class="bx bx-paper-plane"></i> Click on the button to see your result.
                    </p>
                    <a href="{{ url()->to('/student-result/show?roll_number=' . urlencode(base64_encode($rollNumber))) }}" class="btn btn-custom" target="_BLANK">View Result</a>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    @endsection

    @section('pages-scripts')
    <script>
    </script>
    @endsection