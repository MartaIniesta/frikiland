<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>FrikiLand</title>

    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">

    {{-- HEADER --}}
    <x-header />

    {{-- CONTENIDO CENTRAL (LOGIN / REGISTER) --}}
    <main class="flex-1 auth-page login">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <x-footer />

    @fluxScripts
</body>

</html>
