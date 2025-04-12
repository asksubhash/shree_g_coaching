<title>{{ config('app.name') }}</title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/x-icon" href="{{ config('app.logo') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">


<!-- Font Awesome Icons -->
{{-- <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}"> --}}

<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
{{-- <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" /> --}}
<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
<!-- loader-->
<!-- <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/js/pace.min.js') }}"></script> -->
<!-- Bootstrap CSS -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet"> -->

<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,600;1,700&family=Roboto&display=swap" rel="stylesheet"> -->

<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<script>
    var base_url = '{{ config("app.url") }}';
</script>