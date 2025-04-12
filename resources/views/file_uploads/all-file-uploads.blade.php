@extends('layouts.master_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="page-content">

    <!-- Content Header (Page header) -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                </ol>
            </nav>
        </div>

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card p-3">
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="file_uploads_datatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="20%">Category</th>
                                    <th>File Type</th>
                                    <th width="12%">File</th>
                                    <th width="12%">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- Modal --}}
<div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="fileUploadModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="fileUploadsForm" id="fileUploadsForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="operation_type">
                    <input type="hidden" name="file_upload_code" id="file_upload_code">
                    <div class="mb-3">
                        <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                        <select class="form-control" name="category" id="category">
                            <option value="">--- Select ---</option>
                            @foreach ($categories as $cat)
                            <option value="{{$cat->code}}">{{$cat->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="file_type">File Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="file_type" id="file_type">
                            <option value="">--- Select ---</option>
                            <option value="IMAGE">Image</option>
                            <option value="PDF">PDF</option>
                            <option value="WORD">Word</option>
                            <option value="EXCEL">Excel</option>
                            <option value="PPT">PPT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="file">File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" id="file">
                        <small class="text-danger">Max Size 25MB</small>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-custom" id="formSubmitBtn"></button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('pages-scripts')
<script>
    // -------------------------------------------
    // VARIABLES FOR FORM AND MODALS
    // -------------------------------------------
    const DATATABLE_ADD_BUTTON = '<button class="btn btn-custom" id="btn_add_file_upload"><i class="fas fa-plus"></i> Add New</button>';

    const ADD_BTN_NAME = '<i class="bx bx-paper-plane"></i> Save Details';
    const EDIT_BTN_NAME = '<i class="bx bx-edit"></i> Update Details';

    const ADD_FORM_LABEL = '<i class="bx bx-plus"></i> Add New File';
    const EDIT_FORM_LABEL = '<i class="bx bx-plus"></i> Add New File';

    const ADD_OPERATION_TYPE = 'ADD';
    const EDIT_OPERATION_TYPE = 'EDIT';

    const STORE_URL = base_url + '/file-upload/store';
    const UPDATE_URL = base_url + '/file-upload/update';
    const FETCH_SINGLE_DETAILS = base_url + '/ajax/get/menu-details';
    const DELETE_URL = base_url + '/ajax/menu/delete';

    // -------------------------------------------
    // DATATABLE: Datatable to show all the data
    // -------------------------------------------
    var fileUploadsDatatable = $('#file_uploads_datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        "ajax": {
            url: base_url + "/file-upload/get-all-for-datatable",
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
                data: null,
                className: "text-center",
                render: function(data, type, row, meta) {
                    return data.category.category_name;
                }

            },
            {
                data: 'file_type',
                className: "text-left"
            },
            {
                data: 'file_button',
                className: "text-left"
            },
            {
                data: 'action',
                className: "text-center",
                width: '12%'
            },

        ],
        "columnDefs": [{
            "targets": ['_ALL'],
            "orderable": false,
            "sorting": false
        }],
        dom: "<'row'<'col-12 col-md-4'B><'col-12 col-md-4'l><'col-12 col-md-4'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        buttons: []
    });


    // -------------------------------------------
    // DATATABLE ADD BUTTON
    // -------------------------------------------
    $(window).on('load', function() {
        $('.dataTables_wrapper .dt-buttons').append(DATATABLE_ADD_BUTTON);
    })

    // -------------------------------------------
    // ONCLICK ADD BUTTON
    // -------------------------------------------
    $(document).on('click', '#btn_add_file_upload', function() {
        $('#operation_type').val(ADD_OPERATION_TYPE);
        $('#fileUploadModalLabel').html(ADD_FORM_LABEL);
        $('#formSubmitBtn').html(ADD_BTN_NAME);
        $('#fileUploadModal').modal('show')
    })

    // -------------------------------------------
    // SUBMIT THE ADD/EDIT FORM
    // -------------------------------------------
    $("#fileUploadsForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            category: {
                required: true
            },
            file_type: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('fileUploadsForm'));
            var url;

            // Check the operation type
            var operationType = $('#operation_type').val();

            if (operationType == EDIT_OPERATION_TYPE) {
                url = UPDATE_URL;
            } else if (operationType == ADD_OPERATION_TYPE) {
                url = STORE_URL;
            } else {
                toastr.error('Invalid operation type provided')
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
                        toastr.success(response.message);
                        fileUploadsDatatable.ajax.reload();
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


    // -------------------------------------------
    // ONCLICK EDIT BUTTON
    // -------------------------------------------
    $(document).on('click', '.editFileUploadBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: FETCH_SINGLE_DETAILS,
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
                    $('#operation_type').val(EDIT_OPERATION_TYPE);
                    $('#menu_id').val(btoa(data.id));
                    $('#menu_name').val(data.menu_name);
                    $('#menu_icon').val(data.icon_class);
                    $('#resource option[value="' + data.resource_code + '"]').prop('selected', true);
                    $('#role option[value="' + data.role + '"]').prop('selected', true);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);

                    $('#menu_order').val(data.sl_no);

                    $('.form_title').html(EDIT_FORM_LABEL);
                    $('#formSubmitBtn').html(EDIT_BTN_NAME);
                } else if (response.status == false) {
                    toastr.error(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(errors) {
                toastr.error('Server failed while processing request');
            }
        });
    });

    // -------------------------------------------
    // ONCLICK DELETE BUTTON
    // -------------------------------------------
    $(document).on('click', '.deleteFileUploadBtn', function() {
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
                        url: DELETE_URL,
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                fileUploadsDatatable.ajax.reload();
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

    // -------------------------------------------
    // RESET FORM
    // -------------------------------------------
    function formReset() {
        document.getElementById("fileUploadsForm").reset();
        $('#operation_type').val(ADD_OPERATION_TYPE);
        $('#formSubmitBtn').html(ADD_BTN_NAME);
        $('.form_title').html(ADD_FORM_LABEL);
        $('#fileUploadModal').modal('hide')
    }
</script>
@endsection