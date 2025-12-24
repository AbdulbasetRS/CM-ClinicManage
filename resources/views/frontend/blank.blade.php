<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('general/images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('general/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('general/fontawesome/fontawesome-free-6.4.2-web/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('general/libraries/bootstrap/bootstrap-v5.3.8/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('general/libraries/sweetAlert2/v11.17.2/sweetalert2.min.css') }}">
    
    <!-- Site Stylesheet -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-gray-100 font-default">
    <div class="container">
        <h1>Welcome to the Laravel Auth Blade Starter</h1>
    </div>

    <!-- Site Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('general/libraries/bootstrap/bootstrap-v5.3.8/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('general/libraries/jquery/jquery-3.7.1/minified.jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('general/libraries/chart.js/chart-v4.5.0.js') }}"></script>
    <script src="{{ asset('general/libraries/sweetAlert2/v11.17.2/sweetalert2.min.js') }}"></script>
</body>

</html>