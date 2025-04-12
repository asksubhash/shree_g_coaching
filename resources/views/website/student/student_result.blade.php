@extends('layouts.print_website_layout')
@section('content')

<div class="pt-3 pb-5 px-5">
    <div class="card p-3 border-0 shadow">

        <div id="print-section" class="w-100">
            <div class="result-header text-center py-2" style="margin-bottom: 20px;">
                <img src="{{ asset('website_assets/images/site-logo.png') }}" alt="" style="height: 80px; object-fit: contain;">
            </div>

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

        <div class="mt-3 text-center">
            <button type="button" class="btn btn-custom" onclick="printPage()">
                <i class="fa fa-print"></i> Print Result
            </button>
        </div>

    </div>
</div>


@endsection
@section('pages-scripts')
<script></script>
@endsection