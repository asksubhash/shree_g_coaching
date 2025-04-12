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
    <div class="row mt-3">
        @if ($assignments->count() > 0)
        @foreach ($assignments as $as)

        @php
        $uploadedDetails = AppHelper::getUploadedAssignmentDetUsingAssignmentId($as->id);
        @endphp
        <div class="col-12 mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill d-flex align-items-center pe-3">
                            <div class="widgets-icons rounded-circle bg-light-danger text-danger me-3">
                                <i class="bx bx-book"></i>
                            </div>
                            <div>
                                <h6 class="mt-1 mb-1 fw-bold">{{ $as->title }}</h6>
                                <p class="mb-2 text-secondary">
                                    @if ($as->subject_type == 'LANGUAGE')
                                    ({{ $as->language_subject }})
                                    @elseif ($as->subject_type == 'NON_LANGUAGE')
                                    ({{ $as->non_language_subject }})
                                    @else
                                    N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <div>
                                @if($uploadedDetails)
                                <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_UPLOAD_ASSIGNMENTS_VIEW_PATH'].'/' . $uploadedDetails->document) }}" class="btn btn-success btn-sm" target="_BLANK">
                                    <i class="bx bx-download"></i> View Assignment
                                </a>
                                @else
                                <button type="button" class="btn btn-warning btn-sm btn_show_upload_assignment" data-id="{{  Crypt::encryptString($as->id) }}" data-title="{{ base64_encode($as->title) }}">
                                    <i class="bx bx-upload"></i> Upload Assignment
                                </button>
                                @endif
                                <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['ASSIGNMENTS_VIEW_PATH'].'/' . $as->document) }}" class="btn btn-primary btn-sm" target="_BLANK">
                                    <i class="bx bx-download"></i> Download Assignment
                                </a>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0 mt-2 border-top pt-2">
                        @if($uploadedDetails)
                        <strong>Status: </strong> <span class="badge bg-success">Uploaded</span> | <strong>Uploaded On:</strong> <span>{{ Carbon\Carbon::parse($uploadedDetails->uploaded_on)->format('F j, Y') }}</span>
                        @else
                        <strong>Information:</strong> Assignment is not uploaded yet
                        @endif
                    </p>
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
                        <h4 class="my-1">No assignments available</h4>
                        <p class="mb-0 text-secondary">Currently there is no assignment available. Please contact centre in case of any query.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Upload Assignment offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="uploadAssignmentOffcanvas" aria-labelledby="uploadAssignmentOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="uploadAssignmentOffcanvasLabel">Upload Assignment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div>
            <form action="javascript:void(0)" class="uploadAssignmentForm" id="uploadAssignmentForm">
                @csrf
                <input type="hidden" name="assignments_id" id="assignments_id">

                <div class="mb-3">
                    <label class="form-label" for="title">Title <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" name="title" id="title" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="document">Upload Document <strong class="text-danger">*</strong></label>
                    <input type="file" class="form-control" name="document" id="document" accept=".pdf">
                    <small class="text-danger">
                        Max Size 20MB, Only .pdf files allowed
                    </small>
                </div>

                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                    <button type="reset" class="btn btn-default" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@section('pages-scripts')
<script>
    function formReset() {
        document.getElementById("uploadAssignmentForm").reset();

        $('#assignments_id').val('');
        $('#title').val('');

        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Study Material');
        $("#uploadAssignmentForm").validate().resetForm();
        $("#uploadAssignmentForm").trigger('reset');
    }

    $('.btn_show_upload_assignment').on('click', function() {
        formReset();

        let assignmentId = $(this).data('id');
        let title = $(this).data('title');

        $('#title').val(atob(title));
        $('#assignments_id').val(btoa(assignmentId));
        $('#uploadAssignmentOffcanvas').offcanvas('show')
    })

    /** 
     * Upload Assignment
     */
    $("#uploadAssignmentForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            document: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('uploadAssignmentForm'));
            // Check the operation type
            var url = base_url + '/student/assignment/upload';

            $.ajax({
                url: url,
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
                    toastr.error('Something went wrong. Please try again.')
                }
            });
        }
    });
</script>
@endsection