@extends('layouts.master_layout')
@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="page-title flex-fill mb-0 fw-semibold">
            {{ $page_title }}
        </h4>
        <div class="page-header-content-col">
        </div>
    </div>

    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">

        <div class="col-12">
            <form action="" id="studentResultUpdateForm" method="POST">
                @csrf
                <input type="hidden" class="form-control" name="exam_type" value="{{ $studentResult[0]->exam_type }}">
                <input type="hidden" class="form-control" name="student_roll_no" value="{{ $studentResult[0]->student_roll_no }}">
                <input type="hidden" class="form-control" name="osd_id" value="{{ $studentResult[0]->osd_id }}">


                <div class="card p-3">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        Exam Name
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $studentResult[0]->exam_type }}" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Roll No.</th>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $studentResult[0]->student_roll_no }}" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Student Name</th>
                                    <td>
                                        <input type="text" class="form-control" name="student_name" value="{{ $studentResult[0]->student_name }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Father Name
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="father_name" value="{{ $studentResult[0]->father_name }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Exam Date
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="exam_date" value="{{ $studentResult[0]->exam_date }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Date of publication
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="publication_date" value="{{ $studentResult[0]->publication_date }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Exam Centre</th>
                                    <td>
                                        <input type="text" class="form-control" name="exam_center" value="{{ $studentResult[0]->exam_center }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dist</th>
                                    <td>
                                        <input type="text" class="form-control" name="exam_dist" value="{{ $studentResult[0]->exam_dist }}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped" id="marksEntryDatatable">
                            <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Full Marks</th>
                                    <th>Marks Obtained</th>
                                    <th class="text-center">Division</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_marks = 0;
                                $mark_obtained = 0;
                                @endphp
                                @foreach($studentResult as $key => $sr)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="subject_name_{{ $sr->id }}" value="{{ $sr->subject_name }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="total_mark_{{ $sr->id }}" value="{{ $sr->total_mark }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="mark_obtained_{{ $sr->id }}" value="{{ $sr->mark_obtained }}">
                                    </td>
                                    @if($key == 0)
                                    <td rowspan="{{count($studentResult)}}" valign="middle" class="text-center">
                                        <input type="text" class="form-control" name="exam_division" value="{{ $sr->exam_division }}">
                                    </td>
                                    @endif
                                </tr>
                                @endforeach

                                <tr>
                                    <th colspan="2">
                                        Exam Controller
                                    </th>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="exam_controller" value="{{$studentResult[0]->exam_controller}}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-custom">
                            <i class="bx bx-edit"></i> Update Result
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('pages-scripts')
<script>
    $("#studentResultUpdateForm").validate({
        errorClass: "text-danger validation-error",
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('studentResultUpdateForm'));
            $(".loader").show();
            let formUrl = base_url + '/result-entry/update/student-result';
            $.ajax({
                url: formUrl,
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    $(".loader").hide();
                    var data = response;
                    if (data.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: data.message,
                        }).then((result) => {
                            window.location.reload();
                        });
                    } else if (data.status == 'validation_errors') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: data.message
                        })
                    } else if (data.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        })
                    } else {
                        toastr.error('Something went wrong. Please try again.')
                    }
                },
                error: function(error) {
                    $(".loader").hide();
                    toastr.error(error.statusText)
                }
            })
        }
    });
</script>
@endsection