<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="initial-scale=1, width=device-width">
        <title>{{ config('app.name') }} | @yield('title', 'Unknown page!')</title>
        <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    </head>

    <body>
        @yield('content')
    </body>
</html>