@extends('layouts.master_layout')
@section('content')
<div class="page-content">
    <!-- Content Header (Page header) -->
    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="page-title flex-fill mb-0 fw-semibold">
            {{ $page_title }}
        </h4>
        <div class="page-header-content-col">
            <a href="{{url('download/student-result/' . $studentResult[0]->exam_id . '/' . $studentResult[0]->student_roll_no)}}" class="btn btn-danger btn-sm" target="_BLANK">
                <i class="bx bx-download"></i> Download Result
            </a>
        </div>
    </div>

    <!-- /.content-header -->
    <!-- Main content -->
    <div class="row">
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
                                    MCS Sem1
                                </td>
                            </tr>
                            <tr>
                                <th>Roll No.</th>
                                <td>
                                    {{$studentResult[0]->student_roll_no}}
                                </td>
                            </tr>
                            <tr>
                                <th>Student Name</th>
                                <td>
                                    Manoj kumar yadav
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Father Name
                                </th>
                                <td>
                                    Late Ayodhya Prasad
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Exam Date
                                </th>
                                <td>
                                    DT-25,26,27,28 August 2023
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Date of publication
                                </th>
                                <td>
                                    20.10.2023
                                </td>
                            </tr>
                            <tr>
                                <th>Exam Centre</th>
                                <td>
                                    Mangraajpur
                                </td>
                            </tr>
                            <tr>
                                <th>Dist</th>
                                <td>
                                    Baleshwar
                                </td>
                            </tr>
                            <tr>
                                <th>Certificate</th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="marksEntryDatatable">
                        <thead>
                            <tr>
                                <th width="10%">S. No.</th>
                                <th>Subject Name</th>
                                <th>Full Marks</th>
                                <th>Marks Obtained</th>
                                <th class="text-center">Division</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($studentResult) > 0)
                            @php
                            $total_max_marks = 0;
                            $total_obtained_marks = 0;
                            @endphp
                            @foreach ($studentResult as $key => $result)
                            <tr>
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$result->subject->name}}
                                </td>
                                <td>
                                    {{$result->max_marks}}

                                    @php
                                    $total_max_marks += $result->max_marks;
                                    @endphp
                                </td>
                                <td>
                                    {{$result->marks_obtained}}

                                    @php
                                    $total_obtained_marks += $result->marks_obtained;
                                    @endphp
                                </td>
                                @if($key == 0)
                                <th class="text-center" rowspan="{{count($studentResult)+1}}" valign="middle">
                                    First Division
                                </th>
                                @endif
                                <td>
                                    <button class="btn btn-warning btn-sm" type="button">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>

                                </td>
                                <th>Total</th>
                                <td>
                                    {{$total_max_marks}}
                                </td>
                                <td>
                                    {{$total_obtained_marks}}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2">
                                    Exam Controller
                                </th>
                                <td colspan="4">
                                    Gobinda Chandra Pradhan
                                </td>
                            </tr>
                            @endif
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