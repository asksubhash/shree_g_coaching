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
            <div class="card">
                <div class="card-body">
                    Click here to edit the result:
                    <a href="{{ url('result-entry/edit/student-result?exam_type=' . $studentResult[0]->exam_type . '&student_roll_number=' . $studentResult[0]->student_roll_no) }}" class="btn btn-warning btn-sm">
                        <i class="bx bx-download"></i> Edit Result
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card p-3">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    Exam Name
                                </th>
                                <td>
                                    {{ $studentResult[0]->exam_type }}
                                </td>
                            </tr>
                            <tr>
                                <th>Roll No.</th>
                                <td>
                                    {{ $studentResult[0]->student_roll_no }}
                                </td>
                            </tr>
                            <tr>
                                <th>Student Name</th>
                                <td>
                                    {{ $studentResult[0]->student_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Father Name
                                </th>
                                <td>
                                    {{ $studentResult[0]->father_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Exam Date
                                </th>
                                <td>
                                    {{ $studentResult[0]->exam_date }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Date of publication
                                </th>
                                <td>
                                    {{ $studentResult[0]->publication_date }}
                                </td>
                            </tr>
                            <tr>
                                <th>Exam Centre</th>
                                <td>
                                    {{ $studentResult[0]->exam_center }}
                                </td>
                            </tr>
                            <tr>
                                <th>Dist</th>
                                <td>
                                    {{ $studentResult[0]->exam_dist }}
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
                            @if($sr->subject_name != 'Oral')
                            <tr>
                                <td>
                                    {{ $sr->subject_name }}
                                </td>
                                <td>
                                    {{ $sr->total_mark }}

                                    @php
                                    $total_marks += $sr->total_mark;
                                    @endphp
                                </td>
                                <td>
                                    {{ $sr->mark_obtained }}

                                    @php
                                    $mark_obtained += $sr->mark_obtained;
                                    @endphp
                                </td>

                                @if($key == 0)
                                <td rowspan="{{count($studentResult)+1}}" valign="middle" class="text-center">
                                    {{ $sr->exam_division }}
                                </td>
                                @endif

                            </tr>
                            @endif
                            @endforeach


                            <tr>

                                <th>Total</th>
                                <td>
                                    {{ $total_marks }}
                                </td>
                                <td>
                                    {{ $mark_obtained }}
                                </td>
                            </tr>

                            @foreach($studentResult as $key => $sr)
                            @if($sr->subject_name == 'Oral')
                            <tr>
                                <td>
                                    {{ $sr->subject_name }}
                                </td>
                                <td>
                                    {{ $sr->total_mark }}
                                </td>
                                <td>
                                    {{ $sr->mark_obtained }}
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            <tr>
                                <th colspan="2">
                                    Exam Controller
                                </th>
                                <td colspan="4">
                                    {{$studentResult[0]->exam_controller}}
                                </td>
                            </tr>
                        </tbody>
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
    // -----------------------------------------
    // var marksEntryDatatable = $('#marksEntryDatatable').DataTable({
    //     "autoWidth": false,
    //     "responsive": false,
    //     "ordering": false,
    // });
</script>
@endsection