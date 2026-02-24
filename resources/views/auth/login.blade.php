@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="contoh@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-link small" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted small">Belum punya akun? 
            <a href="{{ route('register') }}" class="text-link fw-bold">Daftar sekarang</a>
        </p>
    </div>
@endsection