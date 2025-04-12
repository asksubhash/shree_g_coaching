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
        <div class="ms-auto">
            <a href="{{ url()->previous() }}" class="btn btn-primary"> <i class='bx bx-arrow-back'></i></a>
        </div>
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
        <form action="javascript:void(0)" id="studentForm" name="studentForm">
            @csrf

            @if (isset($user) && !empty($user->id))
            <input type="hidden" name="hiddenId" value="{{ base64_encode($user->id) }}">
            <input type="hidden" name="operation_type" id="operation_type" value="EDIT">
            @else
            <input type="hidden" name="operation_type" id="operation_type" value="ADD">
            @endif

            <div class="col-12">
                <div class="card border-danger border-top border-2 border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="academic_year" class="form-label">Academic Year<span class="text-danger">*</span> </label>
                                <select name="academic_year" class=" form-select form-control" id="academic_year" required>
                                    <option value="">Select</option>
                                    @foreach ($academic_years as $ay)
                                    <option value="{{ $ay->id }}" @selected(isset($user) && $user->academic_year == $ay->id)>
                                        {{ $ay->academic_year }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            @if (auth()->user()->role_code != 'STUDENT')
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="institute_id" class="form-label">Institute<span class="text-danger">*</span>
                                </label>
                                <select name="institute_id" class=" form-select form-control" id="institute_id" required>
                                    <option value="">Select</option>
                                    @foreach ($institutes as $item)
                                    <option value="{{ $item->institute_id }}" @selected(isset($user) && $user->institute_id == $item->institute_id)>
                                        {{ $item->name }} ({{ $item->institute_code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="institute_id" id="institute_id" value="{{ env('CENTER_INSTITUTE_ID') }}">
                            @endif

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="course" class="form-label">Course<span class="text-danger">*</span>
                                </label>
                                <select name="course" class=" form-select form-control" id="course" required>
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $item)
                                    <option value="{{ $item->id }}" @selected(isset($user) && $user->course == $item->id)>
                                        {{ $item->course_name }}({{ $item->course_code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="admission_session" class="form-label">Admission Session<span class="text-danger">*</span> </label>
                                <select name="admission_session" class=" form-select form-control" id="admission_session" required>
                                    <option value="">Select</option>
                                    @if (isset($user) && !empty($user->id))

                                    @php
                                    $admissionSessions = AppHelper::getAdmissionSessUsingAyInsCourse($user->academic_year, $user->institute_id, $user->course);
                                    @endphp

                                    @foreach ($admissionSessions as $item)
                                    <option value="{{ $item->id }}" @selected(isset($user) && $user->adm_sesh == $item->id)>
                                        {{ $item->session_name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card border-danger border-top border-2 border-0">
                    <div class="card-header card-header-light-bg">
                        <h6 class="mb-0 card-title text-dark fw-bold">
                            <i class="bx bx-user fw-bold"></i> Personal Details:
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="name" class="form-label">Student Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ (isset($user) && isset($user->name))?$user->name: '' }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="father_name" class="form-label">Father Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="{{ isset($user) && $user->father_name ? $user->father_name : '' }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="mother_name" class="form-label">Mother Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{ isset($user) && $user->mother_name ? $user->mother_name : '' }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="gender" class="form-label">Gender<span class="text-danger">*</span>
                                </label>
                                <select name="gender" class=" form-select form-control" id="gender" required>
                                    <option value="">Select Gender</option>
                                    @foreach ($gender as $item)
                                    <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->gender == $item->gen_code)>
                                        {{ $item->description }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="dob" class="form-label">Date Of Birth<span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" name="dob" id="dob" value="{{ isset($user) && $user->dob ? $user->dob : '' }}" required>
                            </div>
                            <div class="col-md-12 col-12 mb-3">
                                <label for="address" class="form-label">Address<span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" name="address" id="address" required>{{ isset($user) && $user->address ? $user->address : '' }}</textarea>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0" class="form-control" value="{{ isset($user) && $user->pincode ? $user->pincode : '' }}" name="pincode" id="pincode" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="state" class="form-label">State<span class="text-danger">*</span>
                                </label>
                                <select name="state" class=" form-select form-control" id="state" required>
                                    <option value="">Select State</option>
                                    @foreach ($states as $item)
                                    <option value="{{ $item->state_code }}" {{ (isset($user->state) && $user->state == $item->state_code)?'selected':'' }}>
                                        {{ $item->state_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="email" class="form-label">Email Address<span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" id="email" value="{{ (isset($user) && isset($user->email))?$user->email: '' }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="contact" class="form-label">Contact Number<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="contact" id="contact" value="{{ (isset($user) && isset($user->contact_number))?$user->contact_number: '' }}" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="category" class="form-label">Category<span class="text-danger">*</span>
                                </label>
                                <select name="category" class=" form-select form-control" id="category" required>
                                    <option value="">Select Category</option>
                                    @foreach ($category as $item)
                                    <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->category == $item->gen_code)>
                                        {{ $item->description }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="aadhar_number" class="form-label">Aadhar Card Number<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="aadhar_number" value="{{ isset($user) && $user->aadhar_number ? $user->aadhar_number : '' }}" id="aadhar_number" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-danger border-top border-2 border-0">
                    <div class="card-header card-header-light-bg">
                        <h6 class="mb-0 card-title text-dark fw-bold">
                            <i class="bx bx-file fw-bold"></i> Academic Details:
                        </h6>
                    </div>


                    <div class="card-body">
                        <table class=" table table-bordered radius-10">
                            <tbody>
                                <tr>
                                    <th colspan="3" class="bg-dark text-white">
                                        10th <span class=" text-danger">*</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td width="33%">
                                        <label for="" class="form-label">Passing Year</label>
                                        <input type="number" name="ten_year" id="ten_year" class=" form-control" value="{{ isset($user->ac_ten_year)?$user->ac_ten_year:'' }}" maxlength="4" minlength="4" placeholder="Ex. 2022" required />
                                    </td>
                                    <td width="33%">
                                        <label for="" class="form-label">Subject</label>
                                        <input type="text" name="ten_subj" id="ten_subj" class=" form-control" value="{{ isset($user->ac_ten_subj)?$user->ac_ten_subj:'' }}" required />
                                    </td>

                                    <td width="33%">
                                        <label for="" class="form-label">Board/University</label>
                                        <input type="text" name="ten_board_uni" id="ten_board_uni" class="form-control" value="{{ (isset($user) && $user->ac_ten_board)?$user->ac_ten_board:'' }}" />

                                        <!-- <select name="ten_board_uni" id="ten_board_uni" class=" form-control" required>
                                            <option value="">Select</option>
                                            @foreach ($board as $item)
                                            <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->ac_ten_board == $item->gen_code)>
                                                {{ $item->description }}
                                            </option>
                                            @endforeach
                                        </select> -->
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <label for="" class="form-label">Name of Board/University</label>
                                        <input type="text" class="form-control" name="ten_board_name" id="ten_board_name" placeholder="Ex. Delhi University" value="{{ isset($user) && $user->ac_ten_board_name ? $user->ac_ten_board_name : '' }}" required>
                                    </td>

                                    <td>
                                        <label for="" class="form-label">Marksheet/Certificate</label>
                                        <input type="file" class="form-control" name="ten_marksheet" id="ten_marksheet" {{ isset($user) && !empty($user->ac_ten_sheet) ? '' : 'required' }} accept=".jpg, .jpeg, .pdf">

                                        @if (isset($user->ac_ten_sheet) && !empty($user->ac_ten_sheet))
                                        <p class="mb-0 mt-1">
                                            <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_METRIC_MARKSHEET_VIEW_PATH'].'/' . $user->ac_ten_sheet) }}" class="text-danger fw-bold" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
                                        </p>
                                        @endif
                                    </td>
                                </tr>

                            </tbody>

                            <tbody>
                                <tr>
                                    <th colspan="3" class="bg-dark text-white">
                                        12th <span class=" text-danger">*</span>
                                    </th>
                                </tr>
                                <tr>

                                    <td>
                                        <label for="" class="form-label">Passing Year</label>
                                        <input type="number" name="twelve_year" id="twelve_year" class=" form-control" value="{{ isset($user->ac_twelve_year)?$user->ac_twelve_year:'' }}" maxlength="4" minlength="4" placeholder="Ex. 2022" required />
                                    </td>
                                    <td>
                                        <label for="" class="form-label">Subject</label>
                                        <input type="text" name="twelve_subj" id="twelve_subj" class=" form-control" value="{{ isset($user->ac_twelve_subj)?$user->ac_twelve_subj:'' }}" required />
                                    </td>
                                    <td>
                                        <label for="" class="form-label">Board/University</label>
                                        <input type="text" name="twelve_board_uni" id="twelve_board_uni" class="form-control" value="{{ (isset($user) && $user->ac_twelve_board)?$user->ac_twelve_board:'' }}" />

                                        <!-- <select name="twelve_board_uni" id="twelve_board_uni" class=" form-control" required>
                                            <option value="">Select</option>
                                            @foreach ($board as $item)
                                            <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->ac_twelve_board == $item->gen_code)>
                                                {{ $item->description }}
                                            </option>
                                            @endforeach
                                        </select> -->
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <label for="" class="form-label">Name of Board/University</label>
                                        <input type="text" class="form-control" name="twelve_board_name" id="twelve_board_name" placeholder="Ex. Delhi University" value="{{ isset($user) && $user->ac_twelve_board_name ? $user->ac_twelve_board_name : '' }}" required>

                                    </td>
                                    <td>
                                        <label for="" class="form-label">Marksheet/Certificate</label>
                                        <input type="file" class="form-control" name="twelve_marksheet" id="twelve_marksheet" {{ isset($user) && !empty($user->ac_twelve_sheet) ? '' : 'required' }} accept=".jpg, .jpeg, .pdf">

                                        @if (isset($user->ac_twelve_sheet) && !empty($user->ac_twelve_sheet))
                                        <p class="mb-0 mt-1">
                                            <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_TWELVE_MARKSHEET_VIEW_PATH'].'/' . $user->ac_twelve_sheet) }}" class=" text-danger fw-bold" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <th colspan="3" class="bg-dark text-white">
                                        Other
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="" class="form-label">Passing Year</label>
                                        <input type="number" name="other_year" id="other_year" class=" form-control" value="{{ isset($user->ac_other_year)?$user->ac_other_year:'' }}" maxlength="4" minlength="4" placeholder="Ex. 2022" />
                                    </td>
                                    <td>
                                        <label for="" class="form-label">Subject</label>
                                        <input type="text" name="other_subj" id="other_subj" class=" form-control" value="{{ isset($user->ac_other_subj)?$user->ac_other_subj:'' }}" />
                                    </td>
                                    <td>

                                        <label for="" class="form-label">Board/University</label>
                                        <input type="text" name="other_board_uni" id="other_board_uni" class="form-control" value="{{ (isset($user) && $user->ac_other_board)?$user->ac_other_board:'' }}" />

                                        <!-- <select name="other_board_uni" id="other_board_uni" class=" form-control">
                                            <option value="">Select</option>
                                            @foreach ($board as $item)
                                            <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->ac_other_board == $item->gen_code)>
                                                {{ $item->description }}
                                            </option>
                                            @endforeach
                                        </select> -->
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <label for="" class="form-label">Name of Board/University</label>
                                        <input type="text" class="form-control" name="other_board_name" id="other_board_name" value="{{ isset($user) && $user->ac_other_board_name ? $user->ac_other_board_name : '' }}" placeholder="Ex. Delhi University">

                                    </td>
                                    <td>
                                        <label for="" class="form-label">Marksheet/Certificate</label>
                                        <input type="file" class="form-control" name="other_marksheet" id="other_marksheet" accept=".jpg, .jpeg, .pdf">

                                        <small class="text-danger">
                                            Marksheet is required if you want to fill any other certificate/degree details
                                        </small>

                                        @if (isset($user->ac_other_sheet) && !empty($user->ac_other_sheet))
                                        <p class="mb-0 mt-1">
                                            <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_OTHER_MARKSHEET_VIEW_PATH'].'/' . $user->ac_other_sheet) }}" class="text-danger fw-bold" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card border-danger border-top border-2 border-0">
                    <div class="card-header card-header-light-bg">
                        <h6 class="mb-0 card-title text-dark fw-bold">
                            <i class="bx bx-file fw-bold"></i> Document submit options
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="photo" class="form-label">Photo<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control file" name="photo" id="photo" {{ isset($user) && !empty($user->photo) ? '' : 'required' }} accept=".jpg, .jpeg, .png">

                                @if (isset($user) && !empty($user->photo))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="img-thumbnail w-100" />
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="aadhar" class="form-label">Aadhar<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control file" name="aadhar" id="aadhar" {{ isset($user) && !empty($user->aadhar) ? '' : 'required' }} accept=".jpg, .jpeg, .pdf">

                                @if (isset($user) && !empty($user->aadhar))
                                <div class="mt-2">
                                    <object data="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $user->aadhar) }}" class="img-thumbnail w-100" style="height: 280px;"></object>
                                </div>

                                <div class="mt-2 text-center">
                                    <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $user->aadhar) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                        <i class='bx bx-download'></i> Download
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button class=" btn btn-inverse-dark"> <i class=" bx bx-reset"></i> Reset</button>
                    <button class=" btn btn-danger"> <i class=" bx bx-save"></i> Submit</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('pages-scripts')

@if(isset($user) && !empty($user->id))
<script>
    var formUrl = base_url + '/graduation/update';
    var confirmMassage = "Do you want to edit record  and more time?";
</script>
@endif

<script>
    function fetchAndLoadAdmissionSesionsData(course_id, academic_year_id, institute_id) {
        $.ajax({
            url: base_url + '/ajax/admission-sessions-setup/get-using-course-insitute-academic-year',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                course_id: course_id,
                academic_year_id: academic_year_id,
                institute_id: institute_id
            },
            success: function(response) {
                if (response.status == true) {

                    let admissionSessions = response.data;

                    // Course Language Subjects
                    if (admissionSessions.length > 0) {
                        let admissionSessionOptions = '<option value="">Select</option>';
                        admissionSessions.forEach((as) => {
                            admissionSessionOptions += `<option value="${as.id}">${as.session_name}</option>`;
                        })

                        $('#admission_session').html(admissionSessionOptions);
                    } else {
                        $('#admission_session').html('<option value="">Select</option>');
                        toastr.error('No Admission Session found for the selected Academic Year, Insitute and Course');
                    }

                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(error) {
                toastr.error('Something went wrong. Please try again.')
            }
        });
    }

    function getAdmissionSesionsData() {
        let course_id = $('#course').val();
        let academic_year_id = $('#academic_year').val();
        let institute_id = $('#institute_id').val();

        if (academic_year_id != '' && institute_id != '' && course_id != '') {
            fetchAndLoadAdmissionSesionsData(course_id, academic_year_id, institute_id);
        } else {
            $('#admission_session').html('<option value="">Select</option>');
        }
    }

    $(document).ready(function() {
        $("#studentForm").validate({
            errorClass: "text-danger validation-error",
            // rules: {
            //     email_id: {
            //         required: true
            //     },
            // },
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('studentForm'))
                $(".loader").show();

                let operationType = $('#operation_type').val();
                let formUrl = base_url + '/graduation/store';

                // If EDIT, then override the value
                if (operationType == 'EDIT') {
                    formUrl = base_url + '/graduation/update';
                }

                $.ajax({
                    url: formUrl,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $(".loader").hide();
                        var data = response;
                        if (data.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                html: data.message,
                            }).then((result) => {
                                window.location.href = data.redirect_url;
                            });
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
                        $(".loader").hide();
                        toastr.error('Server error. Please try again.')
                    }
                })
            }
        });
    });

    function resetForm() {
        document.getElementById("studentForm").reset();
        $("#studentForm").validate().resetForm();
    }

    // ============================================
    /**
     * On change in course, get the course subjects and non language subjects
     */
    $(document).on('change', '#course', function() {
        // Get the admission sessions
        getAdmissionSesionsData()
    })

    // ============================================
    /**
     * On change academic year
     */
    $(document).on('change', '#academic_year', function() {
        // Get the admission sessions
        getAdmissionSesionsData()
    });

    // ============================================
    /**
     * On change institute id
     */
    $(document).on('change', '#institute_id', function() {
        // Get the admission sessions
        getAdmissionSesionsData()
    });
</script>
@endsection