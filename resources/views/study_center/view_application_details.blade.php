@extends('layouts.master_layout')
@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to(strtolower(auth::user()->role_code) . '/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <p class="mb-0">
                    Current application status:
                    @if ($scData->is_verified == 0)
                    <span class="text-primary fw-bold">Pending</span>
                    @elseif ($scData->is_verified == 1)
                    <span class="text-success fw-bold">Approved</span>
                    @elseif ($scData->is_verified == 2)
                    <span class="text-danger fw-bold">Rejected</span>
                    @endif
                </p>
                @if ($scData->remarks)
                <p class="mb-0">
                    <strong>Remarks:</strong><br>
                    {{ $scData->remarks }}
                </p>
                @endif
            </div>
            <div class="row">
                <div class="col-md-9 col-12">
                    <!-- Personal Details -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header card-header-light-bg py-3">
                            <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                                <i class="bx bx-file fw-bold"></i> Personal Details:-
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="name" class="form-label">Name<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->name)?$scData->name:'' }}
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="email" class="form-label">Email Id<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->email_id)?$scData->email_id:'' }}
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="contact" class="form-label">Contact Number<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->contact_no)?$scData->contact_no:'' }}
                                    </p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="address1" class="form-label">Address 1<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->address1)?$scData->address1:'' }}
                                    </p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="address2" class="form-label">Address 2
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->address2)?$scData->address2:'' }}
                                    </p>
                                </div>


                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="education_qualification" class="form-label">Education
                                        Qualification<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->education_qualification)?$scData->education_qualification:'' }}
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="occupation" class="form-label">Occupation<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->occupation)?$scData->occupation:'' }}
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="nature_or_work" class="form-label">Nature Of Work<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->nature_of_work)?$scData->nature_of_work:'' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Study Center Details -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header card-header-light-bg py-3">
                            <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                                <i class="bx bx-user fw-bold"></i> Study Center Detail:-
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="state" class="form-label ">State<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->state)?$scData->state_name:'' }}
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="district" class="form-label ">District <span class="text-danger">*</span></label>
                                    <p class="mb-0">
                                        {{ isset($scData->district_name)?$scData->district_name:'' }}
                                    </p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="city" class="form-label ">City Name<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->city_name)?$scData->city_name:'' }}
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label class="form-label " for="pin_code">Pin Code<span class="text-danger">*</span></label>
                                    <p class="mb-0">
                                        {{ isset($scData->pin_code)?$scData->pin_code:'' }}
                                    </p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label class="form-label " for="institute_name">Name Of Institute
                                        <span class="text-danger">*</span></label>
                                    <p class="mb-0">
                                        {{ isset($scData->institute_name)?$scData->institute_name:'' }}
                                    </p>
                                </div>


                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="property" class="form-label ">Property<span class="text-danger">*</span>
                                    </label>
                                    <p class="mb-0">
                                        {{ isset($scData->property)?$scData->property:'' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header card-header-light-bg py-3">
                            <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                                <i class="bx bx-file fw-bold"></i> Documents
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="photo" class="form-label">Passport Size Photo<span class="text-danger">*</span>
                                    </label>

                                    <div>
                                        <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_PHOTO_VIEW_PATH'].'/' . $scData->passport_photo) }}" alt="Image" class="img-thumbnail w-100" />
                                    </div>

                                    <div class="mt-2 text-center">
                                        <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_PHOTO_VIEW_PATH'].'/' . $scData->passport_photo) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                            <i class='bx bx-download'></i> Download
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="aadhar" class="form-label">Aadhar Card<span class="text-danger">*</span>
                                    </label>

                                    <div>
                                        <object data="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_AADHAAR_VIEW_PATH'].'/' . $scData->aadhar_card) }}" class="img-thumbnail w-100" style="height: 280px;"></object>
                                    </div>

                                    <div class="mt-2 text-center">
                                        <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_AADHAAR_VIEW_PATH'].'/' . $scData->aadhar_card) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                            <i class='bx bx-download'></i> Download
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="document" class="form-label">Education Document<span class="text-danger">*</span>
                                    </label>

                                    <div>
                                        <object data="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_EDU_DOC_VIEW_PATH'].'/' . $scData->education_document) }}" class="img-thumbnail w-100" style="height: 280px;"></object>
                                    </div>

                                    <div class="mt-2 text-center">
                                        <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_EDU_DOC_VIEW_PATH'].'/' . $scData->education_document) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                            <i class='bx bx-download'></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header card-header-light-bg py-3">
                            <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                                <i class="bx bx-file fw-bold"></i> Approval/Rejection:-
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="" id="approvalForm">
                                @csrf
                                <input type="hidden" name="study_center_id" value="{{ $scData->id }}">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status <strong class="text-danger">*</strong></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                    </select>
                                </div>

                                <div class="mb-3 institute_code_col" style="display: none;">
                                    <label class="form-label" for="institute_code">Institute Code <strong class="text-danger">*</strong></label>
                                    <input text class="form-control" name="institute_code" id="institute_code" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks" rows="4"></textarea>
                                </div>

                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary btn-sm" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
                                </div>
                            </form>
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
@section('pages-scripts')
<script>
    const APPROVAL_URL = base_url + '/study-centers/new-applications/approval';

    // ---------------------------------------------
    $(document).on('click', '#status', function() {
        let status = $(this).val();
        $('.institute_code_col').hide();
        if (status == 1) {
            $('.institute_code_col').show();
        }
    })

    // ---------------------------------------------
    // Approval form submission
    $("#approvalForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            status: {
                required: true
            },
            remarks: {
                required: false
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('approvalForm'));

            $.ajax({
                url: APPROVAL_URL,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: response.message
                        }).then(() => {
                            window.location.reload();
                        })
                    } else if (response.status == 'validation_errors') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: response.message
                        })
                    } else if (response.status == false) {
                        toastr.error(response.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(error) {
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        }
    });
</script>
@endsection