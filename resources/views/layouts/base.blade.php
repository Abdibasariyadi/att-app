<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <script src="{{URL::asset('assets/jquery-3.3.1.min.js')}}"></script>
    <script src="{{URL::asset('assets/popper.min.js')}}" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{URL::asset('assets/bootstrap.min.js')}}" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{URL::asset('assets/jquery.nicescroll.min.js')}}"></script>
    <script src="{{URL::asset('assets/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('assets/datatables.min.js')}}"></script>
    <script src="{{URL::asset('assets/jquery.validate.js')}}"></script>
    <script src="{{URL::asset('assets/additional-methods.min.js')}}"></script>

    <script src="{{URL::asset('assets/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/buttons.flash.min.js')}}"></script>
    <script src="{{URL::asset('assets/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/buttons.print.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('assets/daterangepicker.min.js')}}"></script>
    <script src="{{URL::asset('assets/highcharts/highcharts.js')}}"></script>
    <script src="{{URL::asset('assets/highcharts/series-label.js')}}"></script>
    <script src="{{URL::asset('assets/highcharts/exporting.js')}}"></script>
    <script src="{{URL::asset('assets/highcharts/export-data.js')}}"></script>
    <script src="{{URL::asset('assets/highcharts/accessibility.js')}}"></script>

    <!-- MULAI DATEPICKER JS -->
    <link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.css')}}">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
    <script src="{{URL::asset('assets/jquery-ui.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{URL::asset('assets/css/css.css')}}" rel="stylesheet">

    <!-- Styles -->
    @yield('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{URL::asset('assets/css/all.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/datatables.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('assets/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('assets/css/buttons.dataTables.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/daterangepicker.css')}}" />

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">

    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>

            @yield('body')

        </div>
    </div>
    <!-- General JS Scripts -->
    <!-- <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> -->


    <script src="{{asset('assets/js/stisla.js')}}"></script>

    <!-- JS Libraies -->

    <!-- Template JS File -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    @stack('scripts')

</body>

</html>