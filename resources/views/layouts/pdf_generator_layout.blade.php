<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>

    <style>
        body {
            font-family: freesans;
        }

        strong {
            font-weight: bold;
        }

        label {
            font-weight: bold;
            float: unset;
        }

        .w-25 {
            width: 25%;
        }

        .w-33 {
            width: 33%;
        }

        .w-50 {
            width: 50%;
        }

        .w-75 {
            width: 75%;
        }

        .w-100 {
            width: 100%;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mt-3 {
            margin-top: 10px;
        }

        .text-danger {
            color: red;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .bg-lightgray {
            background-color: lightgrey;
        }

        thead {
            display: table-header-group;
            /* Display table header only once */
        }

        .table-for-students-data {
            border-spacing: 0;
            width: 100%;
            border: 1px solid lightgrey;
        }

        .table-for-students-data tr td,
        .table-for-students-data tr th {
            padding: 8px 8px;
        }

        .table-for-students-data tr th {
            text-align: left;
        }

        .table-for-students-data tr td,
        .table-for-students-data tr th {
            border: 1px solid lightgrey !important;
        }

        .form-label {
            font-weight: bold !important;
            margin-bottom: 10px;
        }

        .stu-profile-img {
            width: 100px;
            border: 1px solid #191919;
            background-color: lightgrey;
        }

        .logo-img {
            width: 100%;
        }

        .stu-card-title {
            color: red;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>