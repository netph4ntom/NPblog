<section>
    <header>
        <h2 class="card-title">Informasi Profil</h2>
        <p class="card-desc">
            Perbarui informasi nama akun dan alamat surel administrator Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="field-group">
        @csrf
        @method('PATCH')

        {{-- Nama --}}
        <div class="form-field">
            <label for="name">Nama Lengkap</label>
            <input
                id="name"
                name="name"
                type="text"
                class="form-input"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div class="form-field">
            <label for="email">Alamat Surel (Email)</label>
            <input
                id="email"
                name="email"
                type="email"
                class="form-input"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-amber-700">
                    <p>Alamat surel Anda belum diverifikasi.</p>
                    <button form="send-verification" class="underline text-xs text-stone-600 hover:text-stone-900">
                        Klik di sini untuk mengirim ulang surel verifikasi.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-green-600">
                            Tautan verifikasi baru telah dikirim ke alamat surel Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-submit">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
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
                    Perubahan berhasil disimpan.
                </p>
            @endif
        </div>
    </form>
</section>
