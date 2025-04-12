<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ config('app.logo') }}">


    <!--plugins-->
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" rel="stylesheet" />
    @yield('css')
    <script>
        var base_url = '{{ config("app.url") }}';
    </script>
</head>

<body>
    <div class="loader">
        <div class="loader-col">
            <img src="{{ asset('dist/img/loader.svg') }}" alt="">
        </div>
    </div>
    <div class="wrapper">

        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <h4 class="logo-text">{{ config('app.name') }}</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                @if (Auth::user()->role->role_name == 'SUPERADMIN')
                <li>
                    <a href="{{ url()->to('superadmin/dashboard') }}" class="{{ MenuHelper::setMenuActive('superadmin/dashboard') }}">
                        <div class="parent-icon"><i class='bx bx-home-alt'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                <li>
                    <a href="{{ url()->to('all-resource') }}" class="{{ MenuHelper::setMenuActive('all-resource') }}">
                        <div class="parent-icon"><i class='bx bx-link'></i>
                        </div>
                        <div class="menu-title">Resource Setup</div>
                    </a>
                </li>

                <li>
                    <a href="javascript:;" class="has-arrow {{ MenuHelper::setMenuOpenWithArrayLinks([request()->is('all-users'), request()->is('all-roles')]) }}">
                        <div class="parent-icon"><i class="bx bx-category"></i>
                        </div>
                        <div class="menu-title">Authentication Setup</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ url()->to('superadmin-all-users') }}" class="{{ MenuHelper::setMenuActive('superadmin-all-users') }}"><i class='bx bx-radio-circle'></i>Users</a>
                        </li>

                        <li>
                            <a href="{{ url()->to('all-roles') }}" class="{{ MenuHelper::setMenuActive('all-roles') }}"><i class='bx bx-radio-circle'></i>Role Setup</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="has-arrow {{ MenuHelper::setMenuOpenWithArrayLinks([request()->is('all-menus'), request()->is('all-sub-menus'), request()->is('all-resource')]) }}">
                        <div class="parent-icon"><i class="bx bx-category"></i>
                        </div>
                        <div class="menu-title">Menu Setup</div>
                    </a>
                    <ul>

                        <li>
                            <a href="{{ url()->to('all-menus') }}" class="{{ MenuHelper::setMenuActive('all-menus') }}">
                                <i class='bx bx-radio-circle'></i>Main Menu Setup</a>
                        </li>

                        <li>
                            <a href="{{ url()->to('all-sub-menus') }}" class="{{ MenuHelper::setMenuActive('all-sub-menus') }}"><i class='bx bx-radio-circle'></i>Sub Menu Setup</a>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="{{ url()->to('all-login-details') }}" class="{{ MenuHelper::setMenuActive('all-login-details') }}">
                        <div class="parent-icon"><i class='bx bx-user'></i>
                        </div>
                        <div class="menu-title">Login Details</div>
                    </a>
                </li>
                @endif

            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->

        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>

                    <div class="search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                        <a href="avascript:;" class="btn d-flex align-items-center"><i class='bx bx-search'></i>Search</a>
                    </div>

                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                                <a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
                                </a>
                            </li>
                            <li class="nav-item dark-mode d-none d-sm-flex">
                                <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets/images/avatars/avatar-2.png') }}" class="user-img" alt="user avatar">
                            <div class="user-info">
                                <p class="user-name mb-0">
                                    {{ auth()->user()->userDetail->f_name . '' . auth()->user()->userDetail->l_name }}
                                </p>
                                <p class="designattion mb-0">({{ Auth::user()->role->role_name }})</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ url()->to('superadmin-profile') }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--start page wrapper -->
        <div class="page-wrapper">
            @yield('content')
        </div>
        <!--end page wrapper -->
        <!-- Main Footer -->
        <footer class="page-footer">
            <p class="mb-0"> Copyright &copy;
                <?= date('Y') ?> <strong><a href="#">{{ config('app.name') }}</a>.</strong> All rights
            </p>
        </footer>
    </div>
    <!-- ./wrapper -->


    <!-- search modal -->
    <div class="modal" id="SearchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header gap-2">
                    <div class="position-relative popup-search w-100">
                        <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search">
                        <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
                    </div>
                    <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search-list">
                        <p class="mb-1">Html Templates</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-magento fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-shopify fs-4'></i>eCommerce Html Templates</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search modal -->
    <!-- REQUIRED SCRIPTS -->

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>



    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    {{-- <script type="text/javascript"
        src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js">
    </script> --}}
    {{-- Sweetalert --}}
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/axios/axios.min.js') }}"></script>
    <script src="{{ asset('dist/js/jqueryValidate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <!--notification js -->
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/notifications.min.js') }}"></script>
    <script>
        // $('.text_editor').summernote({
        //     height: 200
        // });

        // $('.select2').select2({
        //     placeholder: 'select',
        //     allowClear: true
        // })

        // Start the loader
        $(document).ajaxStart(function() {
            $(".loader").show();
        })
        // Stop the loader
        $(document).ajaxStop(function() {
            $(".loader").hide();
        })
    </script>
    @yield('pages-scripts')
    <script>
        // Hide the popover when clicking anywhere in the body
        $('body').on('click', function(e) {
            // Check if the clicked element is inside a popover or a popover trigger element
            if ($(e.target).data('toggle') !== 'popover' && !$(e.target).parents().is('.popover')) {
                $('[data-toggle="popover"]').popover('hide');
            }
        });
    </script>
</body>

</html>