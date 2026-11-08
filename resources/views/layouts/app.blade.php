<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Laravel') }} — Admin</title>
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
        --ink-secondary: #3b3a37;
        --ink-muted: #575653;
        --ink-faint: #797874;
        --surface: #ffffff;
        --surface-alt: #fbfaf7;
        --surface-hover: #f3f1ec;
        --border: #e6e3db;
        --border-subtle: #eeece6;
        --accent: #1e4fd9;
        --accent-hover: #153fb8;
        --accent-light: #eef3ff;
        --radius-card: 16px;
        --radius-pill: 999px;
        --transition: 200ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    html, body { height: 100%; }

    body {
        font-family: var(--font-body);
        color: var(--ink);
        background: var(--surface-alt);
        -webkit-font-smoothing: antialiased;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* ── TOPBAR ── */
    .topbar {
        height: 62px;
        background: rgba(251, 250, 247, 0.88);
        backdrop-filter: saturate(180%) blur(16px);
        -webkit-backdrop-filter: saturate(180%) blur(16px);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        transition: box-shadow var(--transition), background var(--transition);
    }

    .topbar.scrolled {
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 4px 20px rgba(20, 20, 19, 0.05);
    }

    .topbar-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    /* Brand */
    .topbar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .brand-logo-img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid var(--border);
        transition: transform var(--transition);
    }

    .topbar-brand:hover .brand-logo-img {
        transform: rotate(-6deg) scale(1.05);
    }

    .brand-name {
        font-family: var(--font-display);
        font-size: 20px;
        font-weight: 400;
        color: var(--ink);
        letter-spacing: -0.015em;
        line-height: 1;
        display: inline-flex;
        align-items: center;
    }

    .brand-badge-admin {
        font-family: var(--font-body);
        font-size: 10.5px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--accent);
        background: var(--accent-light);
        padding: 2px 7px;
        border-radius: var(--radius-pill);
        margin-left: 8px;
        line-height: 1.2;
    }

    /* Nav links */
    .topbar-nav {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 14px;
        border-radius: var(--radius-pill);
        font-size: 13.5px;
        font-weight: 500;
        color: var(--ink-muted);
        text-decoration: none;
        border: 1px solid transparent;
        transition: all var(--transition);
    }

    .nav-link svg { width: 14px; height: 14px; flex-shrink: 0; }

    .nav-link:hover {
        background: var(--surface-hover);
        color: var(--ink);
        border-color: var(--border);
    }

    .nav-link.active {
        background: var(--surface);
        color: var(--ink);
        border-color: var(--border);
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    /* Right actions */
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* ── USER DROPDOWN ── */
    .user-dropdown { position: relative; }

    .user-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px 4px 5px;
        border: 1px solid var(--border);
        border-radius: var(--radius-pill);
        background: var(--surface);
        cursor: pointer;
        font-family: var(--font-body);
        transition: all var(--transition);
    }

    .user-pill:hover { background: var(--surface-hover); border-color: #d4d0c5; }
    .user-pill:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

    .user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--ink);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.02em;
        flex-shrink: 0;
    }

    .user-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--ink);
        white-space: nowrap;
    }

    .chevron {
        width: 13px;
        height: 13px;
        color: var(--ink-faint);
        transition: transform var(--transition);
        flex-shrink: 0;
    }

    .user-pill[aria-expanded="true"] .chevron { transform: rotate(180deg); }

    /* Dropdown panel */
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 220px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 12px 36px rgba(20, 20, 19, 0.08), 0 2px 6px rgba(20, 20, 19, 0.04);
        overflow: hidden;
        opacity: 0;
        transform: translateY(-6px) scale(0.97);
        transform-origin: top right;
        pointer-events: none;
        transition: opacity 180ms ease, transform 180ms ease;
        z-index: 200;
    }

    .dropdown-menu.open {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .dropdown-header { padding: 14px 16px 10px; background: var(--surface-alt); border-bottom: 1px solid var(--border-subtle); }
    .dropdown-name { font-size: 13.5px; font-weight: 600; color: var(--ink); }
    .dropdown-role { font-size: 11.5px; color: var(--ink-muted); margin-top: 2px; }
    .dropdown-divider { height: 1px; background: var(--border-subtle); }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        font-size: 13.5px;
        font-weight: 400;
        color: var(--ink-muted);
        text-decoration: none;
        background: transparent;
        border: none;
        cursor: pointer;
        font-family: var(--font-body);
        transition: background var(--transition), color var(--transition);
        text-align: left;
    }

    .dropdown-item svg { width: 15px; height: 15px; flex-shrink: 0; opacity: .8; }
    .dropdown-item:hover { background: var(--surface-hover); color: var(--ink); }
    .dropdown-item-active { color: var(--accent) !important; background: var(--accent-light); font-weight: 500; }
    .dropdown-item-active svg { opacity: 1; }
    .dropdown-item-danger { color: #dc2626; }
    .dropdown-item-danger:hover { background: #fff5f5; color: #dc2626; }

    /* ── MAIN CONTENT ── */
    main { flex: 1; }

    /* ── MOBILE ── */
    @media (max-width: 600px) {
        .nav-link span { display: none; }
        .nav-link { padding: 0 10px; }
        .user-name { display: none; }
        .topbar-inner { padding: 0 16px; }
    }
</style>

    @stack('styles')
</head>
<body>

{{-- ── TOPBAR ── --}}
<header class="topbar" id="topbar">
    <div class="topbar-inner">

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="topbar-brand" aria-label="{{ config('app.name') }} Admin">
            <img src="{{ asset('images/Logo.jpg') }}" alt="{{ config('app.name') }} Logo" class="brand-logo-img">
            <span class="brand-name">
                {{ config('app.name') }}
                <span class="brand-badge-admin">Admin</span>
            </span>
        </a>

        {{-- Nav --}}
        <nav class="topbar-nav">
            <a href="{{ route('admin.articles.index') }}"
               class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span>Kelola Artikel</span>
            </a>
            <a href="{{ route('home') }}" class="nav-link" target="_blank" rel="noopener noreferrer">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                <span>Lihat Blog</span>
            </a>
        </nav>

        {{-- Right --}}
        <div class="topbar-right">
            @auth
            @php
                $initials = collect(explode(' ', Auth::user()->name))
                    ->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
            @endphp

            {{-- Dropdown trigger --}}
            <div class="user-dropdown" id="userDropdown">
                <button class="user-pill" id="userPillBtn" aria-expanded="false">
                    <div class="user-avatar">{{ $initials }}</div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <svg class="chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown menu --}}
                <div class="dropdown-menu" id="dropdownMenu">
                    <div class="dropdown-header">
                        <p class="dropdown-name">{{ Auth::user()->name }}</p>
                        <p class="dropdown-role">Administrator</p>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item {{ request()->routeIs('profile.*') ? 'dropdown-item-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        Edit Profil
                    </a>
                    <a href="{{ route('home') }}" class="dropdown-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                        </svg>
                        Lihat Blog
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-item-danger">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </div>
</header>

{{-- ── PAGE CONTENT ── --}}
<main>
    @yield('content')
</main>

<script>
    // Topbar shadow on scroll
    const topbar = document.getElementById('topbar');
    window.addEventListener('scroll', () => {
        topbar.classList.toggle('scrolled', window.scrollY > 8);
    }, { passive: true });

    // Dropdown toggle
    const pillBtn      = document.getElementById('userPillBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    pillBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dropdownMenu.classList.toggle('open');
        pillBtn.setAttribute('aria-expanded', isOpen);
    });

    document.addEventListener('click', () => {
        dropdownMenu?.classList.remove('open');
        pillBtn?.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            dropdownMenu?.classList.remove('open');
            pillBtn?.setAttribute('aria-expanded', 'false');
        }
    });
</script>

@stack('scripts')
</body>
</html>