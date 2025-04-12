@extends('layouts.master_layout')

@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center ">
        <div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Error</li>
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
                    <h6 class="fw-bold">
                        <span class="text-danger"><i class="bx bx-x"></i> {{ $errorMessage }}</span>
                    </h6>
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