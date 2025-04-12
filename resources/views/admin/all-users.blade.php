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
                                <select name="role" id="filter_role" class="form-control flex-fill" required>
                                    <option value="">ALL</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role['role_code'] }}">{{ $role['role_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="user-datatable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th>Username</th>
                                <th width="15%">Name</th>
                                <th>Email ID</th>
                                <th>Phone Number</th>
                                <th>Designation</th>
                                <th>Role</th>
                                <th>Institute</th>
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
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="add_user_form" id="add_user_form">
                    @csrf
                    <input type="hidden" name="operation_type" id="operation_type">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="row">

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="f_name" id="f_name">
                        </div>
                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="l_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="l_name" id="l_name">
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="designation">Designation <span class="text-danger">*</span></label>
                            <select name="designation" id="designation" class="form-control">
                                <option value="">Select Designation</option>
                                @foreach ($designations as $des)
                                <option value="{{ $des['id'] }}">{{ $des['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="email_id">Email ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="email_id" id="email_id">
                            <small class="text-muted" id="email_id_help_block"></small>
                        </div>
                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number">
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role['role_code'] }}">{{ $role['role_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="department">Institute <span class="text-danger">*</span></label>
                            <select name="department" id="department" class="form-control">
                                <option value="">Select Institute</option>
                                <option value="ALL">All</option>
                                @foreach ($departments as $dep)
                                <option value="{{ $dep['id'] }}">{{ $dep['name'] . ' ('. $dep['institute_code'].')' }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3 col-md-6 col-12">
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control">
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
    var userDataTable = $('#user-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "autoWidth": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/ajax/get/all-users",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.filter_role = $('#filter_role').val();
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
                data: 'username',
                className: "text-center"
            },
            {
                data: 'full_name',
                className: "text-center"
            },
            {
                data: 'email_id',
                className: "text-center"
            },
            {
                data: 'mobile_no',
                className: "text-center"
            },
            {
                data: 'designation_desc',
                name: 'designation',
                className: "text-center"
            },
            {
                data: 'role',
                className: "text-center"
            },
            {
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return data.department_id == 'ALL' ? 'ALL' : data.department_name
                }
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
            '<button class="btn btn-primary" id="add_user_btn"><i class="bx bx-plus"></i> Add New</button>');
    })

    // ===================================
    // Filter
    // ===================================
    $('#filter_role').on('change', function() {
        userDataTable.ajax.reload();
    })

    // On click add button, open the modal
    $(document).on('click', '#add_user_btn', function() {
        $('#operation_type').val('ADD');
        $('#userModalLabel').html('<i class="bx bx-plus"></i> Add New User');
        $('#formSubmitBtn').html('<i class="bx bx-save"></i> Save');
        $('#email_id_help_block').text('')
        $('#role option').prop('selected', false);
        $('#designation option').prop('selected', false);
        $('#status option').prop('selected', false);
        $('#email_id').prop('readonly', false);

        $('#userModal').modal('show');
    })

    // On submitting the form
    $("#add_user_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            f_name: {
                required: true
            },
            l_name: {
                required: true
            },
            email_id: {
                required: true
            },
            phone_number: {
                required: true
            },
            designation: {
                required: true
            },
            role: {
                required: true
            },
            status: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_user_form'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/user/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/user/store';
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
                        userDataTable.ajax.reload();
                        toastr.success(response.message);
                        $('#userModal').modal('hide');
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

    $('#userModal').on('hidden.bs.modal', function() {
        $('#userModalLabel').html('');
        $('#formSubmitBtn').html('');
        $('#add_user_form').trigger('reset');
    });

    // Onclick edit button
    $(document).on('click', '.editUserBtn', function() {
        var id = $(this).attr('id');

        $.ajax({
            url: base_url + '/ajax/get/user-details',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    var data = response.data;
                    // Set the form data
                    $('#operation_type').val('EDIT');
                    $('#user_id').val(btoa(data.id));
                    $('#f_name').val(data.f_name);
                    $('#l_name').val(data.l_name);
                    $('#email_id').val(data.email_id);
                    $('#phone_number').val(data.mobile_no);
                    $('#role option[value="' + data.role_code + '"]').prop('selected', true);
                    $('#designation option[value="' + data.designation + '"]').prop('selected',
                        true);

                    $('#status option[value="' + data.status + '"]').prop('selected', true);

                    $('#email_id').prop('readonly', true);
                    $('#email_id_help_block').text('You can not edit Email ID')
                    $('#userModalLabel').html('<i class="bx bx-edit"></i> Edit User');
                    $('#formSubmitBtn').html('<i class="bx bx-edit"></i> Update');
                    $('#userModal').modal('show');
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
                        url: base_url + '/ajax/user/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                userDataTable.ajax.reload();
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
</script>
@endsection