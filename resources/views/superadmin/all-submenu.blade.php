@extends('superadmin.layouts.superadmin_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('superadmin/dashboard') }}"><i
                                class="bx bx-home-alt"></i></a>
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

                <div class="card-header mb-4">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-12 mb-3">
                            <label class="form-label" for="" class="text-dark">Role Code</label>
                            <select name="role_code" id="role_code" class="form-control">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                <option value="{{$role->role_code}}">{{$role->role_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="menu-datatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    <th width="10%">Order</th>
                                    <th width="10%">Parent Menu</th>
                                    <th width="10%">Sub Menu Name</th>
                                    <th width="10%">Resource</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Menu Icon</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Sub Menu</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="menuForm" id="menuForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="menu_id" id="menu_id">

                        <div class="mb-3">
                            <label class="form-label" for="parent_menu">Parent Menu <strong
                                    class="text-danger">*</strong></label>
                            <select name="parent_menu" id="parent_menu" class="form-control" required>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="sub_menu">Sub Menu <strong
                                    class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="menu" id="sub_menu">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="resource">Recourse Name <strong
                                    class="text-danger">*</strong></label>
                            <select name="resource" id="resource" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach ($resources as $val)
                                <option value="{{ $val->id }}">{{ $val->resource_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="menu_icon">Sub Menu Icon <strong
                                    class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="menu_icon" id="menu_icon" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="menu_order">Sub Menu Order <strong
                                            class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" name="menu_order" id="menu_order" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status <strong
                                            class="text-danger">*</strong></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach (Config::get('constants.status') as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 text-right">
                            <button type="submit" class="btn btn-custom" id="formSubmitBtn"><i
                                    class="bx bx-paper-plane"></i> Submit</button>
                            <button type="button" class="btn btn-default" onclick="formReset()"><i
                                    class="bx bx-refresh"></i> Reset</button>
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
    var menuDataTable = $('#menu-datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        "ajax": {
            url: base_url + "/ajax/get/all-sub-menus",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.role_code = $('#role_code').val();
            }
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
                className: "text-center",
                width: '12%'
            },

            {
                data: 'sl_no',
                className: "text-center"
            },

            {
                data: 'menu_name',
                className: "text-left"
            },
            {
                data: 'sub_menu_name',
                className: "text-left"
            },
            {
                data: 'resource_name',
                className: "text-left"
            },
            {
                data: 'record_status',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'icon_class',
                className: "text-left"
            },

        ],
        "columnDefs": [{
            "targets": ['_ALL'],
            "orderable": false,
            "sorting": false
        }],
    });

    function getParentMenu() {
        $.ajax({
            url: base_url + '/ajax/getParentMenu',
            type: 'POST',
            data: {
                role_code: $('#role_code').val(),
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    let option = `<option>--- Select Parent Menu ---</option>`;
                    $.each(response.data, function(key, val) {
                        option += `<option value="${val.id}">${val.menu_name}</option>`
                    });
                    $('#parent_menu').html(option);
                } else if (response.status == false) {
                    toastr.error(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(error) {
                toastr.error(error)
            }
        });
    }

    $('#role_code').on('change', function() {
        menuDataTable.ajax.reload();
        getParentMenu();
    })

    $("#menuForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            parent_menu: {
                required: true
            },
            menu: {
                required: true
            },
            menu_icon: {
                required: true
            },
            resource: {
                required: true
            },
            menu_order: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();

            // Get role code ================
            let role_code = $('#role_code').val();

            // Check for the role code ================
            if (role_code) {
                var formData = new FormData(document.getElementById('menuForm'));
                formData.append('role_code', role_code);

                // Check the operation type
                var url;
                var operationType = $('#operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/ajax/sub-menu/update';
                } else if (operationType == 'ADD') {
                    url = base_url + '/ajax/sub-menu/store';
                } else {
                    return false;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == true) {
                            menuDataTable.ajax.reload();
                            toastr.success(response.message);
                            formReset();
                            getParentMenu();
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
        }
    });


    // Onclick edit button
    $(document).on('click', '.editMenuBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/sub-menu-details',
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
                    $('#menu_id').val(btoa(data.id));

                    $('#parent_menu option[value="' + data.menu_id + '"]').prop('selected', true);

                    console.log(data.sub_menu_name)
                    $('#sub_menu').val(data.sub_menu_name);

                    $('#menu_icon').val(data.icon_class);
                    $('#resource option[value="' + data.resource_id + '"]').prop('selected', true);
                    $('#role option[value="' + data.role + '"]').prop('selected', true);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                    $('#menu_order').val(data.sl_no);

                    $('.form_title').html('Edit Sub Menu');
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

    $(document).on('click', '.deleteResourceBtn', function() {
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
                        url: base_url + '/ajax/sub-menu/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                menuDataTable.ajax.reload();
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
        document.getElementById("menuForm").reset();
        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Menu');
    }
</script>
@endsection