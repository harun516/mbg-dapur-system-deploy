@extends('layouts.auth')

@section('title', 'Login / Register')

@section('content')
<div id="container" class="container">
    <!-- FORM SECTION -->
    <div class="row">
        <!-- SIGN UP (REGISTER) -->
        <div class="col align-items-center flex-col sign-up">
            <div class="form-wrapper align-items-center">
                <div class="form sign-up">
                    <h4 class="text-center mb-4 fw-bold text-primary">Daftar Akun Baru</h4>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pilihan Role (tetap persis seperti yang kamu punya) -->
                        <div class="input-group">
                            <i class="fas fa-user-shield"></i>
                            <select name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="gudang" {{ old('role') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                                <option value="dapur" {{ old('role') == 'dapur' ? 'selected' : '' }}>Dapur</option>
                                <option value="kurir" {{ old('role') == 'kurir' ? 'selected' : '' }}>Kurir</option>
                            </select>
                            @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password" required>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                        </div>

                        <button type="submit">
                            Daftar Akun
                        </button>
                    </form>

                    <p>
                        <span>Sudah punya akun?</span>
                        <b onclick="toggle()" class="pointer">Login disini</b>
                    </p>
                </div>
            </div>
        </div>

        <!-- SIGN IN (LOGIN) -->
        <div class="col align-items-center flex-col sign-in">
            <div class="form-wrapper align-items-center">
                <div class="form sign-in">
                    <h4 class="text-center mb-4 fw-bold text-primary">Masuk ke Sistem</h4>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password" required>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="remember">Ingat saya</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small text-primary" href="{{ route('password.request') }}">Lupa password?</a>
                            @endif
                        </div>

                        <button type="submit">
                            Masuk
                        </button>
                    </form>

                    <p>
                        <span>Belum punya akun?</span>
                        <b onclick="toggle()" class="pointer">Daftar disini</b>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT SECTION (teks sambutan) -->
    <div class="row content-row">
        <!-- SIGN IN CONTENT -->
        <div class="col align-items-center flex-col">
            <div class="text sign-in">
                <h2>Selamat Datang Kembali</h2>
                <p>Masuk untuk mengelola sistem gudang dan produksi.</p>
            </div>
        </div>

        <!-- SIGN UP CONTENT -->
        <div class="col align-items-center flex-col">
            <div class="text sign-up">
                <h2>Bergabung Bersama Kami</h2>
                <p>Buat akun baru dan mulai kelola operasional gudang & dapur.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let container = document.getElementById('container');

    function toggle() {
        container.classList.toggle('sign-in');
        container.classList.toggle('sign-up');
    }

    // Otomatis mulai dari sign-in
    setTimeout(() => {
        container.classList.add('sign-in');
    }, 200);
</script>
@endpush
@endsection