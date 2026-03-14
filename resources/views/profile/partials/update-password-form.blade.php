<section class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
    <div class="card-body p-4">
        <header class="mb-4">
            <h5 class="fw-bold text-primary">
                <i class="fas fa-key me-2"></i>{{ __('Update Password') }}
            </h5>
            <p class="text-muted small">
                {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
            </p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="needs-validation">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label small fw-bold text-secondary">{{ __('Current Password') }}</label>
                <div class="input-group has-validation">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                    <input id="update_password_current_password" name="current_password" type="password" 
                        class="form-control border-start-0 @error('current_password', 'updatePassword') is-invalid @enderror" 
                        autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label small fw-bold text-secondary">{{ __('New Password') }}</label>
                <div class="input-group has-validation">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-unlock-alt"></i></span>
                    <input id="update_password_password" name="password" type="password" 
                        class="form-control border-start-0 @error('password', 'updatePassword') is-invalid @enderror" 
                        autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="update_password_password_confirmation" class="form-label small fw-bold text-secondary">{{ __('Confirm Password') }}</label>
                <div class="input-group has-validation">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-check-double"></i></span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                        class="form-control border-start-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                        autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    <i class="fas fa-save me-2"></i>{{ __('Update Password') }}
                </button>

                @if (session('status') === 'password-updated')
                    <span 
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-success small fw-bold"
                    >
                        <i class="fas fa-check-circle me-1"></i> {{ __('Password Updated.') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
</section>