@extends('layouts.master_layout')
@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('ins_deo/dashboard') }}"><i class="bx bx-home-alt"></i></a>
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
            <div class="card p-3">
                <div class="row">
                    <div class="offset-md-3 col-md-4 col-sm-6 col-12 mb-3">
                        <label for="" class="form-label">Course</label>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">---Select---</option>
                            @foreach($courses as $course)
                            <option value="{{$course->id}}">{{$course->course_name}} ({{$course->course_code}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12 mb-3 d-flex align-items-end">
                        <button class="btn btn-primary" id="btnCourseProceed">
                            Proceed <i class="bx bx-pencil"></i>
                        </button>
                    </div>
                </div>

                <div class="marks-entry-col" style="display: none;">
                    <hr>
                    <form id="marksEntryForm">
                        @csrf
                        <div class="row">
                            <div class="offset-md-3 col-md-4 col-sm-6 col-12 shadow-sm my-3">
                                <div class="mb-3">
                                    <label for="" class="form-label">Download template to upload marks</label>
                                    <div>
                                        <a href="#" id="btnDownloadTemplate">
                                            <i class="bx bx-download"></i> Download Template
                                        </a>
                                        <p class="mb-1 mt-2">
                                            While uploading marks, you should download template and fill the marks into the template file and upload it.
                                        </p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Financial Year</label>
                                    <select name="financial_year" id="financial_year" class="form-control">
                                        <option value="">---Select---</option>
                                        <option value="2027-2028">2027-2028</option>
                                        <option value="2026-2027">2026-2027</option>
                                        <option value="2025-2026">2025-2026</option>
                                        <option value="2024-2025">2024-2025</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2021-2022">2021-2022</option>
                                        <option value="2020-2021">2020-2021</option>
                                        <option value="2019-2020">2019-2020</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Upload Marks File</label>
                                    <input type="file" name="upload_file" id="upload_file" class="form-control" />
                                </div>
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        Upload Marks <i class="bx bx-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
    // -----------------------------------------
    // On click proceed button
    $('#btnCourseProceed').on('click', function() {
        let courseId = $('#course_id').val();
        if (courseId) {
            $('.marks-entry-col').show()
        } else {
            toastr.error('Please select course to proceed')
        }
    })

    // -----------------------------------------
    // On click download template button
    $('#btnDownloadTemplate').on('click', function() {
        let courseId = $('#course_id').val();
        if (courseId) {
            window.open(`${base_url}/marks-entry/download-template/${courseId}`, '_BLANK');
        } else {
            toastr.error('Please select course to proceed')
        }
    })

    // -----------------------------------------
    // Submit the marks entry form
    $("#marksEntryForm").validate({
        errorClass: "text-danger validation-error",
        rules: {
            financial_year: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('marksEntryForm'));
            formData.append('course_id', $('#course_id').val());

            $.ajax({
                url: base_url + '/marks-entry/store',
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    $(".loader").hide();
                    var data = response;
                    if (data.status == true) {
                        toastr.success(data.message);

                    } else if (data.status == 'validation_errors') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: data.message
                        })
                    } else if (data.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        })
                    } else {
                        toastr.error('Something went wrong. Please try again.')
                    }
                },
                error: function(error) {
                    // $(".loader").hide();
                    toastr.error(error.statusText)
                }
            })
        }
    });
</script>
@endsection