<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | @yield('title', 'SiData Surakarta - Data Terbuka Kota Surakarta')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/SidataLogoIcon.png') }}">

</head>
<body class="antialiased">
    {{ $slot }}
</body>
</html>
