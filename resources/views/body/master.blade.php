<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>{{ ucfirst(basename($_SERVER['PHP_SELF'])) }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">


    <style type="text/css">
        .swal2-warning {
            display: flex;
            width: 60% !important;
            margin: unset !important;
            margin: 20px 80px !important;
        }
    </style>

</head>

<body>

    @include('sweetalert::alert')
    @include('body.header')
    <div class="wrapper">
        @include('body.sidebar')
        @yield('admin')
    </div>
    @include('body.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });

        // Check if $data is defined in the Blade template
        @if (isset($data))
            var chartData = @json($data);
        @else
            var chartData = null;
        @endif

        google.charts.setOnLoadCallback(function() {
            drawChart(chartData);
        });
    </script>
</body>

</html>
