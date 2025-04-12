@extends('layouts.website_layout')
@section('content')
<div class="bg-body-tertiary">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
                <li class="breadcrumb-item">
                    <a class="link-body-emphasis" href="#">
                        <i class="fa fa-home"></i>
                        <span class="visually-hidden">Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Study Center Registration
                </li>
            </ol>
        </nav>
    </div>
</div>
<section class="pt-3 pb-4">
    <div class="container">
        <div class="card card-body shadow-none border-0">
            <h5 class="card-title mb-3 text-center text-danger fw-bold">
                Study Center Registration Form
            </h5>
            <form action="javascript:void(0)" id="centerForm" name="centerForm">
                @csrf

                <!-- Personal Details -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header card-header-light-bg py-3">
                        <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                            <i class="bx bx-file fw-bold"></i> Personal Details:-
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="name" class="form-label">Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" id="name" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="email" class="form-label">Email Id<span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" id="email" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="contact" class="form-label">Contact Number<span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" name="contact" id="contact" required>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="address1" class="form-label">Address 1<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="address1" id="address1" required>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="address2" class="form-label">Address 2
                                </label>
                                <input type="text" class="form-control" name="address2" id="address2" required>
                            </div>


                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="education_qualification" class="form-label">Education
                                    Qualification<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="education_qualification" id="education_qualification" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="occupation" class="form-label">Occupation<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="occupation" id="occupation" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="nature_or_work" class="form-label">Nature Of Work<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="nature_or_work" id="nature_or_work" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Study Center Details -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header card-header-light-bg py-3">
                        <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                            <i class="bx bx-user fw-bold"></i> Study Center Detail:-
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="state" class="form-label ">State<span class="text-danger">*</span>
                                </label>
                                <select name="state" class=" form-select form-control" id="state" required>
                                    <option value="">Select State</option>
                                    @foreach ($states as $item)
                                    <option value="{{ $item->state_code }}">
                                        {{ $item->state_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="district" class="form-label ">District <span class="text-danger">*</span></label>
                                <select name="district" id="district" class="form-control" required>
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="city" class="form-label ">City Name<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="city" id="city" value="" required>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label " for="pin_code">Pin Code<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="pin_code" id="pin_code" required>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label " for="institute_name">Name Of Institute
                                    <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="institute_name" id="institute_name" required>
                            </div>


                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="property" class="form-label ">Property<span class="text-danger">*</span>
                                </label>
                                <select name="property" class=" form-select form-control" id="property" required>
                                    <option value="">Select</option>
                                    @foreach (Config::get('constants.property') as $key => $property)
                                    <option value="{{ $key }}">{{ $property }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header card-header-light-bg py-3">
                        <h6 class="mb-0 card-title text-dark fw-bold text-uppercase">
                            <i class="bx bx-file fw-bold"></i> Documents
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="photo" class="form-label">Passport Size Photo<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control file" name="photo" id="photo" accept=".jpg, .jpeg, .png">
                                <small class="text-danger">
                                    Max Size 1MB, Only (.jpg, .jpeg, .png) are allowed
                                </small>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="aadhar" class="form-label">Aadhar Card<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control file" name="aadhar" id="aadhar" accept=".jpg, .jpeg, .pdf">
                                <small class="text-danger">
                                    Max Size 2MB, Only (.jpg, .jpeg, .pdf) are allowed
                                </small>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label for="document" class="form-label">Education Document<span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control file" name="document" id="document" accept=".jpg, .jpeg, .pdf">
                                <small class="text-danger">
                                    Max Size 2MB, Only (.jpg, .jpeg, .pdf) are allowed
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button class=" btn btn-secondary" type="button" onclick="resetForm()"> <i class="fa fa-sync"></i>
                        Reset</button>
                    <button class=" btn btn-danger" type="submit"> <i class="fa fa-paper-plane"></i>
                        Submit</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('pages-scripts')
<script>
    $(document).ready(function() {
        $("#centerForm").validate({
            errorClass: "text-danger validation-error",
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true
                },
                contact: {
                    required: true
                },
                address1: {
                    required: true
                },
                address2: {
                    required: false
                },
                education_qualification: {
                    required: true
                },
                occupation: {
                    required: true
                },
                nature_or_work: {
                    required: true
                },
                state: {
                    required: true
                },
                district: {
                    required: true
                },
                city: {
                    required: true
                },
                institute_name: {
                    required: true
                },
                pin_code: {
                    required: true
                },
                property: {
                    required: true
                },

                // photo: {
                //     required: true
                // },
                // aadhar: {
                //     required: true
                // },
                // document: {
                //     required: true
                // },
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('centerForm'))
                $(".loader").show();

                $.ajax({
                    url: base_url + '/study-center/store',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        $(".loader").hide();
                        var data = response;
                        if (data.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                html: data.message
                            }).then(() => {
                                window.location.reload();
                            })
                        } else if (data.status == 'validation_errors') {
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
                    }
                })
            }
        });
    });

    function resetForm() {
        document.getElementById("centerForm").reset();
        $("#centerForm").validate().resetForm();
    }
    $('#state').on('change', function() {
        let state = $(this).val();
        $('#district').html(`<option value="">Select District</option>`);
        if (state) {
            $.ajax({
                url: base_url + '/fetch-districts',
                data: {
                    state: state
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $(".loader").hide();
                    if (response.status == true) {
                        response.data.forEach(element => {
                            $('#district').append(
                                `<option value="${element.district_code}">${element.district_name}</option>`
                            );
                        });
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
                    toastr.error('Server error. Please try again.');
                }
            })
        }
    });
</script>
@endsection