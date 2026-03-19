<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Gudang Dapur') }} - @yield('title')</title>

    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Auth Custom CSS via Vite -->
    @vite(['resources/css/auth.css'])

    <!-- Custom CSS Auth (kita pertahankan + tambah override untuk Wagon) -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #333;
        }

        /* Override agar container Wagon bisa full-screen */
        #container {
            position: relative;
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .auth-card {
            display: none; /* kita sembunyikan card lama kalau pakai Wagon */
        }

        /* Pastikan auth-body bisa menampung konten Wagon */
        .auth-body {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* Style tambahan untuk tombol & link agar match tema biru */
        .form button {
            background: #3b82f6 !important;
        }

        .form button:hover {
            background: #1e40af !important;
        }

        .pointer {
            color: #3b82f6 !important;
        }

        .pointer:hover {
            color: #1e40af !important;
        }

        @media (max-width: 576px) {
            .auth-body { padding: 0 !important; }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Kita tetap pakai auth-body supaya alert & error tetap muncul -->
    <div class="auth-body">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Ada kesalahan:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
