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
        <div class="col-12">
            <div class="py-3">
                <form action="javascript:void(0)" id="studentForm" name="studentForm">
                    @csrf

                    @if (isset($user) && !empty($user->id))
                    <input type="hidden" name="hiddenId" value="{{ base64_encode($user->id) }}">
                    <input type="hidden" name="operation_type" id="operation_type" value="EDIT">
                    @else
                    <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                    @endif

                    <div class="card border-danger border-top border-2 border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label class="form-label">Medium of Instruction <span class="text-danger">*</span></label>
                                    <div class="radio_group d-block">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="medium_off_inst" id="hindi" value="HINDI" @checked(isset($user) && $user->medium_off_inst == 'HINDI')>
                                            <label class="form-check-label" for="hindi">Hindi</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="medium_off_inst" id="english" value="ENGLISH" @checked(isset($user) && $user->medium_off_inst == 'ENGLISH')>
                                            <label class="form-check-label" for="english">English</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="medium_off_inst" id="odia" value="ODIA" @checked(isset($user) && $user->medium_off_inst == 'ODIA')>
                                            <label class="form-check-label" for="odia">Odia</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                    {{-- Language / Non-Language / Details: --}}
                    <div class="card border-danger border-top border-2 border-0">
                        <div class="card-header card-header-light-bg">
                            <h6 class="mb-0 card-title text-dark fw-bold">
                                <i class="bx bx-file fw-bold"></i> Language / Non-Language
                                Details:
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="language_subject" class="form-label">Language Subjects<span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" name="language_subject[]" id="language_subject" multiple required>
                                        @if (isset($user) && !empty($user->id))

                                        @if (count($courseSubjects) > 0)
                                        @foreach($courseAllSubjects as $allSub)
                                        @foreach ($courseSubjects as $ssm)
                                        @if($allSub->subject_id == $ssm->subject_id)
                                        <option value="{{ $allSub->subject_id }}" selected>{{ $allSub->name . '('.$allSub->code.')' }}</option>
                                        @else
                                        <option value="{{ $allSub->subject_id }}">{{ $allSub->name . '('.$allSub->code.')' }}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif

                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="non_language_subject" class="form-label">Non Language Subjects<span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" name="non_language_subject[]" id="non_language_subject" multiple required>
                                        @if (isset($user) && !empty($user->id))

                                        @if (count($nonLanguageSubjects) > 0)

                                        @foreach($courseAllNLSubjects as $allSub)

                                        @foreach ($nonLanguageSubjects as $ssm)

                                        @if($allSub->subject_id == $ssm->subject_id)
                                        <option value="{{ $ssm->subject_id }}" selected>{{ $allSub->name . '('.$allSub->code.')' }}</option>
                                        @else
                                        <option value="{{ $allSub->subject_id }}">{{ $allSub->name . '('.$allSub->code.')' }}</option>
                                        @endif
                                        @endforeach

                                        @endforeach

                                        @endif

                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Personal Details --}}
                    <div class="card border-danger border-top border-2 border-0">
                        <div class="card-header card-header-light-bg">
                            <h6 class="mb-0 card-title text-dark fw-bold">
                                <i class="bx bx-user fw-bold"></i> Personal Details
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
                                    <label for="father_name" class="form-label">Father Name<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="father_name" id="father_name" value="{{ (isset($user) && isset($user->father_name))?$user->father_name: '' }}" required>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="mother_name" class="form-label">Mother Name<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{ (isset($user) && isset($user->mother_name))?$user->mother_name: '' }}" required>
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
                                    <label for="dob" class="form-label">DOB<span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="dob" id="dob" value="{{ isset($user) && $user->dob ? $user->dob : '' }}" required>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="religion" class="form-label">Religion<span class="text-danger">*</span>
                                    </label>
                                    <select name="religion" class=" form-select form-control" id="religion" required>
                                        <option value="">Select Religion</option>
                                        @foreach ($religion as $item)
                                        <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->religion == $item->gen_code)>
                                            {{ $item->description }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label">Address<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ isset($user) && $user->address ? $user->address : '' }}" required>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" class="form-control" name="pincode" value="{{ isset($user) && $user->pincode ? $user->pincode : '' }}" id="pincode" required>
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
                                    <input type="number" class="form-control" name="contact" id="contact" value="{{ (isset($user) && isset($user->contact_number))?$user->contact_number: '' }}" required>
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
                                    <input type="text" class="form-control" name="aadhar_number" id="aadhar_number" value="{{ isset($user) && $user->aadhar_number ? $user->aadhar_number : '' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-danger border-top border-2 border-0">
                        <div class="card-header card-header-light-bg">
                            <h6 class="mb-0 card-title text-dark fw-bold">
                                <i class="bx bx-book fw-bold"></i> Metric Qualification Details
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class=" table table-bordered">
                                <thead>
                                    <th>
                                        Subject
                                    </th>
                                    <th>
                                        Passing Year
                                    </th>
                                    <th>
                                        Board/
                                        University
                                    </th>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="met_subject" id="met_subject" value="{{ isset($user) && $user->met_subj ? $user->met_subj : 'All Subjects' }}" readonly required>
                                        </td>
                                        <td>
                                            <input type="number" name="met_year" id="met_year" class=" form-control" value="{{ isset($user->met_year)?$user->met_year:'' }}" maxlength="4" minlength="4" />
                                        </td>
                                        <td>
                                            <input type="text" name="met_board_university" id="met_board_university" class="form-control" value="{{ (isset($user) && $user->met_board)?$user->met_board:'' }}" />
                                            <!-- <select name="met_board_university" id="met_board_university" class=" form-control" required>
                                                <option value="">Select</option>
                                                @foreach ($board as $item)
                                                <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->met_board == $item->gen_code)>
                                                    {{ $item->description }}
                                                </option>
                                                @endforeach
                                            </select> -->
                                        </td>

                                    </tr>

                                    <tr>
                                        <th>
                                            Max
                                            Marks
                                        </th>
                                        <th>
                                            Obtained
                                            Marks
                                        </th>

                                        <th>
                                            Percentage/
                                            Grade
                                        </th>
                                        <th>
                                            Marksheet
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="met_max_mark" id="met_max_mark" value="{{ isset($user) && $user->met_max_mark ? $user->met_max_mark : '' }}" maxlength="3" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="met_ob_mark" id="met_ob_mark" value="{{ isset($user) && $user->met_ob_mark ? $user->met_ob_mark : '' }}" maxlength="3" required>
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" name="met_percentage" id="met_percentage" value="{{ isset($user) && $user->met_max_per ? $user->met_max_per : '' }}" readonly required>
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" name="met_marksheet" id="met_marksheet" {{ isset($user) && !empty($user->met_mrk_sheet) ? '' : 'required' }} accept=".jpg, .jpeg, .pdf">

                                            @if(isset($user->met_mrk_sheet) && !empty($user->met_mrk_sheet))
                                            <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_METRIC_MARKSHEET_VIEW_PATH'].'/' . $user->met_mrk_sheet) }}" class=" btn text-danger fw-bold btn-sm" target="_BLANK"><i class='bx bx-download'></i> View/Download</a>
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
                                <i class="bx bx-file fw-bold"></i>Document submit options
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="photo" class="form-label">Photo<span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control file" name="photo" id="photo" {{ isset($user) && !empty($user->photo) ? '' : 'required' }} accept=".jpg, .jpeg, .png">

                                    @if (isset($user->photo) && !empty($user->photo))
                                    <div class="mt-3">
                                        <div>
                                            <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" alt="Image" class="img-thumbnail w-100" />
                                        </div>

                                        <div class="mt-2 text-center">
                                            <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'].'/' . $user->photo) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                                <i class='bx bx-download'></i> Download
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="aadhar" class="form-label">Aadhar<span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control file" name="aadhar" id="aadhar" {{ isset($user) && !empty($user->aadhar) ? '' : 'required' }} accept=".jpg, .jpeg, .pdf">

                                    @if (isset($user->aadhar) && !empty($user->aadhar))
                                    <div class="mt-3">
                                        <div>
                                            <object data="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $user->aadhar) }}" class="img-thumbnail w-100" style="height: 280px;"></object>
                                        </div>

                                        <div class="mt-2 text-center">
                                            <a href="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'].'/' . $user->aadhar) }}" class=" btn btn-danger btn-sm" target="_BLANK">
                                                <i class='bx bx-download'></i> Download
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class=" btn btn-inverse-dark" type="button" onclick="resetForm()"> <i class=" bx bx-reset"></i>
                            Reset</button>
                        <button class=" btn btn-danger" type="submit"> <i class=" bx bx-save"></i>
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('pages-scripts')
@if(isset($user) && !empty($user->id))
<script>
    var formUrl = base_url + '/inter/update';
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

    $('#met_ob_mark').on('keyup', function() {
        let met_ob_mark = parseInt($(this).val());
        let met_max_mark = parseInt($('#met_max_mark').val());
        console.log(met_ob_mark)
        console.log(met_max_mark)

        console.log(met_ob_mark < met_max_mark)

        if (met_ob_mark && met_max_mark) {
            if (met_max_mark < met_ob_mark) {
                $('#met_ob_mark').val('');
                toastr.error('Obtained marks should not be greater than max marks')
            }

            if (met_max_mark && met_ob_mark) {
                let percentage = (met_ob_mark / met_max_mark) * 100
                $('#met_percentage').val(Math.round(percentage));
            }
        } else {
            $('#met_ob_mark').val('');
        }
    })

    $('#met_max_mark').on('keyup', function() {
        let met_max_mark = parseInt((this).val());
        let met_ob_mark = parseInt(('#met_ob_mark').val());

        if (met_max_mark && met_ob_mark) {
            if (met_max_mark < met_ob_mark) {
                $('#met_ob_mark').val('');
                toastr.error('Obtained marks should not be greater than max marks')
            }

            if (met_max_mark && met_ob_mark) {
                let percentage = (met_ob_mark / met_max_mark) * 100
                $('#met_percentage').val(Math.round(percentage));
            }
        } else {
            $('#met_ob_mark').val('');
        }
    })

    $('#language_subject').on('select2:select', function(e) {
        var selectedOptions = $(this).val() || [];
        if (selectedOptions.length > 4) {
            // Remove the last selected option if the limit is exceeded
            $(this).val(selectedOptions.slice(0, 4)).trigger('change.select2');
            toastr.error('You can select max 4 subjects.')
        }
    });

    $('#non_language_subject').on('select2:select', function(e) {
        var selectedOptions = $(this).val() || [];
        if (selectedOptions.length > 4) {
            // Remove the last selected option if the limit is exceeded
            $(this).val(selectedOptions.slice(0, 4)).trigger('change.select2');
            toastr.error('You can select max 4 subjects.')
        }
    });

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
                let formUrl = base_url + '/inter/store';

                // If EDIT, then override the value
                if (operationType == 'EDIT') {
                    formUrl = base_url + '/inter/update';
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
        let course_id = $(this).val();

        if (course_id) {
            $.ajax({
                url: base_url + '/ajax/course/get/subjects-and-nl-subjects',
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    course_id: course_id
                },
                success: function(response) {
                    if (response.status == true) {

                        let data = response.data;
                        let courseSubjects = data.courseSubjects;
                        let courseNLSubjects = data.courseNLSubjects;

                        // Course Language Subjects
                        if (courseSubjects.length > 0) {
                            let language_subject = '';
                            courseSubjects.forEach((subject) => {
                                language_subject += `<option value="${subject.id}">${subject.name}</option>`;
                            })

                            $('#language_subject').html(language_subject);
                            $('#language_subject').select2();
                        }

                        // Course Non Language Subjects
                        if (courseNLSubjects.length > 0) {
                            let non_language_subject = '';
                            courseNLSubjects.forEach((subject) => {
                                non_language_subject += `<option value="${subject.id}">${subject.name}</option>`;
                            })
                            $('#non_language_subject').html(non_language_subject);
                            $('#non_language_subject').select2();
                        }

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