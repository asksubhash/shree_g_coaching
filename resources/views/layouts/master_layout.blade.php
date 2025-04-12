<!DOCTYPE html>
<html lang="en" class="color-sidebar sidebarcolor2">

<head>
    @include('includes.master_head')
    @yield('css')
</head>

<body>
    {{-- Inlcude the ajax loader --}}
    @include('includes.master_loader')

    <div class="wrapper">
        <!-- Navbar -->
        @include('includes.admin_link')
        <!-- /.navbar -->

        <!--start header -->
        @include('includes.admin_header')
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            @yield('content')
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
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
                        <p class="mb-1">Pages</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Department Setup</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>User Setup</a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search modal -->
    {{-- Include the master scripts here --}}
    @include('includes.master_scripts')

    <script>
        $('.select2').select2();
    </script>


    {{-- Include the pages scripts dynamically --}}
    @yield('pages-scripts')

    {{-- ================================================================ --}}
    {{-- This should be at the last, because it is initializing the pop overs including which are coming through server
    side datatable --}}
    <script>
        // Hide the popover when clicking anywhere in the body
        $('body').on('click', function(e) {
            // Check if the clicked element is inside a popover or a popover trigger element
            if ($(e.target).data('toggle') !== 'popover' && !$(e.target).parents().is('.popover')) {
                $('[data-toggle="popover"]').popover('hide');
            }
        });
    </script>

    <script>

    </script>
</body>

</html>