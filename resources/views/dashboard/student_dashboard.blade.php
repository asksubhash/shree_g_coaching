@extends('layouts.master_layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Student Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('department/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>

    </div>
    <!-- /.content-header -->

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Welcome in Student Dashboard</h5>
                    <p>
                        You can view your assignments, study materials, exam notifications and exams details from this panel. In case of any query, contact your institute or raise a complaint in complaint section.
                    </p>
                </div>
            </div>
        </div>

        @if ($studentDetails && $studentDetails->is_approved == 1)
        <div class="col-12">
            <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2 card bg-white">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-success"><i class="bx bx-check"></i>
                    </div>
                    <div class="ms-3 d-flex alidn-items-center justify-content-between flex-fill">
                        <h6 class="mb-0 text-success">Admission Status</h6>
                        <span class="badge bg-success">Confirmed</span>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
    <!--end row-->


    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('pages-scripts')
@endsection