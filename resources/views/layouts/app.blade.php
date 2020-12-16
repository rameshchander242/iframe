<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        .bg-light-2 {
            background: #dddddd;
        }
        .login-wrap {
            max-width: 540px;
            padding-top: 8vh;
            margin: 0 auto;
        }
        
    </style>
</head>
<body class="bg-light-2">
    <div id="app">
        <main class="pt-5">
            @yield('content')
        </main>
    </div>
</body>
</html>
