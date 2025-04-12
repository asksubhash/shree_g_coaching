@extends('layouts.master_layout')

@section('content')
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
                <div class="mb-3 bg-light card border-0 card-body shadow-none">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="d-flex align-items-center">
                                <label for="filter_state" class="form-label me-2">State:</label>
                                <select name="state" id="filter_state" class="form-control flex-fill" required>
                                    <option value="">ALL</option>
                                    @foreach ($states as $item)
                                    <option value="{{ $item->state_code }}">{{ $item->state_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="institute-datatable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th width="15%">Centre Code</th>
                                <th width="15%">Centre Name</th>
                                <th>Head Name</th>
                                <th>Address 1</th>
                                <th>Address 2</th>
                                <th>State</th>
                                <th>District</th>
                                <th>Pin Code</th>
                                <th>Registered By</th>
                                <th>Status</th>
                                <th width="12%" class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


{{-- Modal --}}
<div class="modal fade" id="instituteModal" tabindex="-1" aria-labelledby="instituteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="instituteModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="add_institute_form" id="add_institute_form">
                    @csrf
                    <input type="hidden" name="operation_type" id="operation_type">
                    <input type="hidden" name="inst_id" id="inst_id">

                    <div class="row">
                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="institute_code">Centre Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="institute_code" id="institute_code" required>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="name">Institute Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="address1">Address 1<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="address1" id="address1" required>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="address2">Address 2<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="address2" id="address2" required>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="state">State <span class="text-danger">*</span></label>
                            <select name="state" id="state" class="form-control" required>
                                <option value="">Select</option>
                                @foreach ($states as $item)
                                <option value="{{ $item->state_code }}">{{ $item->state_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="district">District <span class="text-danger">*</span></label>
                            <select name="district" id="district" class="form-control" required>
                                <option value="">Select District</option>

                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="pin_code">Pin Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pin_code" id="pin_code" required>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Select Status</option>
                                @foreach (Config::get('constants.status') as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-12 text-center">
                            <button type="submit" class="btn btn-primary" id="formSubmitBtn"></button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('pages-scripts')
<script>
    var instituteDataTable = $('#institute-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "autoWidth": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/ajax/get/all-institutes",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.filter_state = $('#filter_state').val()
            }
        },
        initComplete: function() {
            $('[data-toggle="tooltip"]').tooltip()
        },
        "columns": [{
                data: null,
                name: 'id',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }

            },
            {
                data: 'institute_code',
                className: "text-left"
            },
            {
                data: 'name',
                className: "text-left"
            },
            {
                data: 'person_name',
                className: "text-left"
            },
            {
                data: 'address1',
                className: "text-left"
            },
            {
                data: 'address2',
                className: "text-left"
            },
            {
                data: 'state_name',
                className: "text-left"
            },
            {
                data: 'district_name',
                className: "text-left"
            },
            {
                data: 'pin_code',
                className: "text-right"
            },
            {
                data: 'registered_by',
                name: 'registered_by',
                className: "text-center"
            },
            {
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'action',
                className: "text-center"
            }

        ],
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-12 col-md-4'B><'col-12 col-md-4'l><'col-12 col-md-4'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        buttons: []
    });

    $(window).on('load', function() {
        $('.dataTables_wrapper .dt-buttons').append(
            '<button class="btn btn-primary btn-sm" id="add_institute_btn"><i class="bx bx-plus"></i> Add New</button>'
        );
    })

    // ===================================
    // Filter
    // ===================================
    $('#filter_state').on('change', function() {
        instituteDataTable.ajax.reload();
    })

    // On click add button, open the modal
    $(document).on('click', '#add_institute_btn', function() {
        document.getElementById("add_institute_form").reset();
        $('#operation_type').val('ADD');
        $('#instituteModalLabel').html('<i class="bx bx-plus"></i> Add New Institute');
        $('#formSubmitBtn').html('<i class="bx bx-save"></i> Save');
        $("#add_institute_form").validate().resetForm();
        $('#instituteModal').modal('show');
    })

    // On submitting the form
    $("#add_institute_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            name: {
                required: true
            },
            institute_code: {
                required: true
            },
            address1: {
                required: true
            },
            address2: {
                required: true
            },
            state: {
                required: true
            },
            district: {
                required: true
            },
            pin_code: {
                required: true
            },
            status: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_institute_form'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/institute/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/institute/store';
            } else {
                return false;
            }

            // Send Ajax Request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        instituteDataTable.ajax.reload();
                        toastr.success(response.message);
                        $('#instituteModal').modal('hide');
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
                    toastr.error('Something went wrong. Please try again.')
                }
            });
        }
    });

    $('#instituteModal').on('hidden.bs.modal', function() {
        $('#instituteModalLabel').html('');
        $('#formSubmitBtn').html('');
        $('#add_institute_form').trigger('reset');
    });

    // Onclick edit button
    $(document).on('click', '.editInstituteBtn', function() {
        var id = $(this).attr('id');

        $.ajax({
            url: base_url + '/ajax/get/institute-details',
            type: 'POST',
            data: {
                id: btoa(id),
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    var data = response.data;
                    console.log(data);
                    // Set the form data
                    $('#operation_type').val('EDIT');
                    $('#inst_id').val(btoa(data.id));
                    $('#name').val(data.name);
                    $('#institute_code').val(data.institute_code);
                    $('#address1').val(data.address1);
                    $('#address2').val(data.address2);
                    $('#pin_code').val(data.pin_code);
                    $('#state option[value="' + data.state + '"]').prop('selected', true);
                    $('#district option[value="' + data.district + '"]').prop('selected', true);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                    $('#instituteModalLabel').html('<i class="bx bx-edit"></i> Edit Institute');
                    $('#formSubmitBtn').html('<i class="bx bx-edit"></i> Update');
                    $('#instituteModal').modal('show');
                } else if (response.status == false) {
                    toastr.error(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(errors) {
                console.log(errors);
            }
        });
    });

    $(document).on('click', '.deleteInstituteBtn', function() {
        var id = $(this).attr('id');

        if (id) {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#555',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                /* Read more about isConfirmed, isDenied below */
                if (result.value) {
                    $.ajax({
                        url: base_url + '/ajax/institute/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                instituteDataTable.ajax.reload();
                            } else if (response.status == false) {
                                toastr.error(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(errors) {
                            toastr.error(error)
                        }
                    });
                }

            });
        } else {
            toastr.error('Something went wrong. Please try again.');
        }

    });
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