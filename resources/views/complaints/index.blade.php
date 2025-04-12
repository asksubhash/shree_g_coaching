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
        <div class="col-md-8 col-12">
            <div class="card p-3">

                <div class="">
                    <table class="table table-bordered table-striped w-100 " id="complaintDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th width="12%">Action</th>
                                <th>Record Status</th>
                                <th>Complaint Status</th>
                                <th>Title</th>
                                <th>Complaint Date</th>
                                <th>Document</th>
                                <th width="50%">Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Complaint</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addComplaintForm" id="addComplaintForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="complaint_id" id="complaint_id">

                        <div class="mb-3">
                            <label class="form-label" for="complaint_title">Complaint Title <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="complaint_title" id="complaint_title" maxlength="190">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="complaint_description">Complaint Description <strong class="text-danger">*</strong></label>
                            <textarea class="form-control" name="complaint_description" id="complaint_description" rows="6" maxlength="500"></textarea>
                            <small class="text-danger">
                                Max 500 characters
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="complaint_document">Upload Document <span>(If Any)</span></label>
                            <input type="file" class="form-control" name="complaint_document" id="complaint_document" accept=".pdf">
                            <small class="text-danger">
                                Max Size 2MB, Only .pdf files allowed
                            </small>
                        </div>

                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                            <button type="reset" class="btn btn-default" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Complaint Description View Modal -->
<!-- Modal -->
<div class="modal fade" id="complaintDescriptionModal" tabindex="-1" aria-labelledby="complaintDescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="complaintDescriptionModalLabel">Complaint Description</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal_complaint_description_content"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pages-scripts')
<script>
    function formReset() {
        document.getElementById("addComplaintForm").reset();

        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Complaint');
        $("#addComplaintForm").validate().resetForm();
        $("#addComplaintForm").trigger('reset');
    }

    /**
     * DATATABLE
     */
    var complaintDatatable = $('#complaintDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: false,
        scrollCollapse: false,
        ordering: false,
        "ajax": {
            url: base_url + "/student/complaints/fetch-for-datatable",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.filter_institute = $('#filter_institute').val();
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
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: 'complaint_status_desc',
                name: 'id',
                className: "text-center"
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.complaint_title;
                }
            },

            {
                data: 'complaint_date',
                name: 'complaint_date',
                className: "text-center"
            },
            {
                data: 'complaint_document_button',
                name: 'complaint_document',
                className: "text-center"
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.complaint_description
                }
            },


        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        complaintDatatable.ajax.reload();
    })

    /**
     * ADD
     */
    $("#addComplaintForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            complaint_title: {
                required: true
            },
            complaint_description: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();

            var formData = new FormData(document.getElementById('addComplaintForm'));
            var url = base_url + '/student/complaints/store';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        complaintDatatable.ajax.reload();
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

    /**
     * DELETE
     */
    $(document).on('click', '.deleteComplaintBtn', function() {
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
                        url: base_url + '/student/complaints/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                complaintDatatable.ajax.reload();
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