<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<title>@yield('title') | {{ config('app.name') }}</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}"/>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">