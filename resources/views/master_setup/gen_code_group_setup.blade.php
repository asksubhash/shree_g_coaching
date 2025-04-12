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
            <table class="table table-bordered table-striped w-100 nowrap" id="genCodeGroup-datatable">
                <thead>
                    <tr>
                        <th width="8%" class="text-center">S. No.</th>
                        <th>Group Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="genCodeGroupModal" tabindex="-1" aria-labelledby="genCodeGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="genCodeGroupModalLabel"></h5>
                </div>

                <div class="modal-body">
                    <form action="" class="add_gencode_group_form" id="add_gencode_group_form">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <div class="mb-3">
                            <label for="name">Group Name</label>
                            <input type="text" class="form-control" name="group_name" id="group_name">
                        </div>
                        <div class="mb-3">
                            <label for="name">Status</label>
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
    // get datatable records
    var genCodeGroupDataTable = $('#genCodeGroup-datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "responsive": false,
        "ajax": {
            url: base_url + "/gen-code-group/list",
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
                data: 'status_desc',
                className: "text-center"
            },

        ],
        "columnDefs": [{
            "targets": [0, 1, 2],
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
            '<button class="btn btn-primary mb-2" id="addgenCodeGroup" data-bs-toggle="modal" data-bs-target="#genCodeGroupModal">Add New</button>');
    })


    $(document).on('click', '#addgenCodeGroup', function() {
        $('#operation_type').val('ADD');
        $('#genCodeGroupModalLabel').html('<i class="fas fa-plus"></i> Add genCodeGroup');
        $('#formSubmitBtn').html('<i class="fas fa-paper-plane"></i> Save');
        // $('#genCodeGroupModal').modal('show');
    });

    // On submitting the form
    $("#add_gencode_group_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            group_name: {
                required: true
            },
            status: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_gencode_group_form'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/gen-code-group/edit';
            } else if (operationType == 'ADD') {
                url = base_url + '/gen-code-group/add';
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
                        genCodeGroupDataTable.ajax.reload();
                        toastr.success(response.message);
                        $('#genCodeGroupModal').modal('hide');
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

    $('#genCodeGroupModal').on('hidden.bs.modal', function() {
        $('#genCodeGroupModalLabel').html('');
        $('#formSubmitBtn').html('');
        $('#add_gencode_group_form').trigger('reset');
    });
</script>
@endsection