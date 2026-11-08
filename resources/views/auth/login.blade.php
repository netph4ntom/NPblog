<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-stone-900 tracking-tight">Masuk ke Akun</h2>
        <p class="text-sm text-stone-500 mt-1">Gunakan akun admin untuk mengelola artikel.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username -->
        <div>
            <label for="name" class="block text-sm font-medium text-stone-800 mb-1.5">Username</label>
            <input
                id="name"
                class="w-full h-11 px-3.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-900 text-sm focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/10 transition"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="Username admin"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-stone-800 mb-1.5">Kata Sandi</label>
            <input
                id="password"
                class="w-full h-11 px-3.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-900 text-sm focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600/10 transition"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-stone-300 text-blue-600 focus:ring-blue-500 cursor-pointer" name="remember">
                <span class="ms-2 text-xs font-medium text-stone-600 select-none">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full h-11 rounded-full bg-stone-900 hover:bg-blue-600 active:scale-[0.98] text-white text-sm font-medium transition duration-150 flex items-center justify-center gap-2 cursor-pointer">
                Masuk
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        <div class="pt-2 text-center">
            <a class="text-xs font-medium text-stone-500 hover:text-stone-900 transition-colors inline-flex items-center gap-1.5" href="{{ route('home') }}">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda Blog
            </a>
        </div>
    </form>
</x-guest-layout>
