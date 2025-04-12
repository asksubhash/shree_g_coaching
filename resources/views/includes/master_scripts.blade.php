{{-- THIS IS THE MASTER SCRIPTS INCLUDES IN THE APPLICATION --}}

<!-- REQUIRED SCRIPTS -->
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
{{-- <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script> --}}

{{-- alert  --}}
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

{{-- Datatable --}}
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
{{-- Axios --}}
<script src="{{ asset('plugins/axios/axios.min.js') }}"></script>
<script src="{{ asset('dist/js/jqueryValidate.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/plugins/select2/js/select2-custom.js') }}"></script>

<!-- CKeditor -->
<script src="{{ asset('plugins/ckeditor/build/ckeditor.js') }}"></script>

<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/functions.js') }}"></script>
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

    // ======================================
    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>