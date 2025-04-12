@extends('layouts.website_layout')

@section('content')
<!--==============================Breadcumb============================== -->
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
                    Student Login
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--============================== Contact Area ==============================-->
<section class="pt-3 pb-4">
    <div class="container">
        <div class="row">

            <div class="col-md-4 offset-md-4 offset-sm-3 col-sm-6 col-12 shadow-sm p-3">
                <form action="javascript:void(0)" class="form-style2" id="studentLoginForm">
                    @csrf
                    <div class="form-inner">
                        <h3 class="form-title h5 mb-3 text-center fw-bold">Student Login</h3>
                        <div class="">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="username" placeholder="Username" name="username">
                                <label for="username">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-3" id="password" placeholder="Password" name="password">
                                <label for="password">Password</label>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="text-center mr-2 captchaSpan">
                                        {!! captcha_img('inverse') !!}
                                    </span>
                                    <button type="button" class="btn btn-refresh-captcha ml-1" id="reloadCaptcha">
                                        <span class="fa fa-sync"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="floatingCaptcha" placeholder="Captcha" name="captcha">
                                <label for="floatingCaptcha">Captcha</label>
                            </div>

                            <div class="text-center">
                                <button class="mb-2 btn btn-lg rounded-3 btn-custom" type="submit">
                                    <i class="fa fa-paper-plane"></i> Login
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
    $("#studentLoginForm").validate({
        errorElement: "span",
        errorClass: "text-danger validation-error",
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            },
            captcha: {
                required: true
            },
        },
        messages: {
            username: {
                required: "Please enter your username."
            },
            password: {
                required: "Please enter your password."
            },
            captcha: {
                required: "Please enter captcha to submit form."
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('studentLoginForm'))
            $(".loader").show();
            $.ajax({
                url: base_url + '/student/check-login',
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    $(".loader").hide();
                    reloadCaptcha();

                    var data = response;

                    if (data.status == true) {
                        toastr.success(data.message);
                        window.location.href = data.redirect_to;
                    } else if (data.status == 'validation_error') {
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
                    reloadCaptcha();
                }
            })
        }
    });
</script>
@endsection