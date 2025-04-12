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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="user-datatable">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">S. No.</th>
                                <th class="text-center">Student Roll No</th>
                                <th class="text-center">Student Name</th>
                                <th class="text-center">Father Name</th>
                                <th class="text-center">Exam Name</th>
                                <th>Date</th>
                                <th>District</th>
                                <th>Publication Date</th>
                                <th>Action</th>
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
<div class="modal fade" id="addResultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Upload Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form id="add_result_form">
                    @csrf
                    <div class="row">
                        <div class=" col-12  my-1">
                            <div class="mb-3">
                                <label for="" class="form-label">Download template to upload results</label>
                                <div>
                                    <a href="#" id="btnDownloadTemplate">
                                        <i class="bx bx-download"></i> Download Template
                                    </a>
                                    <p class="mb-1 mt-2">
                                        While uploading results, you should download template and fill the results into
                                        the
                                        template file and upload it.
                                    </p>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="" class="form-label">Upload Result File</label>
                                <input type="file" name="file" id="file" class="form-control" />
                            </div>
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Upload Result <i class="bx bx-pencil"></i>
                                </button>
                            </div>
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
        "scrollX": false,
        "ordering": false,
        "autoWidth": false,
        "responsive": false,
        "ajax": {
            url: base_url + "/result-entry/get-datatable-data",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');

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
                data: 'student_roll_no',
                name: 'student_roll_no',
                className: "text-center"
            },
            {
                data: 'student_name',
                name: 'osd.student_name',
                className: "text-center"
            },
            {
                data: 'father_name',
                name: 'osd.father_name',
                className: "text-center"
            },
            {
                data: 'exam_type',
                name: 'exam_type',
                className: "text-center"
            },
            {
                data: 'exam_date',
                name: 'osd.exam_date',
                className: "text-center"
            },
            {
                data: 'exam_dist',
                name: 'osd.exam_dist',
                className: "text-center"
            },
            {
                data: 'publication_date',
                name: 'osd.publication_date',
                className: "text-center"
            },
            {
                data: 'action',
                name: 'id',
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
            '<button class="btn btn-primary" id="add_result_btn"><i class="bx bx-plus"></i> Add Result</button>'
        );
    })

    // On click add button, open the modal
    $(document).on('click', '#add_result_btn', function() {
        $('#addResultModal').modal('show');
    })

    $.validator.addMethod("excelFile", function(value, element) {
        // Use a regex to check if the file extension is xls or xlsx
        return this.optional(element) || /\.(xls|xlsx)$/i.test(value);
    }, "Please choose a valid Excel file.");


    // On submitting the form
    $("#add_result_form").validate({
        errorClass: "text-danger validation-error",
        rules: {
            file: {
                required: true,
                excelFile: true
            },
        },
        messages: {
            file: {
                required: "Please select a file."
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('add_result_form'));
            // Check the operation type
            var url = base_url + '/result-entry/store';

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
                        $('#addResultModal').modal('hide');
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

    $('#addResultModal').on('hidden.bs.modal', function() {
        $('#add_result_form').trigger('reset');
    });

    $('#btnDownloadTemplate').on('click', function() {
        window.open(`${base_url}/result-entry/download-template`, '_BLANK');
    })
</script>
@endsection