<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DAPURKU') }}</title>

    <!-- Bootstrap 5.3 (latest stable) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    <script>
        window.successMessage = "{{ session('success') }}";
        window.errorMessage = "{{ session('error') }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @vite([
        'resources/css/app.css',
        'resources/css/sidebar.css',
        'resources/css/gudang/penerimaan-barang.css',
        'resources/css/dapur/index-stok-dapur.css',
        'resources/css/dapur/menu/menu.css',
        'resources/css/gudang/item/item.css',
        'resources/css/gudang/request/request.css',
        'resources/js/app.js',
        'resources/js/sidebar-toggle.js',
        'resources/js/dapur/menu/notif-menu.js',
    ])
</head>
<body class="font-sans antialiased" style="font-family: 'Inter', sans-serif;">

    <div class="app-wrapper">
        <x-sidebar />

        <div class="main-content">
            <x-topbar />

            <main class="py-4 px-3 px-md-5">
                <div class="container-fluid">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>

            <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
                © {{ date('Y') }} MBG-Dapur01 By Yayang Setia Budi
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
