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
        <div class="col-md-9 col-12">
            <div class="card p-3">

                <div class="mb-3 bg-light card border-0 card-body shadow-none">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <label class="form-label" for="filter_institute">Institute <strong class="text-danger">*</strong></label>
                            <select class="form-control select2" name="filter_institute" id="filter_institute">
                                <option value="">All</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name .' ('.$institute->institute_code.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100 nowrap" id="course-datatable">
                            <thead>
                                <tr>
                                    <th width="8%">S. No.</th>
                                    <th width="12%">Action</th>
                                    <th>Institute</th>
                                    <th>Course For</th>
                                    <th>Course Name</th>
                                    <th>Course Subjects</th>
                                    <th>Non Language Subjects</th>
                                    <th>Duration</th>
                                    <!-- <th>Description</th> -->
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card">
                <div class="card-header">
                    <strong class="form_title"> Add Course</strong>
                </div>
                <div class="card-body">
                    <form action="javascript:void(0)" class="addCourseForm" id="addCourseForm">
                        @csrf
                        <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                        <input type="hidden" name="course_id" id="course_id">

                        <div class="mb-3">
                            <label class="form-label" for="institute_id">Institute <strong class="text-danger">*</strong></label>
                            <select class="form-control select2" name="institute_id" id="institute_id">
                                <option value="">---Select---</option>
                                @foreach($institutes as $institute)
                                <option value="{{$institute->id}}">{{$institute->name .' ('.$institute->institute_code.')'}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="course_for">Course For <strong class="text-danger">*</strong></label>
                            <select class="form-control" name="course_for" id="course_for">
                                <option value="">---Select---</option>
                                <option value="TEN">10th</option>
                                <option value="TWELVE">12th</option>
                                <option value="GRADUATION">GRADUATION</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="course_name">Course Name <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="course_name" id="course_name">
                        </div>
                        <!-- <div class="mb-3 col-12">
                            <label class="form-label" for="subject">Subject <strong class="text-danger">*</strong></label>
                            <select class="form-select select2" data-placeholder="--Select--" multiple="" name="subject[]" id="subject" class="form-control" required>
                                @foreach ($subjects as $key => $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="course_code">Course Code <strong class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" name="course_code" id="course_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="duration">Duration<strong class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" name="duration" id="duration" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="amount">Amount<strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="amount" id="amount" required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="duration">Description<strong class="text-danger">*</strong></label>
                            <textarea name="description" id="description" class="form-control" rows="2" required></textarea>
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="status">Status <strong class="text-danger">*</strong></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach (Config::get('constants.status') as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary" id="formSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
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

<!-- Add Course Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="addSubjectForm" id="addSubjectForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="subject_operation_type">
                    <input type="hidden" name="hidden_course_id" id="hidden_course_id">

                    <div class="mb-3">
                        <label for="" class="form-label">Course Name:</label>
                        <input type="text" class="form-control" name="course_name_for_subject" id="course_name_for_subject" disabled />
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Subject Name:</label>
                        <select name="subject_id" id="subject_id" class="form-control" required>
                            <option value="">---Select---</option>
                            @foreach ($subjects as $key => $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Max. Marks:</label>
                        <input type="text" class="form-control" name="max_marks" id="max_marks">
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-custom" id="btnSaveSubject">
                            <i class="fa fa-save"></i> Save Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Non Language Subject Modal -->
<div class="modal fade" id="addNLSubjectModal" tabindex="-1" aria-labelledby="addNLSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addNLSubjectModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="addNLSubjectForm" id="addNLSubjectForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="nl_subject_operation_type">
                    <input type="hidden" name="hidden_course_id" id="hidden_nl_subject_course_id">

                    <div class="mb-3">
                        <label for="" class="form-label">Course Name:</label>
                        <input type="text" class="form-control" name="course_name_for_subject" id="course_name_for_nl_subject" disabled />
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Subject Name:</label>
                        <select name="subject_id" id="nl_subject_id" class="form-control" required>
                            <option value="">---Select---</option>
                            @foreach ($nlSubjects as $key => $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Max. Marks:</label>
                        <input type="text" class="form-control" name="max_marks" id="nl_max_marks">
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-custom" id="btnSaveNLSubject">
                            <i class="fa fa-save"></i> Save Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pages-scripts')
<script>
    $('#subject').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
        tags: true
    });

    var courseDataTable = $('#course-datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        "ajax": {
            url: base_url + "/ajax/get/all-courses",
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
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return (data.institute) ? data.institute.name : '';
                }
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.course_for;
                }
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    return data.course_name + ' <br /> (' + data.course_code + ')'
                }
            },

            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    if (data.course_subject_mapping.length > 0) {
                        let subjects = '';
                        data.course_subject_mapping.forEach((row, i) => {
                            subjects += `${row.subject.name} (${row.max_marks})`;
                            if (data.course_subject_mapping.length > i + 1) {
                                subjects += `,<br /> `;
                            }
                        });
                        return subjects;
                    } else {
                        return '<span class="badge bg-danger">No Subject Added</span>'
                    }
                }
            },
            {
                data: null,
                className: "text-left",
                render: function(data, type, row, meta) {
                    if (data.course_n_l_subject_mapping.length > 0) {
                        let subjects = '';
                        data.course_n_l_subject_mapping.forEach((row, i) => {
                            subjects += `${row.nl_subject.name} (${row.max_marks})`;
                            if (data.course_n_l_subject_mapping.length > i + 1) {
                                subjects += `,<br /> `;
                            }
                        });
                        return subjects;
                    } else {
                        return '<span class="badge bg-danger">No Subject Added</span>'
                    }
                }
            },
            {
                data: 'duration',
                className: "text-left"
            },
            // {
            //     data: 'description',
            //     className: "text-left"
            // },
            {
                data: 'amount',
                className: "text-left"
            },

            {
                data: 'status_desc',
                name: 'id',
                className: "text-center"
            },

        ],
        "columnDefs": [{
            "targets": ['_ALL'],
            "orderable": false,
            "sorting": false
        }],
    });

    // ===================================
    // Filter
    // ===================================
    $('#filter_institute').on('change', function() {
        courseDataTable.ajax.reload();
    })

    $("#addCourseForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            course_name: {
                required: true
            },
            course_code: {
                required: true
            },
            duration: {
                required: true
            },
            description: {
                required: true
            },
            amount: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addCourseForm'));
            // Check the operation type
            var url;
            var operationType = $('#operation_type').val();
            if (operationType == 'EDIT') {
                url = base_url + '/ajax/course/update';
            } else if (operationType == 'ADD') {
                url = base_url + '/ajax/course/store';
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
                        courseDataTable.ajax.reload();
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
    $(document).on('click', '.editCourseBtn', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: base_url + '/ajax/get/course-details',
            type: 'POST',
            data: {
                id: btoa(id),
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function(response) {

                if (response.status == true) {
                    let data = response.data;
                    // let subject = response.subjects;

                    // Set the form data
                    formReset();
                    $('#operation_type').val('EDIT');

                    // $('#institute_id option[value="' + data.institute_id + '"]').prop('selected', true);

                    $('#institute_id').val(data.institute_id).trigger('change');

                    $('#course_for option[value="' + data.course_for + '"]').prop('selected', true);

                    $('#course_id').val(btoa(data.id));
                    $('#course_name').val(data.course_name);
                    $('#course_code').val(data.course_code);
                    $('#duration').val(data.duration);
                    $('#description').val(data.description);
                    $('#amount').val(data.amount);
                    $('#status option[value="' + data.record_status + '"]').prop('selected', true);

                    // let selectedValues = subject.map(function(option) {
                    //     return option.subject_id.toString();
                    // });

                    $('.form_title').html('Edit Course');
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

    $(document).on('click', '.deleteCourseBtn', function() {
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
                        url: base_url + '/ajax/course/delete',
                        type: 'POST',
                        data: {
                            id: btoa(id),
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == true) {
                                toastr.success(response.message);
                                courseDataTable.ajax.reload();
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
        document.getElementById("addCourseForm").reset();
        $('#subject').trigger('change');
        $('#operation_type').val('ADD');
        $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');

        $('#institute_id').val(null).trigger('change');

        $('.form_title').html('Add Course');
        $("#addCourseForm").validate().resetForm();
    }

    // ----------------------------------------------
    // Add Subject
    // ----------------------------------------------

    $(document).on('click', '.btnAddSubject', function() {
        var id = $(this).attr('id');

        var rowData = courseDataTable.row($(this).closest('tr')).data();
        var courseName = rowData.course_name;

        // Now you have the course name, you can use it as needed
        // console.log('Course Name:', courseName);

        $('#course_name_for_subject').val(courseName);

        $('#subject_operation_type').val('ADD');
        $('#hidden_course_id').val(id);

        $('#addSubjectModalLabel').html('<i class="fas fa-plus"></i> Add Subject');
        $('#formSubmitBtn').html('<i class="fas fa-paper-plane"></i> Save Subject');

        $('#addSubjectModal').modal('show')
    })

    // ----------------------------------------------
    // Add Subject Form
    $("#addSubjectForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            subject_id: {
                required: true
            },
            max_marks: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addSubjectForm'));

            $.ajax({
                url: base_url + '/ajax/course/subject/store',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: response.message
                        }).then(() => {
                            courseDataTable.ajax.reload();
                            $('#subject_id option').prop('selected', false);
                            $('#max_marks').val('')
                        })

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


    // ----------------------------------------------
    // Add Non Language Subject
    // ----------------------------------------------

    $(document).on('click', '.btnAddNLSubject', function() {
        var id = $(this).attr('id');

        var rowData = courseDataTable.row($(this).closest('tr')).data();
        var courseName = rowData.course_name;

        // Now you have the course name, you can use it as needed
        // console.log('Course Name:', courseName);

        $('#course_name_for_nl_subject').val(courseName);

        $('#nl_subject_operation_type').val('ADD');
        $('#hidden_nl_subject_course_id').val(id);

        $('#addNLSubjectModalLabel').html('<i class="fas fa-plus"></i> Add Non Language Subject');
        $('#btnSaveNLSubject').html('<i class="fas fa-paper-plane"></i> Save Subject');

        $('#addNLSubjectModal').modal('show')
    })

    // ----------------------------------------------
    // Add Subject Form
    $("#addNLSubjectForm").validate({
        errorClass: 'validation-error w-100 text-danger',
        rules: {
            subject_id: {
                required: true
            },
            max_marks: {
                required: true
            },
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addNLSubjectForm'));

            $.ajax({
                url: base_url + '/ajax/course/nl-subject/store',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: response.message
                        }).then(() => {
                            courseDataTable.ajax.reload();
                            $('#nl_subject_id option').prop('selected', false);
                            $('#nl_max_marks').val('')
                        })

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
</script>
@endsection