<section>
    <header>
        <h2 class="card-title">Perbarui Kata Sandi</h2>
        <p class="card-desc">
            Pastikan akun Anda menggunakan kata sandi yang panjang dan acak demi keamanan optimal.
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="field-group">
        @csrf
        @method('PUT')

        {{-- Current Password --}}
        <div class="form-field">
            <label for="update_password_current_password">Kata Sandi Saat Ini</label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="form-input"
                autocomplete="current-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        {{-- New Password --}}
        <div class="form-field">
            <label for="update_password_password">Kata Sandi Baru</label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="form-input"
                autocomplete="new-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        {{-- Confirm Password --}}
        <div class="form-field">
            <label for="update_password_password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-input"
                autocomplete="new-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        {{-- Action --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-submit">
                Simpan Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="status-msg"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    Kata sandi berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
