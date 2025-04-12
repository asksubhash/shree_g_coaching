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
            <input type="hidden" name="exam_id" id="exam_id" value="{{ $exam_id }}">
            <input type="hidden" name="subject_type" id="subject_type" value="{{ $subject_type }}">
            <input type="hidden" name="subject_id" id="subject_id" value="{{ $subject_id }}">

            <ul class="nav nav-tabs nav-danger" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                            </div>
                            <div class="tab-title">Text Questions Setup</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                            </div>
                            <div class="tab-title">MCQ Questions Setup</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade active show" id="dangerhome" role="tabpanel">
                    <!-- Text Questions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card p-3">
                                <div class="card-header">
                                    <strong class="form_title"> Text Questions</strong>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <table class="table table-bordered table-striped w-100 " id="textQuestionsDatatable">
                                            <thead>
                                                <tr>
                                                    <th width="8%">S. No.</th>
                                                    <th width="12%">Action</th>
                                                    <th>Question</th>
                                                    <th width="20%">Question Image</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="form_title" id="text_question_form_title"> Add Text Questions</strong>
                                </div>
                                <div class="card-body">
                                    <form action="javascript:void(0)" class="textQuestionsForm" id="textQuestionsForm">
                                        @csrf
                                        <input type="hidden" name="operation_type" id="text_question_operation_type" value="ADD">
                                        <input type="hidden" name="hidden_id" id="hidden_text_question_id" value="">
                                        <input type="hidden" name="exam_id" value="{{ $exam_id }}">
                                        <input type="hidden" name="subject_type" value="{{ $subject_type }}">
                                        <input type="hidden" name="subject_id" value="{{ $subject_id }}">

                                        <div class="mb-3">
                                            <label class="form-label" for="question_text">Qeustion Text</label>
                                            <textarea class="form-control ck_text_editor_text_questions" name="question_text" id="question_text"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="question_image">Question Image</label>
                                            <input type="file" class="form-control" name="question_image" id="question_image" accept=".jpg,.png,.jpeg">
                                            <small class="text-danger">
                                                Max Size 10MB, Only .jpg,.png,.jpeg files allowed
                                            </small>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <button type="submit" class="btn btn-primary" id="textQuestionFormSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                                            <button type="reset" class="btn btn-default" onclick="textQuestionFormReset()"><i class="bx bx-refresh"></i> Reset</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="dangerprofile" role="tabpanel">
                    <!-- MCQ Qustions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card p-3">
                                <div class="card-header">
                                    <strong class="form_title"> MCQ Questions</strong>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped w-100" id="mcqQuestionsDatatable">
                                            <thead>
                                                <tr>
                                                    <th width="8%">S. No.</th>
                                                    <th width="12%">Action</th>
                                                    <th>Question</th>
                                                    <th>Question Image</th>
                                                    <th>Option 1</th>
                                                    <th>Option 1 Image</th>
                                                    <th>Option 2</th>
                                                    <th>Option 2 Image</th>
                                                    <th>Option 3</th>
                                                    <th>Option 3 Image</th>
                                                    <th>Option 4</th>
                                                    <th>Option 4 Image</th>
                                                    <th>Correct Answer</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="form_title" id="mcq_question_form_title"> Add MCQ Questions</strong>
                                </div>
                                <div class="card-body">
                                    <form action="javascript:void(0)" class="mcqQuestionsForm" id="mcqQuestionsForm">
                                        @csrf
                                        <input type="hidden" name="operation_type" id="mcq_question_operation_type" value="ADD">
                                        <input type="hidden" name="hidden_id" id="hidden_mcq_question_id" value="">
                                        <input type="hidden" name="exam_id" value="{{ $exam_id }}">
                                        <input type="hidden" name="subject_type" value="{{ $subject_type }}">
                                        <input type="hidden" name="subject_id" value="{{ $subject_id }}">

                                        <div class="mb-3">
                                            <label class="form-label" for="question_mcq">Qeustion MCQ</label>
                                            <textarea class="form-control ck_mcq_editor_mcq_questions" name="question_mcq" id="question_mcq"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="question_image">Question Image</label>
                                            <input type="file" class="form-control" name="question_image" id="question_image" accept=".jpg,.png,.jpeg">
                                            <small class="text-danger">
                                                Max Size 10MB, Only .jpg,.png,.jpeg files allowed
                                            </small>
                                        </div>

                                        <h5 class="text-danger text-center text-uppercase">
                                            ------- Options -------
                                        </h5>

                                        <div class="row">
                                            <!-- Option 1 -->
                                            <div class="col-md-6 col-12 mb-3">
                                                <fieldset class="fieldset">
                                                    <legend class="legend">
                                                        Option 1
                                                    </legend>
                                                    <div class="fieldset-content-col">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_1">Option 1</label>
                                                            <input type="text" class="form-control" name="option_1" id="option_1" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_1_image">Option 1 Image</label>
                                                            <input type="file" class="form-control" name="option_1_image" id="option_1_image" accept=".jpg,.png,.jpeg">
                                                            <small class="text-danger">
                                                                Max Size 10MB, Only .jpg,.png,.jpeg files allowed
                                                            </small>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <!-- Option 2 -->
                                            <div class="col-md-6 col-12 mb-3">
                                                <fieldset class="fieldset">
                                                    <legend class="legend">
                                                        Option 2
                                                    </legend>
                                                    <div class="fieldset-content-col">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_2">Option 2</label>
                                                            <input type="text" class="form-control" name="option_2" id="option_2" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_2_image">Option 2 Image</label>
                                                            <input type="file" class="form-control" name="option_2_image" id="option_2_image" accept=".jpg,.png,.jpeg">
                                                            <small class="text-danger">
                                                                Max Size 10MB, Only .jpg,.png,.jpeg files allowed
                                                            </small>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <!-- Option 3 -->
                                            <div class="col-md-6 col-12 mb-3">
                                                <fieldset class="fieldset">
                                                    <legend class="legend">
                                                        Option 3
                                                    </legend>
                                                    <div class="fieldset-content-col">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_3">Option 3</label>
                                                            <input type="text" class="form-control" name="option_3" id="option_3" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_3_image">Option 3 Image</label>
                                                            <input type="file" class="form-control" name="option_3_image" id="option_3_image" accept=".jpg,.png,.jpeg">
                                                            <small class="text-danger">
                                                                Max Size 10MB, Only .jpg,.png,.jpeg files allowed
                                                            </small>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <!-- Option 4 -->
                                            <div class="col-md-6 col-12 mb-3">
                                                <fieldset class="fieldset">
                                                    <legend class="legend">
                                                        Option 4
                                                    </legend>
                                                    <div class="fieldset-content-col">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_4">Option 4</label>
                                                            <input type="text" class="form-control" name="option_4" id="option_4" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label" for="option_4_image">Option 4 Image</label>
                                                            <input type="file" class="form-control" name="option_4_image" id="option_4_image" accept=".jpg,.png,.jpeg">
                                                            <small class="text-danger">
                                                                Max Size 10MB, Only .jpg,.png,.jpeg files allowed
                                                            </small>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <h5 class="text-danger text-center text-uppercase">
                                            ------- Correct Answer -------
                                        </h5>

                                        <div class="mb-3">
                                            <label class="form-label" for="correct_answer">Correct Answer</label>
                                            <select class="form-control" name="correct_answer" id="correct_answer">
                                                <option value="">Select</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <button type="submit" class="btn btn-primary" id="mcqQuestionFormSubmitBtn"><i class="bx bx-paper-plane"></i> Submit</button>
                                            <button type="reset" class="btn btn-default" onclick="mcqQuestionFormReset()"><i class="bx bx-refresh"></i> Reset</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
    let textQuestionEditorInstance;

    function textQuestionFormReset() {
        textQuestionEditorInstance.setData("");
        $('#text_question_operation_type').val('ADD');
        $('#textQuestionFormSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('#text_question_form_title').html('Add Text Question');
        $("#textQuestionsForm").validate().resetForm();
        $("#textQuestionsForm").trigger('reset');
    }

    function mcqQuestionFormReset() {
        $('#mcq_question_operation_type').val('ADD');
        $('#mcqQuestionFormSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
        $('#mcq_question_form_title').html('Add MCQ Question');
        $("#mcqQuestionsForm").validate().resetForm();
        $("#mcqQuestionsForm").trigger('reset');
    }

    /** Datatable for text type questions */
    var textQuestionsDatatable = $('#textQuestionsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: false,
        scrollCollapse: false,
        ordering: false,
        "ajax": {
            url: base_url + "/exams-setup/subjects/question-setup/text-questions-datatable",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.exam_id = $('#exam_id').val();
                d.subject_type = $('#subject_type').val();
                d.subject_id = $('#subject_id').val();
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
                data: 'question_text',
                name: 'question_text',
            },
            {
                data: 'question_image_button',
                name: 'question_image',
                className: "text-center"
            }
        ]
    });


    $(document).ready(function() {
        // CKEDITOR START
        // Check if the element exists
        if ($(".ck_text_editor_text_questions").length) {
            // Loop through each element with the class 'ck_text_editor_text_questions'
            $(".ck_text_editor_text_questions").each(function(index) {
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
                        textQuestionEditorInstance = editor;
                        // Set the height dynamically or perform other operations
                        editor.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "150px",
                                editor.editing.view.document.getRoot()
                            );
                        });
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        /** On submit the text question form */
        $("#textQuestionsForm").validate({
            errorClass: 'validation-error w-100 text-danger',
            rules: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('textQuestionsForm'));
                // Check the operation type
                var url;
                var operationType = $('#text_question_operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/exams-setup/subjects/question-setup/update-text-question';
                } else if (operationType == 'ADD') {
                    url = base_url + '/exams-setup/subjects/question-setup/store-text-question';
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
                            textQuestionsDatatable.ajax.reload();
                            toastr.success(response.message);
                            textQuestionFormReset();
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
        $(document).on('click', '.editTextQuestion', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: base_url + '/exams-setup/subjects/question-setup/fetch-text-question-by-id',
                type: 'POST',
                data: {
                    id: btoa(id),
                    _token: $('meta[name=csrf-token]').attr('content')
                },
                success: function(response) {

                    if (response.status == true) {
                        let data = response.data;

                        // Set the form data
                        textQuestionFormReset();
                        $('#text_question_operation_type').val('EDIT');

                        $('#hidden_text_question_id').val(btoa(data.id));
                        // Set CKEditor data
                        if (textQuestionEditorInstance) {
                            textQuestionEditorInstance.setData(data.question_text);
                        }

                        // $('#question_text').val(data.question_text);

                        $('#text_question_form_title').html('Edit Text Question');
                        $('#textQuestionFormSubmitBtn').html('<i class="bx bx-edit"></i> Update');

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

        $(document).on('click', '.deleteTextQuestion', function() {
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
                            url: base_url + '/exams-setup/subjects/question-setup/delete-text-question',
                            type: 'POST',
                            data: {
                                id: btoa(id),
                                _token: $('meta[name=csrf-token]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == true) {
                                    toastr.success(response.message);
                                    textQuestionsDatatable.ajax.reload();
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


    // ==========================================================
    /** Datatable for mcq type questions */
    // ==========================================================
    var mcqQuestionsDatatable = $('#mcqQuestionsDatatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: false,
        scrollCollapse: false,
        ordering: false,
        "ajax": {
            url: base_url + "/exams-setup/subjects/question-setup/mcq-questions-datatable",
            type: 'POST',
            data: function(d) {
                d._token = $('meta[name=csrf-token]').attr('content');
                d.exam_id = $('#exam_id').val();
                d.subject_type = $('#subject_type').val();
                d.subject_id = $('#subject_id').val();
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
                data: 'question_text',
                name: 'question_text'
            },
            {
                data: 'question_image_button',
                name: 'question_image',
                className: "text-center"
            },
            {
                data: 'option_1',
                name: 'option_1',
            },
            {
                data: 'option_1_image_button',
                name: 'option_1_image',
                className: "text-center"
            },
            {
                data: 'option_2',
                name: 'option_2',
            },
            {
                data: 'option_2_image_button',
                name: 'option_2_image',
                className: "text-center"
            },
            {
                data: 'option_3',
                name: 'option_3',
            },
            {
                data: 'option_3_image_button',
                name: 'option_3_image',
                className: "text-center"
            },
            {
                data: 'option_4',
                name: 'option_4',
            },
            {
                data: 'option_4_image_button',
                name: 'option_4_image',
                className: "text-center"
            },
            {
                data: 'correct_answer',
                name: 'correct_answer',
                className: "text-center"
            }
        ]
    });

    $(document).ready(function() {
        // CKEDITOR START
        // Check if the element exists
        if ($(".ck_mcq_editor_mcq_questions").length) {
            // Loop through each element with the class 'ck_mcq_editor_mcq_questions'
            $(".ck_mcq_editor_mcq_questions").each(function(index) {
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
                        mcqQuestionEditorInstance = editor;
                        // Set the height dynamically or perform other operations
                        editor.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "150px",
                                editor.editing.view.document.getRoot()
                            );
                        });
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        /** On submit the mcq question form */
        $("#mcqQuestionsForm").validate({
            errorClass: 'validation-error w-100 mcq-danger',
            rules: {},
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('mcqQuestionsForm'));
                // Check the operation type
                var url;
                var operationType = $('#mcq_question_operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/exams-setup/subjects/question-setup/update-mcq-question';
                } else if (operationType == 'ADD') {
                    url = base_url + '/exams-setup/subjects/question-setup/store-mcq-question';
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
                            mcqQuestionsDatatable.ajax.reload();
                            toastr.success(response.message);
                            mcqQuestionFormReset();
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
        $(document).on('click', '.editMcqQuestion', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: base_url + '/exams-setup/subjects/question-setup/fetch-mcq-question-by-id',
                type: 'POST',
                data: {
                    id: btoa(id),
                    _token: $('meta[name=csrf-token]').attr('content')
                },
                success: function(response) {

                    if (response.status == true) {
                        let data = response.data;

                        // Set the form data
                        mcqQuestionFormReset();
                        $('#mcq_question_operation_type').val('EDIT');

                        $('#hidden_mcq_question_id').val(btoa(data.id));
                        // Set CKEditor data
                        if (mcqQuestionEditorInstance) {
                            mcqQuestionEditorInstance.setData(data.question_mcq);
                        }

                        // $('#question_mcq').val(data.question_mcq);

                        $('#mcq_question_form_title').html('Edit MCQ Question');
                        $('#mcqQuestionFormSubmitBtn').html('<i class="bx bx-edit"></i> Update');

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

        $(document).on('click', '.deleteMcqQuestion', function() {
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
                            url: base_url + '/exams-setup/subjects/question-setup/delete-text-question',
                            type: 'POST',
                            data: {
                                id: btoa(id),
                                _token: $('meta[name=csrf-token]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == true) {
                                    toastr.success(response.message);
                                    mcqQuestionsDatatable.ajax.reload();
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
</script>
@endsection