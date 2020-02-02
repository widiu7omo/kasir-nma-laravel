<!--
=========================================================
 Paper Dashboard - v2.0.0
=========================================================

 Product Page: https://www.creative-tim.com/product/paper-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com)
 UPDIVISION (https://updivision.com)
 Licensed under MIT (https://github.com/creativetimofficial/paper-dashboard/blob/master/LICENSE)

 Coded by Creative Tim

=========================================================

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('paper') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('paper') }}/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ __('Paper Dashboard by Creative Tim') }}
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{ asset('paper') }}/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ asset('paper') }}/css/paper-dashboard.css?v=2.0.0" rel="stylesheet"/>
    <link href="{{asset('paper')}}/vendors/DataTables/datatables.min.css" rel="stylesheet">
    <link href="{{asset('paper')}}/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('paper') }}/demo/demo.css" rel="stylesheet"/>

</head>

<body class="{{ $class }}">
@auth()
    @include('layouts.page_templates.auth')
@endauth

@guest
    @include('layouts.page_templates.guest')
@endguest

<!--   Core JS Files   -->
<script src="{{ asset('paper') }}/js/core/jquery.min.js"></script>
<script src="{{ asset('paper') }}/js/core/popper.min.js"></script>
<script src="{{ asset('paper') }}/js/core/bootstrap.min.js"></script>
<script src="{{ asset('paper') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!--  Google Maps Plugin    -->
{{--    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>--}}
<!-- Chart JS -->
<script src="{{ asset('paper') }}/js/plugins/chartjs.min.js"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('paper') }}/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('paper') }}/js/paper-dashboard.min.js?v=2.0.0" type="text/javascript"></script>
<script src="{{ asset('paper') }}/vendors/DataTables/datatables.min.js" type="text/javascript"></script>
<script src="{{ asset('paper') }}/vendors/momentjs/moment.min.js" type="text/javascript"></script>
<script src="{{asset('paper')}}/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{{asset('paper')}}/vendors/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('paper') }}/demo/demo.js"></script>
<!-- Sharrre libray -->

@stack('scripts')

</body>

</html>
