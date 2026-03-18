<section class="card border-0 shadow-sm mt-4 mb-5" style="border-radius: 15px;">
    <div class="card-body p-4">
        <header class="mb-4">
            <h5 class="fw-bold text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Delete Account') }}
            </h5>
            <p class="text-muted small">
                {{ __('Setelah akun Anda dihapus, semua sumber daya dan data di dalamnya akan dihapus secara permanen. Silakan sertakan kata sandi Anda untuk konfirmasi.') }}
            </p>
        </header>

        <button type="button" class="btn btn-danger px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
            <i class="fas fa-trash-alt me-2"></i>{{ __('Delete Account') }}
        </button>

        <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                    <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
                        @csrf
                        @method('delete')

                        <h5 class="fw-bold text-dark mb-3" id="deleteAccountModalLabel">
                            {{ __('Konfirmasi Penghapusan Akun') }}
                        </h5>

                        <p class="text-muted small mb-4">
                            {{ __('Demi keamanan, silakan masukkan password akun Anda untuk melanjutkan penghapusan permanen.') }}
                        </p>

                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold text-secondary">{{ __('Password Anda') }}</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" name="password" type="password" 
                                    class="form-control border-start-0 @error('password', 'userDeletion') is-invalid @enderror" 
                                    placeholder="Masukkan password konfirmasi..." required>
                                
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">
                                {{ __('Batal') }}
                            </button>

                            <button type="submit" class="btn btn-danger px-4 shadow-sm">
                                <i class="fas fa-user-times me-2"></i>{{ __('Hapus Sekarang') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        myModal.show();
    });
</script>
@endif