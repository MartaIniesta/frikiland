<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>FrikiLand</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen flex flex-col">
    <main class="flex-1">
        {{ $slot }}
    </main>

    <div class="mensajes">
        <div class="circulo-mansajes">

            @auth
                @if (auth()->user()->hasRole('admin'))
                    @if (request()->is('manage'))
                        <a href="{{ route('home') }}" aria-label="Ir al inicio">
                            <i class="bx bxs-home"></i>
                        </a>
                    @else
                        <a href="{{ route('manage') }}" aria-label="Ir al panel admin">
                            <i class="bx bx-cog"></i>
                        </a>
                    @endif
                @else
                    @if (request()->routeIs('chat.*'))
                        <a href="{{ route('social-web.for-you') }}" aria-label="Ir al inicio">
                            <i class="bx bxs-home"></i>
                        </a>
                    @else
                        <a href="{{ route('chat.index') }}" aria-label="Ir a chats">
                            <i class="bx bx-chat"></i>
                        </a>
                    @endif
                @endif
            @endauth
        </div>
    </div>

    <x-footer />
    @livewireScripts
</body>

</html>
