<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>FrikiLand</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-header />

    <div class="content-home">
        <div class="text-home social-web">
            <a href="{{ route('social-web') }}">
                <h2>Social web</h2>
                <p>
                    Únete para comentar, compartir posts, debatir, chatear y seguir a otros geeks.
                    Una comunidad donde cada tema encuentra su fandom.
                </p>
            </a>

        </div>

        <div class="text-home shop">
            <a href="#">
                <h2>Shop</h2>
                <p>Explora, elige y compra con confianza. Aquí, cada compra conecta con lo que te gusta y con tu fandom.
                </p>
            </a>
        </div>
    </div>

    <x-footer />
</body>

</html>
