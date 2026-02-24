<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Gudang Dapur') }} - @yield('title')</title>

    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS untuk Auth -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f0ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .auth-card {
            max-width: 420px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            border: none;
        }
        .auth-header {
            background: #007bff;
            color: white;
            padding: 32px 24px;
            text-align: center;
        }
        .auth-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
        }
        .auth-body {
            padding: 32px 28px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.3);
        }
        .text-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .text-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .alert {
            border-radius: 8px;
            font-size: 0.95rem;
        }
        @media (max-width: 576px) {
            .auth-body { padding: 24px 20px; }
            .auth-header { padding: 28px 20px; }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <h3>@yield('title')</h3>
        </div>

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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>