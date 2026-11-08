<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') — {{ config('app.name') }} @else {{ config('app.name') }} — Berbagi Tulisan &amp; Ide @endif</title>
    <meta name="description" content="@yield('meta_description', 'SiBlog - Platform berbagi tulisan, gagasan, dan cerita inspiratif.')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'SiBlog - Platform berbagi tulisan, gagasan, dan cerita inspiratif.')">
    <meta property="og:image" content="@yield('og_image', asset('images/Logo.jpg'))">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'SiBlog - Platform berbagi tulisan, gagasan, dan cerita inspiratif.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/Logo.jpg'))">

    <link rel="icon" href="{{ asset('images/Logo.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
    @import url('https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --font-display: 'Instrument Serif', Georgia, serif;
        --font-body: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        --ink: #141413;
        --ink-secondary: #2f2e2b;
        --ink-muted: #575653;
        --ink-faint: #797874;
        --surface: #ffffff;
        --surface-alt: #faf9f6;
        --surface-hover: #f3f1ec;
        --border: #e6e4dc;
        --border-subtle: #f0eee7;
        --accent: #1e4fd9;
        --accent-hover: #153fb5;
        --accent-light: #eef3ff;
        --radius-card: 16px;
        --radius-pill: 999px;
        --transition: 200ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    html {
        scroll-behavior: smooth;
        text-rendering: optimizeLegibility;
    }

    body {
        font-family: var(--font-body);
        color: var(--ink);
        background: var(--surface);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    main { flex: 1; }

    /* ── ACCESSIBILITY & FOCUS ── */
    :focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }

    /* ── NAVBAR ── */
    .nav {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(14px) saturate(180%);
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        border-bottom: 1px solid var(--border);
        transition: box-shadow var(--transition), border-color var(--transition);
    }

    .nav.scrolled {
        box-shadow: 0 4px 24px rgba(20, 20, 19, 0.04);
        border-bottom-color: #dedbd2;
    }

    .nav-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    /* Brand */
    .nav-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        flex-shrink: 0;
        transition: opacity var(--transition);
    }

    .nav-brand:hover { opacity: 0.85; }

    .nav-brand-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid var(--border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .nav-brand-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .nav-brand-name {
        font-family: var(--font-display);
        font-size: 22px;
        font-weight: 400;
        letter-spacing: -0.02em;
        color: var(--ink);
        line-height: 1;
    }

    /* Nav right side */
    .nav-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-link {
        height: 38px;
        padding: 0 16px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--ink-secondary);
        text-decoration: none;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border);
        background: var(--surface);
        transition: all var(--transition);
    }

    .nav-link:hover {
        background: var(--surface-alt);
        color: var(--ink);
        border-color: #d8d5cb;
        transform: translateY(-1px);
    }

    .nav-link:active {
        transform: scale(0.97);
    }

    .nav-link svg { width: 14px; height: 14px; flex-shrink: 0; }

    .nav-link-primary {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
        font-weight: 500;
        box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    }

    .nav-link-primary:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
        box-shadow: 0 4px 14px rgba(30, 79, 217, 0.25);
    }

    /* ── FOOTER ── */
    footer {
        border-top: 1px solid var(--border);
        background: var(--surface-alt);
        padding: 44px 24px 36px;
        margin-top: auto;
    }

    .footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .footer-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .footer-brand-wrap {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-width: 360px;
    }

    .footer-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .footer-brand span {
        font-family: var(--font-display);
        font-size: 20px;
        color: var(--ink);
    }

    .footer-tagline {
        font-size: 13px;
        line-height: 1.55;
        color: var(--ink-muted);
    }

    .footer-action-top {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        color: var(--ink-muted);
        text-decoration: none;
        padding: 6px 12px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border);
        background: var(--surface);
        transition: all var(--transition);
        cursor: pointer;
    }

    .footer-action-top:hover {
        background: var(--surface-hover);
        color: var(--ink);
        border-color: #d8d5cb;
    }

    .footer-action-top svg { width: 13px; height: 13px; }

    .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        font-size: 12.5px;
        color: var(--ink-faint);
        flex-wrap: wrap;
    }

    /* ── SCROLL PROGRESS BAR ── */
    #progress-bar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: var(--accent);
        width: 0%;
        z-index: 200;
        transition: width 90ms ease-out;
        box-shadow: 0 0 8px rgba(30, 79, 217, 0.4);
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

    @stack('styles')
</head>
<body>

{{-- Reading progress bar --}}
<div id="progress-bar" aria-hidden="true"></div>

{{-- ── NAVBAR ── --}}
<nav class="nav" id="navbar">
    <div class="nav-inner">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="nav-brand">
            <div class="nav-brand-icon">
                <img src="{{ asset('images/Logo.jpg') }}" alt="Logo">
            </div>
            <span class="nav-brand-name">{{ config('app.name') }}</span>
        </a>

        {{-- Right side --}}
        <div class="nav-right">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="nav-link nav-link-primary">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-link nav-link-primary">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                    Upload
                </a>
            @endauth
        </div>

    </div>
</nav>

{{-- ── MAIN CONTENT ── --}}
<main>
    @yield('content')
</main>

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand-wrap">
                <a href="{{ route('home') }}" class="footer-brand">
                    <div class="nav-brand-icon" style="width:28px; height:28px; border-radius:7px;">
                        <img src="{{ asset('images/Logo.jpg') }}" alt="Logo">
                    </div>
                    <span>{{ config('app.name') }}</span>
                </a>
                <p class="footer-tagline">Ruang berbagi ide, cerita, dan wawasan pemikiran independen seputar teknologi dan kehidupan.</p>
            </div>

            <button type="button" class="footer-action-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke atas">
                <span>Kembali ke Atas</span>
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 15l7-7 7 7"/>
                </svg>
            </button>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Didesain dengan perhatian mendalam pada tipografi &amp; keterbacaan.</p>
        </div>
    </div>
</footer>

<script>
    // Sticky nav shadow on scroll
    const navbar = document.getElementById('navbar');
    const progressBar = document.getElementById('progress-bar');

    window.addEventListener('scroll', () => {
        // Nav shadow
        navbar.classList.toggle('scrolled', window.scrollY > 10);

        // Reading progress
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const pct = docHeight > 0 ? (window.scrollY / docHeight) * 100 : 0;
        progressBar.style.width = pct + '%';
    }, { passive: true });
</script>

@stack('scripts')
</body>
</html>