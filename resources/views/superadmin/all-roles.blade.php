@extends('superadmin.layouts.superadmin_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
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
        <div class="col-md-8 col-12">
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="role-datatable">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">S. No.</th>
                                <th width="15%" class="text-center">Action</th>
                                <th>Role Code</th>
                                <th width="20%">Role Name</th>
                                <th>Landing Page</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Role</strong>
                </div>
                <div class="card-body">
                    <form class="roleForm" id="roleForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="role_id" id="role_id">
                        <div class="mb-3">
                            <label class="form-label" for="role_code">Role Code <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="role_code" id="role_code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="role_name">Role Name <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="role_name" id="role_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="resource_code">Recourse Name (Landing Page) <strong class="text-danger">*</strong></label>
                            <select name="resource_code" id="resource_code" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach ($resources as $val)
                                <option value="{{ $val->id }}">{{ $val->resource_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Status <strong class="text-danger">*</strong></label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>
                                @foreach (Config::get('constants.status') as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 float-right">
                            <button type="submit" class="btn btn-custom" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                            <button type="button" class="btn btn-default" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('pages-scripts')
<script>
    var roleDataTable = $('#role-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "autoWidth": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/ajax/get/all-roles",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');

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
                data: 'action',
                className: "text-center"
            },
            {
                data: 'role_code',
                className: "text-left"
            },
            {
                data: 'role_name',
                className: "text-left"
            },
            {
                data: 'resource_name',
                className: "text-left"
            },
            {
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },


        ],
        "columnDefs": [{
            "targets": [0, 1, 2, 3],
            "orderable": false,
            "sorting": false
        }],

    });


    // On submitting the form
    $("#roleForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            role_code: {
                required: true
            },
            role_name: {
                required: true
            },
            resource_code: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();

            var formData = new FormData(document.getElementById('roleForm'));

            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/role/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/role/store';
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
                        roleDataTable.ajax.reload();
                        toastr.success(response.message);
                        formReset();
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



    // Onclick edit button
    $(document).on('click', '.editRoleBtn', function() {
        var id = $(this).attr('id');

        $.ajax({
            url: base_url + '/ajax/get/role-details',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    var data = response.data;
                    // Set the form data
                    formReset();
                    $('#operation_type').val('EDIT');
                    $('#role_id').val(btoa(data.id));

                    $('#role_code').val(data.role_code);
                    $('#role_code').prop('readonly', true);

                    $('#role_name').val(data.role_name);
                    $('#status option[value="' + data.status + '"]').prop('selected', true);
                    $('#resource_code option[value="' + data.resource_id + '"]').prop('selected',
                        true);

                    $('.form_title').html('Edit Role');
                    $('#formSubmitBtn').html('<i class="bx bx-edit"></i> Update');
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

    $(document).on('click', '.deleteUserBtn', function() {
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
                        url: base_url + '/ajax/role/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                roleDataTable.ajax.reload();
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

    function formReset() {
        document.getElementById("roleForm").reset();
        $('#operation_type').val('ADD');

        $('#role_code').val('');
        $('#role_code').prop('readonly', false);

        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Role');
    }
</script>
@endsection