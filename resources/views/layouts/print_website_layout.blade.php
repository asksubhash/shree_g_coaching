<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>

    <!-- Link to your regular stylesheet -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('website_assets/css/style.css') }}">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />

    <!-- Print stylesheet -->
    <style type="text/css" media="print">
        /* Styles for printing */
        body {
            counter-reset: page;
        }

        body * {
            visibility: hidden;
        }

        /* Hide date, title, and URL in print */
        .no-print {
            display: none !important;
        }

        /* Print page number in the footer */
        .page-number::after {
            content: counter(page);
        }

        @page {
            size: auto;
            /* Set page size to auto */
            margin: 0mm;
            /* Set margins to zero */
        }

        #print-section {
            padding: 0px;
        }

        #print-section,
        #print-section * {
            visibility: visible;
        }

        #print-section {
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>
</head>

<body>

    @yield('content')

    <script>
        function printPage() {
            window.print();
        }
    </script>

</body>

</html>