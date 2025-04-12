<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$page_title}}</title>

    {{--
    <link rel="stylesheet" href="{{asset('assets/css/export_pdf.css')}}" type="text/css" /> --}}

    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        body {
            font-size: 14px;
        }

        .page-wrapper {
            padding: 50px;
        }

        .table {
            border-collapse: collapse;
            border-spacing: 0px;
            width: 100%;
        }

        .table-bordered tr th,
        .table-bordered tr td {
            border: 1px solid rgb(230, 230, 230);
            padding: 10px 15px;
            text-align: left;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgb(241, 241, 241);
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="page-header"></div>
        <div class="page-content-wrapper">
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
            <div class="table-responsive" style="margin-top: 10px;">
                <table class="table table-bordered table-striped" id="marksEntryDatatable">
                    <thead>
                        <tr>
                            <th width="10%">S. No.</th>
                            <th>Subject Name</th>
                            <th>Full Marks</th>
                            <th>Marks Obtained</th>
                            <th class="text-center">Division</th>
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
                            <td colspan="3">
                                Gobinda Chandra Pradhan
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>