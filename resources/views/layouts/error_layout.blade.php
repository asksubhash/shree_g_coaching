<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.master_head')
    @yield('css')
</head>

<body class="hold-transition sidebar-mini bg-dark">
    <div class="wrapper">
        @yield('content')
    </div>

    {{-- Include the master scripts here --}}
    @include('includes.master_scripts')

    {{-- Include the pages scripts dynamically --}}
    @yield('pages-scripts')
</body>

</html>