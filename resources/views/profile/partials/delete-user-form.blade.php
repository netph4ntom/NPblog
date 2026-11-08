<section>
    <header>
        <h2 class="card-title text-red-600">Hapus Akun</h2>
        <p class="card-desc">
            Setelah akun Anda dihapus, seluruh data dan artikel terkait akan dihapus secara permanen. Pastikan Anda telah mencadangkan data yang diperlukan.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn-danger"
    >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Hapus Akun Ini
    </button>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >
        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="card-title text-stone-900 mb-2">
                Konfirmasi Penghapusan Akun
            </h2>

            <p class="text-sm text-stone-600 mb-6 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan secara permanen.
            </p>

            <div class="mb-6">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-700 mb-1.5">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Masukkan kata sandi akun Anda"
                    class="form-input"
                    required
                >
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="h-10 px-5 rounded-full border border-stone-300 bg-white text-stone-700 text-sm font-medium hover:bg-stone-50 transition cursor-pointer"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn-danger"
                >
                    Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
