@extends('layouts.master_layout')

@section('content')
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url()->to('admin/dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>

    </div>
    <!-- /.content-header -->
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100 nowrap" id="department-datatable">
                <thead>
                    <tr>
                        <th width="8%" class="text-center">S. No.</th>
                        <th width="">Department Name</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="departmentModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    <form action="" class="add_department_form" id="add_department_form">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <div class="mb-3">
                            <label for="name">Department Name</label>
                            <input type="text" class="form-control" name="department_name" id="department_name">
                        </div>
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-custom" id="formSubmitBtn">Add</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- /.content -->
</div>


@endsection

@section('pages-scripts')
<script>
    $(document).on('click', '#addDep', function() {
        $('#operation_type').val('ADD');
        $('#departmentModalLabel').html('<i class="fas fa-plus"></i> Add Department');
        $('#formSubmitBtn').html('<i class="fas fa-paper-plane"></i> Save');


        // $('#userModal').modal('show');
    })

    // On submitting the form
    $("#add_department_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            department_name: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_department_form'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/departments/edit';
            } else if (operationType == 'ADD') {
                url = base_url + '/departments/add';
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
                        departmentDataTable.ajax.reload();
                        toastr.success(response.message);
                        $('#departmentModal').modal('hide');
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

    $('#departmentModal').on('hidden.bs.modal', function() {
        $('#departmentModalLabel').html('');
        $('#formSubmitBtn').html('');
        $('#add_department_form').trigger('reset');
    });

    var departmentDataTable = $('#department-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/departments/list",
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
                data: 'department_name',
                className: "text-center"
            },
            {
                data: 'status_desc',
                className: "text-center"
            },
            {
                data: 'action',
                className: "text-center"
            }

        ],
        "columnDefs": [{
            "targets": [0, 1, 2, 3],
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
            '<button class="btn btn-primary mb-2" id="addDep" data-bs-toggle="modal" data-bs-target="#departmentModal">Add New</button>');
    })


    // Delete Department function
    $(document).on('click', '.deleteDepartmentBtn', function() {
        var id = $(this).attr('id');
        console.log(id)

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
                        url: base_url + '/departments/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                departmentDataTable.ajax.reload();
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


    // Edit Department Function
    $(document).on('click', '.editDepartmentBtn', function() {
        var id = $(this).attr('id');

        $.ajax({
            url: base_url + '/departments/get-details',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    var data = response.data;
                    console.log(data)
                    // Set the form data
                    $('#operation_type').val('EDIT');
                    $('#hidden_id').val(btoa(data.id));
                    $('#department_name').val(data.department_name);
                    $('#departmentModalLabel').html('<i class="fas fa-edit"></i> Edit Department');
                    $('#formSubmitBtn').html('<i class="fas fa-edit"></i> Update');
                    $('#departmentModal').modal('show');
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
</script>
@endsection