@extends('superadmin.layouts.superadmin_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<style>
    .profile-userpic-wrapper {
        width: 130px;
        height: 130px;
        position: relative;
        margin: auto;
        overflow: hidden;
    }

    .profile-userpic-wrapper label {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #26262694;
        border-radius: 50%;
        cursor: pointer;
        color: white;
        overflow: hidden;
        opacity: 0;
        transition: all .4s ease-in-out;
    }

    .profile-userpic-wrapper #profile_image_upload {
        display: none;
    }

    .profile-userpic-wrapper:hover label {
        opacity: 1;
    }
</style>
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('superadmin/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-custom card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('public/dist/img/avatar4.png') }}" alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ ucwords(auth()->user()->userDetail->f_name . '' . auth()->user()->userDetail->l_name) }}
                                </h3>
                                <p class="text-muted text-center">{{ auth()->user()->role->role_name }}</p>

                                <strong><i class="bx bx-book-alt mr-1"></i> Email</strong>

                                <p class="text-muted">
                                    {{ auth()->user()->userDetail->email_id }}
                                </p>

                                <hr>

                                <strong><i class="bx bx-map mr-1"></i> Phone Number</strong>

                                <p class="text-muted">{{ auth()->user()->userDetail->mobile_no }}</p>

                                <hr>
                                <button type="button" id="change_password_btn" class="btn btn-custom btn-block"><i class="bx bx-edit"></i> Change
                                    Password</button>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <form class="row g-3" class="updateProfileForm" id="updateProfileForm" autocomplete="off">
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="profile_f_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="profile_f_name" id="profile_f_name" value="{{ auth()->user()->userDetail->f_name }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="profile_l_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="profile_l_name" id="profile_l_name" value="{{ auth()->user()->userDetail->l_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile_email">Email Id</label>
                                        <input type="email" class="form-control" name="profile_email" id="profile_email" value="{{ auth()->user()->userDetail->email_id }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile_phone">Phone Number</label>
                                        <input type="text" class="form-control" name="profile_phone" id="profile_phone" value="{{ auth()->user()->userDetail->mobile_no }}">
                                    </div>

                                    <div class="col-md-12 text-center col-12">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-custom" id="profileFormSubmitBtn"><span class="bx bx-save"></span>
                                                Update Details</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- Modal --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="changePasswordForm" id="changePasswordForm">
                    @csrf
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input type="password" type="password" class="form-control" name="old_password" id="old_password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-custom"><i class="fas fa-edit"></i> Change
                            Password</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('pages-scripts')
<script>
    $('#change_password_btn').on('click', function() {
        $('#changePasswordModal').modal('show');
    })

    // On submitting the form
    $('#changePasswordForm').on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(document.getElementById('changePasswordForm'));

        // Send Ajax Request
        $.ajax({
            url: base_url + '/ajax/superadmin-profile/change-password',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == true) {
                    toastr.success(response.message);
                    $('#changePasswordModal').modal('hide');
                } else if (response.status == 'validation_errors') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: response.message
                    })
                } else if (response.status == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.message
                    })
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(error) {
                toastr.error('Something went wrong. Please try again.')
            }
        });
    });

    $("#updateProfileForm").validate({
        errorClass: "text-danger validation-error",
        rules: {
            profile_f_name: {
                required: true
            },
            profile_l_name: {
                required: true
            },
            profile_phone: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('updateProfileForm'))
            $(".loader").show();

            // Send Ajax Request
            $.ajax({
                url: base_url + '/ajax/superadmin-profile/update',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        toastr.success(response.message);
                        window.location.reload();
                    } else if (response.status == 'validation_errors') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: response.message
                        })
                    } else if (response.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: response.message
                        })
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(error) {
                    toastr.error('Something went wrong. Please try again.')
                }
            });
        }
    });
</script>
@endsection