@extends('layouts.website_layout')

@section('content')
<div class="bg-body-tertiary">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
                <li class="breadcrumb-item">
                    <a class="link-body-emphasis" href="#">
                        <i class="fa fa-home"></i>
                        <span class="visually-hidden">Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Student Register
                </li>
            </ol>
        </nav>
    </div>
</div>
<section class="pt-3 pb-4">
    <div class="container">
        <h3 class="form-title h5 mb-3 text-center fw-bold">Student Application</h3>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5 class="card-title text-danger">Important Instructions</h5>
                <ul>
                    <li>
                        Intrested Candidates are requested to apply here first, then study centre will revert you for further details.
                    </li>
                    <li>
                        Student will get their credentials after admission approval.
                    </li>
                    <li>
                        Please fill each and every details carefully as per the documents.
                    </li>
                </ul>
            </div>
        </div>

        <form action="javascript:void(0)" class="form-style2" id="studentApplicationForm">
            @csrf

            {{-- Personal Details --}}
            <div class="col-12">
                <div class="card shadow-sm mb-3 border-danger border-top border-2 border-0">
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
                                <input type="text" class="form-control" name="name" id="name" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="father_name" class="form-label">Father Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="mother_name" class="form-label">Mother Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="" required>
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
                                <input type="date" class="form-control" name="dob" id="dob" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="religion" class="form-label">Religion<span class="text-danger">*</span>
                                </label>
                                <select name="religion" class=" form-select form-control" id="religion" required>
                                    <option value="">Select Religion</option>
                                    @foreach ($religion as $item)
                                    <option value="{{ $item->gen_code }}">
                                        {{ $item->description }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address<span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" name="address" id="address" required></textarea>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0" class="form-control" name="pincode" value="" id="pincode" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="state" class="form-label">State<span class="text-danger">*</span>
                                </label>
                                <select name="state" class=" form-select form-control" id="state" required>
                                    <option value="">Select State</option>
                                    @foreach ($states as $item)
                                    <option value="{{ $item->state_code }}">
                                        {{ $item->state_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="email" class="form-label">Email Address<span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" id="email" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="contact" class="form-label">Contact Number<span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" name="contact" id="contact" value="" minlength="10" maxlength="10" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="category" class="form-label">Category<span class="text-danger">*</span>
                                </label>
                                <select name="category" class=" form-select form-control" id="category" required>
                                    <option value="">Select Category</option>
                                    @foreach ($category as $item)
                                    <option value="{{ $item->gen_code }}">
                                        {{ $item->description }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="aadhar_number" class="form-label">Aadhar Card Number<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="aadhar_number" id="aadhar_number" value="" minlength="12" maxlength="12" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-danger border-top border-2 border-0">
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
                                <input type="file" class="form-control file" name="photo" id="photo" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="aadhar" class="form-label">Aadhar<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control file" name="aadhar" id="aadhar" accept=".jpg, .jpeg, .pdf">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <button class=" btn btn-custom" type="submit">
                    <i class="fa fa-paper-plane"></i> Apply
                </button>
            </div>
        </form>

    </div>
</section>
@endsection

@section('pages-scripts')
<script>
    $(document).ready(function() {
        $("#studentApplicationForm").validate({
            errorElement: "span",
            errorClass: "text-danger validation-error",
            rules: {
                f_name: {
                    required: true
                },
                l_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                confirmPassword: {
                    required: true,
                    equalTo: "#password"
                },
                captcha: {
                    required: true
                },
            },
            messages: {
                f_name: {
                    required: "Please enter your first name."
                },
                l_name: {
                    required: "Please enter your last name."
                },

                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                phone_number: {
                    required: "Please enter your phone number."
                },
                password: {
                    required: "Please enter your password."
                },
                confirmPassword: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                },
                captcha: {
                    required: "Please enter captcha to submit form."
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                // Add your form submission logic here
                // For example, you can use AJAX to submit the form data
                // e.g., $.post('submit.php', $(form).serialize(), function(response) { /* handle response */ });

                var formData = new FormData(document.getElementById('studentApplicationForm'));

                // Send Ajax Request
                $.ajax({
                    url: base_url + '/student/application/store',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        reloadCaptcha();
                        if (response.status == true) {
                            // toastr.success(response.message);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                window.location.reload();
                            });

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
                        reloadCaptcha()
                        toastr.error('Something went wrong. Please try again.')
                    }
                });
            }
        });
    });
</script>
@endsection