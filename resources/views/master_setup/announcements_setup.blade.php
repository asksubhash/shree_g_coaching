@extends('layouts.master_layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
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

    <!-- Main content -->
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Announcements</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addAnnouncementsForm" id="addAnnouncementsForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="announcements_id" id="announcements_id">

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="announcement">Announcement Text <strong class="text-danger">*</strong></label>
                                    <textarea type="text" class="form-control ck_text_editor_announcement" name="announcement" id="announcement"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="from_date">From Date <strong class="text-danger">*</strong></label>
                                    <input type="date" name="from_date" id="from_date" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="to_date">To Date <strong class="text-danger">*</strong></label>
                                    <input type="date" name="to_date" id="to_date" class="form-control" />
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12 mb-3">
                                <label class="form-label" for="status">Status <strong class="text-danger">*</strong></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">--Select--</option>
                                    @foreach (Config::get('constants.status') as $key => $status)
                                    <option value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-3 text-center">
                                <button type="submit" class="btn btn-primary" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                                <button type="reset" class="btn btn-default" onclick="formReset()"><i class="bx bx-refresh"></i> Reset</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-12">
            <div class="card p-3">

                <div class="">
                    <table class="table table-bordered table-striped w-100" id="announcementsDatatable">
                        <thead>
                            <tr>
                                <th width="8%">S. No.</th>
                                <th width="12%">Action</th>
                                <th>Status</th>
                                <th width="40%">Announcement</th>
                                <th>From Date</th>
                                <th>To Date</th>
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

@endsection

@section('pages-scripts')
<script>
    function formReset() {
        document.getElementById("addAnnouncementsForm").reset();

        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('.form_title').html('Add Annoucement');
        $("#addAnnouncementsForm").validate().resetForm();
        $("#addAnnouncementsForm").trigger('reset');
    }

    var announcementsDatatable = $('#announcementsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: false,
        scrollCollapse: false,
        ordering: false,
        "ajax": {
            url: base_url + "/announcements/fetch-for-datatable",
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
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },

            {
                data: 'announcement',
                name: 'id',
                className: "text-left"
            },
            {
                data: 'from_date',
                name: 'from_date',
                className: "text-center"
            },
            {
                data: 'to_date',
                name: 'to_date',
                className: "text-center"
            },
        ]
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        announcementsDatatable.ajax.reload();
    })

    let editorInstance;
    // CKEDITOR START
    $(document).ready(function() {
        // Check if the element exists
        if ($(".ck_text_editor_announcement").length) {
            // Loop through each element with the class 'ck_text_editor_announcement'
            $(".ck_text_editor_announcement").each(function(index) {
                // Create CKEditor instance for the current element
                ClassicEditor.create(this, {
                        fontSize: {
                            options: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                        },
                        htmlSupport: {
                            allow: [{
                                name: /.*/,
                                attributes: true,
                                classes: true,
                                styles: true,
                            }, ],
                        },
                    })
                    .then((editor) => {
                        editorInstance = editor;
                        // Set the height dynamically or perform other operations
                        editor.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "250px",
                                editor.editing.view.document.getRoot()
                            );
                        });
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        $("#addAnnouncementsForm").validate({
            errorClass: 'validation-error w-100 text-danger',
            rules: {
                announcement_id: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('addAnnouncementsForm'));
                // Check the operation type
                var url;
                var operationType = $('#operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/announcements/update';
                } else if (operationType == 'ADD') {
                    url = base_url + '/announcements/store';
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
                            announcementsDatatable.ajax.reload();
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
        $(document).on('click', '.editAnnouncementsBtn', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: base_url + '/announcements/get-details',
                type: 'POST',
                data: {
                    id: btoa(id),
                    _token: $('meta[name=csrf-token]').attr('content')
                },
                success: function(response) {

                    if (response.status == true) {
                        let data = response.data;

                        // Set the form data
                        formReset();
                        $('#operation_type').val('EDIT');
                        $('#announcements_id').val(btoa(data.id));

                        // Set CKEditor data
                        if (editorInstance) {
                            editorInstance.setData(data.announcement);
                        }

                        // $('#announcement').val(data.announcement);
                        $('#from_date').val(data.from_date);
                        $('#to_date').val(data.to_date);

                        $('#status option[value="' + data.record_status + '"]').prop('selected', true);

                        $('.form_title').html('Edit Annoucement');
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

        $(document).on('click', '.deleteAnnouncementsBtn', function() {
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
                            url: base_url + '/announcements/delete',
                            type: 'POST',
                            data: {
                                id: btoa(id),
                                _token: $('meta[name=csrf-token]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == true) {
                                    toastr.success(response.message);
                                    announcementsDatatable.ajax.reload();
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
    });
    // CKEDITOR END
</script>
@endsection