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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="resource-datatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Resource</th>
                                    <th width="10%">Link</th>
                                    <th width="10%">Is Maintenance</th>
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
                    <strong class="form_title"> Add Recourse</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="recourseForm" id="recourseForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="resource_id" id="resource_id">
                        <div class="mb-3">
                            <label for="link">Link <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="link" id="link">
                        </div>
                        <div class="mb-3">
                            <label for="name">Name <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="is_maintenance">Is Maintenance <strong class="text-danger">*</strong></label>
                                    <select name="is_maintenance" id="is_maintenance" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach (Config::get('constants.status_2') as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="status">Status <strong class="text-danger">*</strong></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach (Config::get('constants.status') as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3 text-right">
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
    var resourceDataTable = $('#resource-datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        // fixedColumns: {
        //     left: 2,
        // },
        "ajax": {
            url: base_url + "/ajax/get/all-resource",
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
                data: 'resource_name',
                className: "text-left"
            },
            {
                data: 'resource_link',
                className: "text-left"
            },
            {
                data: 'is_maintenance',
                className: "text-center"
            },

        ],
        // "columnDefs": [{
        //     "targets": [0, 1, 2, 3],
        //     "orderable": false,
        //     "sorting": false
        // }],

    });

    $("#recourseForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            name: {
                required: true
            },
            link: {
                required: true
            },
            is_maintenance: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('recourseForm'));

            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/resource/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/resource/store';
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
                        resourceDataTable.ajax.reload();
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
    $(document).on('click', '.editResourceBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/resource-details',
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
                    $('#resource_id').val(btoa(data.id));
                    $('#name').val(data.resource_name);
                    $('#link').val(data.resource_link);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                    $('#is_maintenance option[value="' + data.is_maintenance + '"]').prop(
                        'selected', true);
                    $('.form_title').html('Edit Recourse');
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
                        url: base_url + '/ajax/resource/delete',
                        type: 'POST',
                        data: {
                            id: id,
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                resourceDataTable.ajax.reload();
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
        document.getElementById("recourseForm").reset();
        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Recourse');
    }

    // Lobibox.notify('success', {
    //     pauseDelayOnHover: true,
    //     continueDelayOnInactiveTab: false,
    //     size: 'mini',
    //     position: 'top right',
    //     icon: 'bx bx-info-circle',
    //     msg: 'This is Gradient Color Dashboard'
    // });
</script>
@endsection