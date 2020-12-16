<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Computer Repair Doctor</title>
    <!-- Scripts -->
    <script src="{{ asset('front/js/app.js') }}"></script>
    
    <script src="https://kit.fontawesome.com/02c593d7c1.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('front/css/app.css') }}" rel="stylesheet">
    @stack('custom_styles')
</head>
<body>
    <div id="app-iframe">
        @yield('content')
    </div>

    @stack('custom_scripts')
</body>
</html>
