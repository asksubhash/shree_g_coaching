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
            <table class="table table-bordered table-striped w-100 nowrap" id="category-datatable">
                <thead>
                    <tr>
                        <th width="8%" class="text-center">S. No.</th>
                        <th>Category Name</th>
                        <th>Department Name</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel"></h5>
                </div>

                <div class="modal-body">
                    <form action="" class="add_category_form" id="add_category_form">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <div class="mb-3">
                            <label for="name">Department Name</label>
                            <select class="form-control" name="department" id="department">
                                @foreach ($department as $data)
                                <option value="{{$data->id}}">{{$data->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" name="category" id="category">
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
    // get datatable records
    var categoryDataTable = $('#category-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/category/list",
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
                data: 'category_name',
                className: "text-center"
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
            '<button class="btn btn-primary mb-2" id="addCategory" data-bs-toggle="modal" data-bs-target="#categoryModal">Add New</button>');
    })


    $(document).on('click', '#addCategory', function() {
        $('#operation_type').val('ADD');
        $('#categoryModalLabel').html('<i class="fas fa-plus"></i> Add Category');
        $('#formSubmitBtn').html('<i class="fas fa-paper-plane"></i> Save');


        // $('#userModal').modal('show');
    });
    // On submitting the form
    $("#add_category_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            category: {
                required: true
            },
            department: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_category_form'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/category/edit';
            } else if (operationType == 'ADD') {
                url = base_url + '/category/add';
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
                        categoryDataTable.ajax.reload();
                        toastr.success(response.message);
                        $('#categoryModal').modal('hide');
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

    $('#categoryModal').on('hidden.bs.modal', function() {
        $('#categoryModalLabel').html('');
        $('#formSubmitBtn').html('');
        $('#add_category_form').trigger('reset');
    });

    // Delete Category function
    $(document).on('click', '.deleteCategoryBtn', function() {
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
                        url: base_url + '/category/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                categoryDataTable.ajax.reload();
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

    // Edit Category Function
    $(document).on('click', '.editCategoryBtn', function() {
        var id = $(this).attr('id');

        $.ajax({
            url: base_url + '/category/get-details',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {
                if (response.status == true) {
                    var data = response.data;
                    // console.log(data)
                    // Set the form data
                    $('#operation_type').val('EDIT');
                    $('#hidden_id').val(btoa(data.id));
                    $('#category').val(data.category_name);
                    $('#department option[value="' + data.department_id + '"]').prop('selected', true);
                    $('#categoryModalLabel').html('<i class="fas fa-edit"></i> Edit Department');
                    $('#formSubmitBtn').html('<i class="fas fa-edit"></i> Update');
                    $('#categoryModal').modal('show');
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