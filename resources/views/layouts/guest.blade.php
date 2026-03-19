<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap 5.3.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmAFTimeaM5tghvzOMGPRzv+QQnnSmkJ" crossorigin="anonymous">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            body {
                background-color: #f3f4f6;
                font-family: 'figtree', sans-serif;
            }
            .min-vh-100 {
                min-height: 100vh;
            }
            .guest-container {
                max-width: 448px;
            }
        </style>
    </head>
    <body>
        <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light">
            <div class="mb-4">
                <a href="/">
                    <x-application-logo style="width: 80px; height: 80px; color: #9ca3af;" />
                </a>
            </div>

            <div class="guest-container w-100 mx-auto mt-4 p-4 bg-white shadow-sm rounded">
                {{ $slot }}
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NSTL6OiOXP7DXKL1Wy3NGM/0vP2" crossorigin="anonymous"></script>
    </body>
</html>
