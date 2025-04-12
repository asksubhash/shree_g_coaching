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
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
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
                            <label class="form-label">Payment Type</label>
                            <p class="mb-0">{{ $paymentData->payment_type }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Payment Method</label>
                            <p class="mb-0">{{ $paymentData->payment_method }}</p>
                        </div>

                        @if ($paymentData->payment_method == 'UPI')
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">UPI Name</label>
                            <p class="mb-0">{{ $paymentData->upi_name }}</p>
                        </div>
                        @endif

                        @if ($paymentData->payment_method == 'BANK')
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Payment Through</label>
                            <p class="mb-0">{{ $paymentData->payment_through }}</p>
                        </div>
                        @endif

                        @if ($paymentData->payment_method == 'CASH')
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Receipt No</label>
                            <p class="mb-0">{{ $paymentData->receipt_no }}</p>
                        </div>
                        @endif

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Payment Date</label>
                            <p class="mb-0">{{ $paymentData->payment_date }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Transaction ID</label>
                            <p class="mb-0">{{ $paymentData->transaction_id }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Transaction Details</label>
                            <p class="mb-0">{{ $paymentData->transaction_details }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Fees Amount</label>
                            @php
                            $totalAmount = $paymentData->amount;
                            @endphp
                            <p class="mb-0">{{ $paymentData->amount }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Payment Document</label>
                            <p class="mb-0">
                                <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_FEES_PAY_DOC_VIEW_PATH'].'/' . $paymentData->payment_document) }}" class=" btn btn-primary btn-sm" target="_BLANK">
                                    <i class='bx bx-show'></i> View
                                </a>
                            </p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Late Fees If Any</label>
                            <p class="mb-0">{{ ($paymentData->late_fees_if_any == 1)?'Yes':'No' }}</p>
                        </div>

                        @if ($paymentData->late_fees_if_any == 1)
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Late Fees Amount</label>
                            @php
                            $totalAmount += $paymentData->late_fees_amount;
                            @endphp
                            <p class="mb-0">{{ $paymentData->late_fees_amount }}</p>
                        </div>
                        @endif

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Total Amount</label>
                            <p class="mb-0">{{ $totalAmount }}</p>
                        </div>


                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <p class="mb-0">{{ $paymentData->remarks }}</p>
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