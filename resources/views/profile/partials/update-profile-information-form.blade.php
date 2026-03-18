<section class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-4">
        <header class="mb-4">
            <h5 class="fw-bold text-primary">
                <i class="fas fa-user-circle me-2"></i>{{ __('Profile Information') }}
            </h5>
            <p class="text-muted small">
                {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="needs-validation">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label small fw-bold text-secondary">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name', $user->name) }}" 
                    required autofocus autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="form-label small fw-bold text-secondary">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', $user->email) }}" 
                    required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 p-2 bg-light rounded border">
                        <p class="text-sm mb-0 text-dark small">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 mb-0 fw-medium small text-success">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    <i class="fas fa-save me-2"></i>{{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <span 
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-success small fw-bold"
                    >
                        <i class="fas fa-check-circle me-1"></i> {{ __('Saved.') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
</section>