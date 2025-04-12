<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website_assets/css/style.css') }}">
    <!-- <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" /> -->

    <script>
        var base_url = '{{ config("app.url") }}'
    </script>
</head>

<body>
    @include('includes.master_loader')

    <!-- Top Navbar -->
    <nav class="site-navbar">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-12 nav-left-col">
                    <p class="nav-left-info text-capitalize">
                        Welcome to Odisha Rastrabhasa Parisad, Jagannath Dham, Puri
                    </p>
                </div>

                <div class="col-md-6 col-sm-12 col-12 nav-right-col">
                    <div class="nav-right-list-col">
                        <ul class="top-nav-list">
                            <li>
                                <a href="#">
                                    <i class="fa fa-envelope"></i> odisharastrabhasa@gmail.com
                                </a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-phone"></i> 91-7683882331</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <header class="site-header sticky-top bg-white shadow-sm">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                    <img src="{{ asset('website_assets/images/site-logo.png') }}" alt="Logo" class="site-logo">
                </a>

                <?php
                $menus = [
                    [
                        'name' => 'Home',
                        'url' => route('home'),
                        'icon' => 'fa fa-home',
                    ],
                    [
                        'name' => 'Student Zone',
                        'url' => '#',
                        'icon' => 'fa fa-user',
                        'submenu' => [
                            [
                                'name' => 'Apply Now',
                                'url' => route('student-application'),
                            ],
                            [
                                'name' => 'Student Login',
                                'url' => route('student-login'),
                            ],
                        ],
                    ],
                    [
                        'name' => 'Study Center',
                        'url' => '#',
                        'icon' => 'fa fa-user',
                        'submenu' => [
                            [
                                'name' => 'Study Center Online Registration',
                                'url' => url('study-center/registration'),
                            ],
                            [
                                'name' => 'Study Center List',
                                'url' => '#',
                            ],
                            [
                                'name' => 'Study Center Login',
                                'url' => url('study-center/login'),
                            ],
                        ],
                    ],
                    [
                        'name' => 'Result',
                        'url' => route('result'),
                        'icon' => 'fa fa-newspaper',
                    ],
                    [
                        'name' => 'About Us',
                        'url' => route('about'),
                        'icon' => 'fa fa-user',
                        'submenu' => [
                            [
                                'name' => 'Vision',
                                'url' => route('about'),
                            ],
                            [
                                'name' => 'Course Details',
                                'url' => route('find-courses'),
                            ],
                            [
                                'name' => 'Gallery',
                                'url' => route('gallery'),
                            ],
                            [
                                'name' => 'Contact Us',
                                'url' => route('contact-us'),
                            ],
                        ],
                    ],
                ];
                ?>

                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small h-menu">
                    @foreach ($menus as $menu)
                    <li class="position-relative">
                        <a href="{{ $menu['url'] }}" class="nav-link text-dark">
                            <span class="d-block text-center mb-0">
                                <i class="{{ $menu['icon'] }}"></i>
                            </span>
                            {{ $menu['name'] }}
                        </a>

                        @if (isset($menu['submenu']) && count($menu['submenu']) > 0)
                        <ul class="h-submenu position-absolute top-100 right-0 ps-0 mb-0 list-unstyled z-3 bg-white shadow rounded  d-none">
                            @foreach ($menu['submenu'] as $submenu)
                            <li>
                                <a href="{{ $submenu['url'] }}" class="nav-link text-dark">
                                    {{ $submenu['name'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
    </header>

    @yield('content')

    <footer class="bg-dark text-white site-footer py-1">
        <div class="container">
            <footer class="pt-5">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <img src="{{ asset('website_assets/images/site-logo.png') }}" alt="Logo" class="footer-logo mb-3">
                        <p class="mb-0">
                            Odisha Rastravasa Parisad, Jagannath Dham, Puri is an Voluntary Hindi Organisation and
                            Permanent Recognition by Ministry of Human Resource Development (MHRD), Govt. of India
                            (Regd. No. 7632/73-74 old Regd No 10 of 1954).
                        </p>
                    </div>

                    <div class="col-12 col-sm-6 col-md-2 mb-3">
                        <h5 class="mb-3">Quick Links</h5>
                        <ul class="nav flex-column">
                            @foreach ($menus as $menu)
                            <li class="nav-item mb-2">
                                <a href="{{ $menu['url'] }}" class="nav-link p-0 text-body-secondary">
                                    {{ $menu['name'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>


                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card text-bg-dark">
                            <img src="https://img.freepik.com/free-photo/card-soft-template-paper-report_1258-167.jpg?w=996&t=st=1698050585~exp=1698051185~hmac=dc650f88251e203480e889b1f1f53513333468a1426fa644af3404636500d57e" alt="" class="rounded">
                            <div class="card-img-overlay">
                                <h1 class="fs-2 fw-bold">Looking to study with us?</h1>
                                <p class="card-text">SPEAK TO AN ADVISER</p>
                                <h1 class="fs-3 fw-bold">
                                    +91-7683882331</small></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-12 mb-3">
                        <form>
                            <h5>Subscribe to our newsletter</h5>
                            <p>Monthly digest of what's new and exciting from us.</p>
                            <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                                <label for="newsletter1" class="visually-hidden">Email address</label>
                                <input id="newsletter1" type="text" class="form-control custom-form-control" placeholder="Email address">
                            </div>
                            <button class="btn btn-custom w-100 mt-1" type="button"><i class="fa fa-paper-plane"></i>
                                Subscribe</button>
                        </form>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center pt-4 pb-2 ">
                    <p class="mb-0">© 2023 {{ env('APP_NAME') }} All rights reserved.</p>
                    <ul class="list-unstyled d-flex">
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="#">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="#">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="#">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </footer>
        </div>
    </footer>

    <script src="{{ asset('plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('plugins/popper/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Bootstrap JS -->
    {{-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> --}}
    <!--plugins-->
    {{-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> --}}
    {{-- Jquery Validate --}}
    <script src="{{ asset('dist/js/jqueryValidate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        // -----------------------------------
        // Function to reload captcha
        function reloadCaptcha() {
            $.ajax({
                type: 'GET',
                url: base_url + '/reload-captcha',
                success: function(data) {
                    $(".captchaSpan").html(data.captcha);
                }
            });
        }

        // -----------------------------------
        // On click reload captcha
        $('#reloadCaptcha').click(function() {
            reloadCaptcha();
        });

        // -----------------------------------
        // Enquiry Form Submission
        $("#enquiryForm").validate({
            errorElement: "span",
            errorClass: "text-danger validation-error",
            rules: {
                enqName: {
                    required: true
                },
                enqEmailId: {
                    required: true,
                    email: true
                },
                enqPhoneNumber: {
                    required: true
                },
                enqMessage: {
                    required: true
                },
                enqCaptcha: {
                    required: true
                }
            },
            messages: {
                enqName: {
                    required: "Please enter your full name."
                },
                enqEmailId: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                enqPhoneNumber: {
                    required: "Please enter your phone number."
                },
                enqMessage: {
                    required: "Please enter your message."
                },
                enqCaptcha: {
                    required: "Please enter captcha to submit form."
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                // Add your form submission logic here
                // For example, you can use AJAX to submit the form data
                // e.g., $.post('submit.php', $(form).serialize(), function(response) { /* handle response */ });

                var formData = new FormData(document.getElementById('enquiryForm'));

                // Send Ajax Request
                $.ajax({
                    url: base_url + '/enquiry/store',
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
                                title: 'Submitted Successfully',
                                text: response.message,
                            })
                            $('#enquiryForm').trigger('reset');
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
    </script>

    @yield('pages-scripts')
</body>

</html>