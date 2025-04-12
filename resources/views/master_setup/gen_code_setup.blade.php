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
        <img src="{{asset('public/assets/images/common-logo.png')}}" alt="" class="w-25">
        <div class="col-md-12 col-12">
            <div class="">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="gencode-datatable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th>Gen code Group</th>
                                <th>Gencode</th>
                                <th>Description</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="gencodeModal" tabindex="-1" aria-labelledby="gencodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="gencodeModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    <form action="" class="add_gencode_form" id="add_gencode_form">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <div class="mb-3">
                            <label for="name">Gen Code Group:</label>
                            <select class="form-control" name="gen_code_group" id="gen_code_group">
                                @foreach ($gen_code_group as $data)
                                <option value="{{$data->id}}">{{$data->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Gen Code:</label>
                            <input type="text" class="form-control" name="gen_code" id="gen_code">
                        </div>
                        <div class="mb-3">
                            <label for="">Description:</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="">Serial Number:</label>
                            <input type="text" class="form-control" name="serial_no" id="serial_no">
                        </div>
                        <div class="mb-3">
                            <label for="">Status:</label>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
    var gencodeDataTable = $('#gencode-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/gencode/list",
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
                data: 'group_name',
                className: "text-center"
            },
            {
                data: 'gen_code',
                className: "text-center"
            },
            {
                data: 'description',
                className: "text-center"
            },
            {
                data: 'serial_no',
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
            '<button class="btn btn-primary mb-2" id="addGencode">Add New</button>');
    })


    $(document).on('click', '#addGencode', function() {
        $('#operation_type').val('ADD');
        $('#gencodeModalLabel').html('<i class="fas fa-plus"></i> Add Gen Code');
        $('#formSubmitBtn').html('<i class="fas fa-paper-plane"></i> Save');
        $('#gencodeModal').modal('show');
    });

    // On submitting the form
    $("#add_gencode_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            gen_code_group: {
                required: true
            },
            gen_code: {
                required: true
            },
            description: {
                required: true
            },
            serial_no: {
                required: true
            },
            status: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_gencode_form'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/gencode/edit';
            } else if (operationType == 'ADD') {
                url = base_url + '/gencode/add';
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
                        gencodeDataTable.ajax.reload();
                        toastr.success(response.message);
                        $('#gencodeModal').modal('hide');
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

    $('#gencodeModal').on('hidden.bs.modal', function() {
        $('#gencodeModalLabel').html('');
        $('#formSubmitBtn').html('');
        $('#add_gencode_form').trigger('reset');
    });

    // Delete Gen Code function
    $(document).on('click', '.deleteGencodeBtn', function() {
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
                        url: base_url + '/gencode/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                gencodeDataTable.ajax.reload();
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

    // Edit Gen Code Function
    $(document).on('click', '.editGencodeBtn', function() {
        var id = $(this).attr('id');

        $.ajax({
            url: base_url + '/gencode/edit-details',
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
                    $('#hidden_id').val(data.id);
                    $('#gen_code_group option[value="' + data.gen_code_group_id + '"]').prop('selected', true);
                    $('#gen_code').val(data.gen_code);
                    $('#description').val(data.description);
                    $('#serial_no').val(data.serial_no);
                    $('#status option[value="' + data.status + '"]').prop('selected', true);
                    $('#gencodeModalLabel').html('<i class="fas fa-edit"></i> Edit Gen Code');
                    $('#formSubmitBtn').html('<i class="fas fa-edit"></i> Update');
                    $('#gencodeModal').modal('show');
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