<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Masuk</title>
        <link rel="icon" href="{{ asset('images/Logo.jpg') }}" type="image/jpeg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --font-display: 'Instrument Serif', Georgia, serif;
                --font-body: 'DM Sans', system-ui, sans-serif;
                --ink: #141413;
                --ink-muted: #575653;
                --ink-faint: #797874;
                --surface: #ffffff;
                --surface-alt: #fbfaf7;
                --border: #e6e3db;
                --accent: #1e4fd9;
                --radius-card: 16px;
                --radius-pill: 999px;
            }
            body {
                font-family: var(--font-body);
                background-color: var(--surface-alt);
                color: var(--ink);
                -webkit-font-smoothing: antialiased;
            }
            .auth-card {
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: var(--radius-card);
                box-shadow: 0 4px 24px rgba(20, 20, 19, 0.04);
            }
            .auth-brand-title {
                font-family: var(--font-display);
                font-size: 28px;
                font-weight: 400;
                color: var(--ink);
                letter-spacing: -0.015em;
            }
        </style>
    </head>
    <body class="antialiased selection:bg-blue-600 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-12">
            <div class="mb-6 text-center">
                <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3 group text-decoration-none">
                    <img src="{{ asset('images/Logo.jpg') }}" alt="{{ config('app.name') }}" class="w-14 h-14 rounded-full object-cover border-2 border-stone-200 transition-transform duration-200 group-hover:scale-105">
                    <div>
                        <span class="auth-brand-title block">{{ config('app.name') }}</span>
                        <span class="text-xs uppercase tracking-widest font-semibold text-blue-600">Admin Portal</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md auth-card p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
