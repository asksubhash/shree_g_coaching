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
        <div class="row">

            <div class="col-md-4 col-sm-12 col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Important Instructions</h5>
                        <ul>
                            <li>
                                Intrested Candidates are requested to register here first, then using the registered mail id or mobile no. they have to open the student login for further instructions.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-12 shadow-sm p-3">
                <form action="javascript:void(0)" class="form-style2" id="studentRegisterForm">
                    @csrf
                    <div class="form-inner">
                        <h3 class="form-title h5 mb-3 text-center fw-bold">Student Registration</h3>
                        <div class="">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-floating mb-3">
                                        <select class="form-control rounded-3" id="course" placeholder="Course" name="course">
                                            <option value="">Select</option>
                                            <option value="TEN">Vinod (10th Hindi equivalent)</option>
                                            <option value="TWELVE">Prabina (12th Hindi equivalent)</option>
                                            <option value="GRADUATION">Shastri (B.A. in Hindi equivalent)</option>
                                        </select>
                                        <label class="form-label" for="course">Course</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control rounded-3" id="f_name" placeholder="first name" name="f_name">
                                        <label class="form-label" for="f_name">First Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control rounded-3" id="l_name" placeholder="last name" name="l_name">
                                        <label class="form-label" for="l_name">Last Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 ">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control rounded-3" id="email" placeholder="Email" name="email">
                                        <label class="form-label" for="email">Email</label>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 ">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Phone Number">
                                        <label class="form-label" for="phone_number">Phone Number</label>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 ">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control rounded-3" id="password" placeholder="Password" name="password">
                                        <label class="form-label" for="password">Password</label>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 ">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control rounded-3" id="confirmPassword" placeholder="confirmPassword" name="confirmPassword">
                                        <label class="form-label" for="confirmPassword">Confirm Password</label>
                                    </div>
                                </div>

                                <div class="col-md-12 col-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="text-center mr-2 captchaSpan">
                                            {!! captcha_img('inverse') !!}
                                        </span>
                                        <button type="button" class="btn btn-refresh-captcha ml-1" id="reloadCaptcha">
                                            <span class="fa fa-sync"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="offset-md-3 col-md-6 col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control rounded-3" id="captcha" placeholder="Captcha" name="captcha">
                                        <label class="form-label" for="captcha">Captcha</label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="mb-2 btn btn-lg rounded-3 btn-custom" type="submit">
                                    <i class="fa fa-paper-plane"></i> Subhmit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="vs-circle color2"></div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pages-scripts')
<script>
    $(document).ready(function() {
        $("#studentRegisterForm").validate({
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

                var formData = new FormData(document.getElementById('studentRegisterForm'));

                // Send Ajax Request
                $.ajax({
                    url: base_url + '/student/store',
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
                                $('#studentRegisterForm').trigger('reset');
                                window.location.href = base_url +
                                    '/student/login';
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