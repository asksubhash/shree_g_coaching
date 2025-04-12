<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$page_title}}</title>

    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        body {
            font-size: 14px;
            font-family: 'Times New Roman', Times, serif;
        }

        .page-wrapper {
            padding: 0px;
        }

        .page-content-wrapper {
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

        .admit-card-header {
            background-color: #db0f0f;
            padding: 20px 20px;
            text-align: center;
            color: white;
        }

        .text-uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="page-header">
            <div class="mb-4 admit-card-header">
                <div>
                    <img src="{{ public_path('website_assets/images/site-logo-white.png') }}" alt="Image" style="width: 300px; height: auto; margin-bottom: 15px;">

                    <h3 class="text-uppercase" style="margin-bottom: 5px;">Admit Card</h3>
                    <h3 class="text-uppercase">({{ $exam->academic_year }})</h3>
                </div>
            </div>
        </div>
        <div class="page-content-wrapper">
            <div>
                <div style="width: 80%; float: left;">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="w-25"><b>ROLL NO : </b></td>
                                <td class="w-25">{{ $user->roll_number }}</td>
                                <td class="w-25"><b>D.O.B. : </b></td>
                                <td class="w-25">{{ ($user->dob)?date('d-m-Y', strtotime($user->dob)):'' }}</td>
                            </tr>
                            <tr>
                                <td><b>Student Name: </b></td>
                                <td>{{ $user->name }}</td>
                                <td class="w-25"><b>Gender : </b></td>
                                <td>{{ $user->gender }}</td>
                            </tr>
                            <tr>
                                <td><b>Father Name: </b></td>
                                <td>{{ $user->father_name }}</td>
                                <td><b>Mother Name: </b></td>
                                <td>{{ $user->mother_name }}</td>
                            </tr>
                            <tr>
                                <td><b>Address: </b></td>
                                <td>
                                    {{ $user->address }}, {{ $user->state_name }} - {{ $user->pincode }}
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Exam Centre: </b></td>
                                <td>
                                    {{ $exam->exam_centre }}
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Exam District: </b></td>
                                <td>
                                    {{ $exam->exam_district }}
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="width: 18%; margin-left: 5px; float: right;">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row txt-center">
                                    <img src="{{ storage_path('app/public/' . Config::get('constants.files_storage_path.STUDENT_PHOTO_VIEW_PATH') . '/' . $user->photo) }}" alt="Image" style="width: 100%; height: auto;">
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="clear:both; "></div>
            <div class="table-responsive" style="margin-top: 20px;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="12%">S.No.</th>
                            <th>Exam Code</th>
                            <th width="22%">Subject/Paper</th>
                            <th>Exam Date</th>
                            <th>Exam Time</th>
                            <th width="12%">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1;
                        @endphp

                        @foreach ($studentSubjects as $subject)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ (isset($subject->exam_date) && !empty($subject->exam_date))? date('d M, Y', strtotime($subject->exam_date)):'' }}</td>
                            <td>{{ $subject->exam_time }}</td>
                            <td>
                                @if($subject->exam_duration)
                                {{ $subject->exam_duration }} Hours
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @foreach ($nlStudentSubjects as $subject)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ (isset($subject->exam_date) && !empty($subject->exam_date))? date('d M, Y', strtotime($subject->exam_date)):'' }}</td>
                            <td>{{ $subject->exam_time }}</td>
                            <td>
                                @if($subject->exam_duration)
                                {{ $subject->exam_duration }} Hours
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>