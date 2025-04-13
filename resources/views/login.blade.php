@extends('layouts.home_layout')

@section('content')

<div class="wrapper">
    <div class="section-authentication-cover">
        <div class="">
            <div class="row g-0">

                <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex p-0">

                    <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0 h-100">
                        <div class="card-body p-0">
                            <img src="{{asset('assets/images/school_banner.jpg')}}" class="img-fluid auth-img-cover-login w-100 h-100" alt="">
                        </div>
                    </div>

                </div>

                <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                    <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                        <div class="card-body p-sm-5">
                            <div class="">
                                <div class="mb-3 text-center">
                                    <img src="{{ config('app.logo') }}" width="60" alt="" />
                                </div>
                                <div class="text-center mb-4">
                                    <h5 class="">{{ config('app.name') }}</h5>
                                    <p class="mb-0">Please log in to your account</p>
                                </div>
                                <div class="form-body">
                                    <form action="" class="row g-3 auth_form login_form" id="login_form" autocomplete="off">
                                        @csrf
                                        <div class="col-12">
                                            <label for="email_id" class="form-label">Email ID</label>
                                            <input type="text" class="form-control" id="email_id" name="email_id" placeholder="info@example.com">
                                        </div>
                                        <div class="col-12" id="show_hide_password">
                                            <label for="password" class="form-label">Password</label>

                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                            <a href="javascript:;" class="showHide bg-transparent "><i class='bx bx-hide'></i></a>

                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="text-center mr-2 captchaSpan">
                                                    {!! captcha_img('inverse') !!}
                                                </span>
                                                <button type="button" class="btn btn-refresh-captcha ml-1" id="reloadCaptcha">
                                                    <span class="bx bx-revision"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="captcha" class="form-label">Captcha</label>
                                            <input type="text" class="form-control" id="captcha" name="captcha" />
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end"> <a href="authentication-forgot-password.html">Forgot
                                                Password ?</a>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-danger"><i class="bx bx-send"></i> Sign in</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end row-->
        </div>
    </div>
</div>

@endsection

@section('pages-scripts')
<script>
    $("#login_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            email_id: {
                required: true
            },
            password: {
                required: true
            },
            captcha: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('login_form'))
            $(".loader").show();
            $.ajax({
                url: base_url + '/check-login',
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