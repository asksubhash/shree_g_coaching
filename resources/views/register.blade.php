@extends('layouts.home_layout')

@section('content')
    <section class="hp_banner">
        <div class="container">
            <div class="login_col">
                <h2 class="login_title">Register Here</h2>
                <form action="" class="auth_form register_form" id="register_form">
                    <div class="mb-3">
                        <label for="">Name:</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Email ID:</label>
                        <input type="text" name="email_id" id="email_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Password:</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Confirm Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-custom">Register <i class="fas fa-user-plus"></i></button>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <a href="{{ route('login') }}" class="text-muted"><i class="fas fa-sign-in-alt"></i> Already have an account?</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection


@section('pages-scripts')

<script>
     $("#register_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            email_id: {
                required: true
            },
            password: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('register_form'))
            axios({
                method: 'POST',
                url: base_url+'/store-user',
                data: formData
            }).then(function(response){
                var data = response.data;
                if(data.status == true){
                    toastr.success(data.message);
                    window.location.href = data.redirect_to;
                }
                else if(data.status == 'validation_error'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: data.message
                    })
                }   
                else if(data.status == false){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    })
                }
                else{
                    toastr.error('Something went wrong. Please try again.')
                }
            }).catch(function(error){
                toastr.error(error)
            })
        }
    });
    
</script>

@endsection