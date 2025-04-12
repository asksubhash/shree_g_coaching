@extends('layouts.master_layout')
@section('content')
    <div class="page-content">
        <!-- Content Header (Page header) -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center ">
            <div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a
                                href="{{ url()->to(strtolower(Auth::user()->role_code) . '/dashboard') }}"><i
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
            <div class="card p-0 mt-2">
                <div class="card-header d-flex justify-content-between  align-items-baseline">
                    <ul class="nav nav-pills rounded mb-0  border-0" id="custom-pills-tab" role="tablist">
                        <li class="nav-item px-1" role="presentation">

                            <a href="{{ route('student.register', ['page' => App\Helpers\AppHelper::getStudentRegisterTabConstant('PERSONAL_DETAIL'), 'student_code' => $student_code]) }}"
                                class="nav-link  rounded  {{ $activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('PERSONAL_DETAIL') ? 'active' : '' }} "
                                id="course-offered-tab" role="tab" aria-controls="course-offered-tab-pane"
                                aria-selected="true">Personal Details
                            </a>
                        </li>
                        @if (isset($user))
                            <li class="nav-item px-1" role="presentation">
                                <a href="{{ route('student.register', ['page' => App\Helpers\AppHelper::getStudentRegisterTabConstant('ACADEMIC_DETAIL'), 'student_code' => $student_code]) }}"
                                    class="nav-link rounded {{ $activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('ACADEMIC_DETAIL') ? 'active' : '' }}"
                                    id="faculty-staff-tab" role="tab" aria-controls="faculty-staff-tab-pane"
                                    aria-selected="true">Academic Details</a>
                            </li>
                            <li class="nav-item px-1" role="presentation">
                                <a href="{{ route('student.register', ['page' => App\Helpers\AppHelper::getStudentRegisterTabConstant('FEE_DETAIL'), 'student_code' => $student_code]) }}"
                                    class="nav-link rounded {{ $activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('FEE_DETAIL') ? 'active' : '' }}"
                                    id="research-tab" role="tab" aria-controls="research-tab-pane"
                                    aria-selected="true">Fee
                                    Detail</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body p-4">
                    @if ($activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('PERSONAL_DETAIL'))
                        <div class="tab-pane fade show active" id="course-offered-tab" role="tabpanel"
                            aria-labelledby="course-offered-tab-pane" tabindex="0">
                            @include('components.backend.student.personal_detail_tab')
                        </div>
                    @endif

                    @if ($activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('ACADEMIC_DETAIL'))
                        <div class="tab-pane fade show active" id="faculty-staff-tab" role="tabpanel"
                            aria-labelledby="faculty-staff-tab-pane" tabindex="0">
                            @include('components.backend.student.academic_detail_tab')
                        </div>
                    @endif
                    @if ($activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('FEE_DETAIL'))
                        <div class="tab-pane fade show active" id="research-tab" role="tabpanel"
                            aria-labelledby="research-tab-pane" tabindex="0">
                            @include('components.backend.student.fee_detail_tab')
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('pages-scripts')
    <script>
        let student_code = "{{ $student_code }}";
    </script>
    @if ($activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('PERSONAL_DETAIL'))
        <script src="{{ asset('assets/js/student/personal_detail_tab.js') }}"></script>
    @endif
    @if ($activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('ACADEMIC_DETAIL'))
        <script>
            const student_id = "{{ $student_code }}";
        </script>
        <script src="{{ asset('assets/js/student/academic_detail_tab.js') }}"></script>
    @endif
    @if ($activeTab == App\Helpers\AppHelper::getStudentRegisterTabConstant('FEE_DETAIL'))
        <script src="{{ asset('assets/js/student/fee_detail_tab.js') }}"></script>
    @endif
@endsection
