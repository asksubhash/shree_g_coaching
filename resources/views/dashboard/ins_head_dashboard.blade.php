@extends('layouts.master_layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>

    </div>
    <!-- /.content-header -->

    <!-- Main content -->

    <div class="row">


        <div class="col-xl-9 col-lg-8 col-12">
            <!-- New -->
            <div class="row row-cols-1 row-cols-md-4 row-cols-xl-3 row-cols-lg-3">
                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">10th New Applications</p>
                                    <h4 class="my-1">{{ $newStudentsData['tenthStudentsCount'] }}</h4>
                                </div>
                                <div class="text-primary ms-auto font-35"><i class="bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">10th Approved Applications</p>
                                    <h4 class="my-1">{{ $approvedStudentsData['tenthStudentsCount'] }}</h4>
                                </div>
                                <div class="text-success ms-auto font-35"><i class='bx bx-user-check'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">All 10th Students</p>
                                    <h4 class="my-1">{{ $data['tenthStudentsCount'] }}</h4>
                                </div>
                                <div class="text-danger ms-auto font-35"><i class='bx bxs-user-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

            <!-- Approved -->
            <div class="row row-cols-1 row-cols-md-4 row-cols-xl-3 row-cols-lg-3">
                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">12th New Applications</p>
                                    <h4 class="my-1">{{ $newStudentsData['twelfthStudentsCount'] }}</h4>
                                </div>
                                <div class="text-primary ms-auto font-35"><i class="bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">12th Approved Applications</p>
                                    <h4 class="my-1">{{ $approvedStudentsData['twelfthStudentsCount'] }}</h4>
                                </div>
                                <div class="text-success ms-auto font-35"><i class='bx bx-user-check'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">All 12th Students</p>
                                    <h4 class="my-1">{{ $data['twelfthStudentsCount'] }}</h4>
                                </div>
                                <div class="text-danger ms-auto font-35"><i class='bx bxs-user-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row row-cols-1 row-cols-md-4 row-cols-xl-3 row-cols-lg-3">
                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary"> Graduation New Applications</p>
                                    <h4 class="my-1">{{ $newStudentsData['graduationStudentsCount'] }}</h4>
                                </div>
                                <div class="text-primary ms-auto font-35"><i class="bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary"> Graduation Approved Applications</p>
                                    <h4 class="my-1">{{ $approvedStudentsData['graduationStudentsCount'] }}</h4>
                                </div>
                                <div class="text-success ms-auto font-35"><i class='bx bx-user-check'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">All Graduation Students</p>
                                    <h4 class="my-1">{{ $data['graduationStudentsCount'] }}</h4>
                                </div>
                                <div class="text-danger ms-auto font-35"><i class='bx bxs-user-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- All -->
            <div class="row row-cols-1 row-cols-md-4 row-cols-xl-3 row-cols-lg-3">

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">All New Applications</p>
                                    <h4 class="my-1">{{ $newStudentsData['totalStudentsCount'] }}</h4>
                                </div>
                                <div class="text-primary ms-auto font-35"><i class="bx bx-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">All Approved Applications</p>
                                    <h4 class="my-1">{{ $approvedStudentsData['totalStudentsCount'] }}</h4>
                                </div>
                                <div class="text-success ms-auto font-35"><i class='bx bx-user-check'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">All Students</p>
                                    <h4 class="my-1">{{ $data['totalStudentsCount'] }}</h4>
                                </div>
                                <div class="text-danger ms-auto font-35"><i class='bx bxs-user-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-danger fw-bold">
                        IMPORTANT NOTE:
                    </h6>
                    <p>
                        <strong>
                            New Centers will have to pay the Center Registration fees of Rs.2,000/- in the Office Account given below.
                        </strong>
                    </p>
                    <p class="mb-0">
                        <strong>Bank Account Details-</strong>
                    </p>
                    <p>
                        Name- Odisha Rastrabhasa Parisad, Puri. <br>
                        Bank Name- UCO Bank. <br>
                        A/C No.- 02900100002266 <br>
                        IFSC- UCBA0000290 <br>
                        Branch- Puri.
                    </p>

                    <p>
                        <strong>
                            After the Payment of Fees, send the Bank Receipt or Screenshot in this WhatsApp No.- <span class="text-info">7683882331</span> or by E-Mail in <span class="text-info">
                                odisharastrabhasa@gmail.com
                            </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>


    </div>
    <!--end row-->

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


@if(isset($announcements) && count($announcements) > 0)
{{-- MODAL --}}
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="announcementModalLabel">Latest Announcements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                @foreach ($announcements as $announcement)
                <div>
                    {!! $announcement->announcement !!}
                </div>
                <hr>
                <div class="text-danger">
                    {{$announcement->from_date}} to {{ $announcement->to_date }}
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endif

@endsection

@section('pages-scripts')
@if(isset($announcements) && count($announcements) > 0)
<script>
    $(document).ready(function() {
        $('#announcementModal').modal('show')
    })
</script>
@endif
@endsection