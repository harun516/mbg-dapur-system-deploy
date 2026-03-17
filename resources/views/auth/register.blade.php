@extends('layouts.auth')

@section('title', 'Registrasi Akun')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Contoh : Yayang Setia Budi">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="example@gmail.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tambahan Pemilihan Role -->
        <!-- <div class="mb-4">
            <label for="role" class="form-label">Role</label>
            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="gudang" {{ old('role') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                <option value="dapur" {{ old('role') == 'dapur' ? 'selected' : '' }}>Dapur</option>
                <option value="kurir" {{ old('role') == 'kurir' ? 'selected' : '' }}>Kurir</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> -->

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
            <i class="fas fa-user-plus me-2"></i> Daftar Akun Baru
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted small">Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-link fw-bold">Login disini</a>
        </p>
    </div>
@endsection