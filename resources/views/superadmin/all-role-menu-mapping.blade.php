@extends('superadmin.layouts.superadmin_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $page_title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $page_title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9 col-12">
                    <div class="card p-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="">--Select--</option>
                                    @foreach ($roles as $val)
                                    <option value="{{ $val->role_code }}">{{ $val->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped w-100 nowrap" id="menu-datatable">
                                    <thead>
                                        <tr>
                                            <th width="8%">S. No.</th>
                                            <th width="12%">Action</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Role</th>
                                            <th width="10%">Menu Name</th>
                                            <th width="10%">Alias Name</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="form_title">Add Roll To Menu Mapping</strong>
                        </div>
                        <div class="card-body">
                            <form action="javascript:void(0)" class="menuForm" id="menuForm">
                                @csrf
                                <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                                <input type="hidden" name="menu_id" id="menu_id">

                                <div class="mb-3">
                                    <label for="role_code">Role <strong class="text-danger">*</strong></label>
                                    <select name="role_code" id="role_code" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach ($roles as $val)
                                        <option value="{{ $val->role_code }}">{{ $val->role_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="menu_code">Menu <strong class="text-danger">*</strong></label>
                                    <select name="menu_code" id="menu_code" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach ($menus as $val)
                                        <option value="{{ $val->menu_code }}">{{ $val->menu_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alias_menu">Alias Menu <strong class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" name="alias_menu" id="alias_menu">
                                </div>
                                <div class="mb-3">
                                    <label for="status">Status <strong class="text-danger">*</strong></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach (Config::get('constants.status') as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="mb-3 text-right">
                                    <button type="submit" class="btn btn-custom" id="formSubmitBtn"><i
                                            class="fas fa-paper-plane"></i> Submit</button>
                                    <button type="button" class="btn btn-default" onclick="formReset()"><i
                                            class="fas fa-solid fa-retweet"></i> Reset</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
            fixedColumns: {
                left: 2,
            },
            "ajax": {
                url: base_url + "/ajax/get/all-roleMenuMapping",
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');

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
                    data: 'record_status',
                    name: 'id',
                    className: "text-center"
                },
                {
                    data: 'role_name',
                    name: 'role_name',
                    className: "text-center"
                },
                {
                    data: 'menu_name',
                    name: 'menu_name',
                    className: "text-left"
                },
                {
                    data: 'alias_menu',
                    name: 'alias_menu',
                    className: "text-left"
                }

            ],
            // "columnDefs": [{
            //     "targets": [0, 1, 2, 3],
            //     "orderable": false,
            //     "sorting": false
            // }],

        });

        $("#menuForm").validate({
            errorClass: 'validation-error w-100 text-danger',
            rules: {
                role_code: {
                    required: true
                },
                menu_code: {
                    required: true
                },
                alias_menu: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('menuForm'));

                // Check the operation type
                var url;
                var operationType = $('#operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/ajax/menuMapping/update';
                } else if (operationType == 'ADD') {
                    url = base_url + '/ajax/menuMapping/store';
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
        $(document).on('click', '.editMenuBtn', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: base_url + '/ajax/get/menuMapping-details',
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
                        $('#alias_menu').val(data.alias_menu);
                        $('#role_code option[value="' + data.role_code + '"]').prop('selected', true);
                        $('#menu_code option[value="' + data.menu_code + '"]').prop('selected', true);
                        $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                        $('.form_title').html('Edit Role To Menu Mapping');
                        $('#formSubmitBtn').html('<i class="fas fa-edit"></i> Update');
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

        $(document).on('click', '.deleteMenuBtn', function() {
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
                            url: base_url + '/ajax/menuMapping/delete',
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
            $('#formSubmitBtn').html('<i class="fas fa-paper-plane"></i> Submit');
            $('.form_title').html('Add Role To Menu Mapping');
        }


</script>
@endsection
