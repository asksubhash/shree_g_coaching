<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.master_head')
    @yield('css')
</head>

<body>
    {{-- Inlcude the ajax loader --}}
    @include('includes.master_loader')

    <main class="wrapper">
        @yield('content')
    </main>

    {{-- Include the master scripts here --}}
    @include('includes.master_scripts')

    {{-- Include the pages scripts dynamically --}}
    @yield('pages-scripts')

    <script>
        // Start the loader
        $(document).ajaxStart(function() {
            $(".loader").show();
        })
        // Stop the loader
        $(document).ajaxStop(function() {
            $(".loader").hide();
        })

        $('.mobile_menu_btn').on('click', function(){
            $('.hp_menu_list').css({
                'top': 0
            })
        })

        $('.close_mobile_menu_btn').on('click', function(){
            $('.hp_menu_list').css({
                'top': '-100%'
            })
        })


        function reloadCaptcha(){
            $.ajax({
                type: 'GET',
                url: base_url + '/reload-captcha',
                success: function(data) {
                    $(".captchaSpan").html(data.captcha);
                }
            });
        }

        $('#reloadCaptcha').click(function() {
            reloadCaptcha();
        });
        
    </script>
    @yield('pages-scripts')
</body>

</html>