{{--
    views/home.blade.php
    Halaman publik utama: menampilkan grid artikel + form pencarian.
    Bisa diakses siapa saja tanpa login.
--}}
@extends('layouts.public')

@push('styles')
<style>
    /* ── PAGE WRAPPER ── */
    .blog-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 52px 24px 88px;
    }

    /* ── MASTHEAD ── */
    .masthead {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 32px;
        margin-bottom: 48px;
        padding-bottom: 32px;
        border-bottom: 1px solid var(--border);
    }

    .masthead-left { flex: 1; }

    .masthead-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 10px;
    }

    .masthead-eyebrow::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent);
    }

    .masthead-title {
        font-family: var(--font-display);
        font-size: clamp(38px, 5.5vw, 64px);
        font-weight: 400;
        line-height: 1.05;
        letter-spacing: -0.025em;
        color: var(--ink);
    }

    .masthead-title em {
        font-style: italic;
        color: var(--ink-muted);
        font-weight: 400;
    }

    /* ── SEARCH FORM ── */
    .search-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .search-field-wrap {
        position: relative;
        display: flex;
        align-items: center;
        min-width: 260px;
    }

    .search-icon {
        position: absolute;
        left: 14px;
        color: var(--ink-faint);
        pointer-events: none;
        width: 15px;
        height: 15px;
    }

    .search-input {
        height: 42px;
        width: 100%;
        border: 1px solid var(--border);
        border-radius: var(--radius-pill);
        background: var(--surface-alt);
        padding: 0 16px 0 38px;
        font-family: var(--font-body);
        font-size: 13.5px;
        color: var(--ink);
        outline: none;
        transition: border-color var(--transition), background var(--transition), box-shadow var(--transition);
    }

    .search-input::placeholder { color: var(--ink-faint); }

    .search-input:focus {
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(30, 79, 217, 0.12);
    }

    .btn-search {
        height: 42px;
        padding: 0 20px;
        background: var(--ink);
        color: #fff;
        border: 1px solid var(--ink);
        border-radius: var(--radius-pill);
        font-family: var(--font-body);
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition);
        white-space: nowrap;
    }

    .btn-search:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-search:active { transform: scale(0.97); }

    .btn-reset {
        height: 42px;
        padding: 0 16px;
        background: transparent;
        color: var(--ink-muted);
        border: 1px solid var(--border);
        border-radius: var(--radius-pill);
        font-family: var(--font-body);
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-reset:hover {
        background: var(--surface-hover);
        color: var(--ink);
        border-color: #d8d5cb;
    }

    /* ── SEARCH RESULT INFO ── */
    .search-info {
        font-size: 13.5px;
        color: var(--ink-muted);
        margin-bottom: 32px;
        padding: 12px 18px;
        background: var(--accent-light);
        border-radius: 10px;
        border-left: 3px solid var(--accent);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .search-info strong { color: var(--ink); font-weight: 600; }

    /* ── ARTICLE GRID ── */
    .article-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px 28px;
    }

    /* Featured Lead Card (First card on page 1 when not searching) */
    .article-card-featured {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
        margin-bottom: 8px;
    }

    .article-card-featured .card-img-wrap {
        aspect-ratio: 16/10;
        height: 100%;
        min-height: 280px;
    }

    .article-card-featured .card-body {
        padding: 32px 36px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .article-card-featured .card-title {
        font-family: var(--font-display);
        font-size: clamp(26px, 3vw, 34px);
        line-height: 1.2;
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .article-card-featured .card-excerpt {
        font-size: 15px;
        line-height: 1.7;
        -webkit-line-clamp: 4;
        margin-bottom: 8px;
    }

    /* ── STANDARD ARTICLE CARD ── */
    .article-card {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        overflow: hidden;
        transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
        position: relative;
    }

    .article-card:hover, .article-card-featured:hover {
        border-color: #d4d0c5;
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(20, 20, 19, 0.06);
    }

    /* Image zone */
    .card-img-wrap {
        position: relative;
        overflow: hidden;
        background: var(--surface-alt);
        aspect-ratio: 16/10;
        border-bottom: 1px solid var(--border-subtle);
    }

    .card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 450ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    .article-card:hover .card-img, .article-card-featured:hover .card-img {
        transform: scale(1.025);
    }

    /* Elegant Editorial Placeholder */
    .card-no-img {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, #fbfaf7 0%, #f0eee8 100%);
        position: relative;
        overflow: hidden;
    }

    .card-no-img::before {
        content: '';
        position: absolute;
        inset: 0;
        background-radial-gradient: circle at 50% 50%, rgba(255,255,255,0.6) 0%, transparent 60%;
        pointer-events: none;
    }

    .card-no-img-badge {
        font-family: var(--font-display);
        font-size: 26px;
        font-style: italic;
        color: var(--ink-faint);
        letter-spacing: -0.02em;
        opacity: 0.6;
    }

    .card-no-img-label {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--ink-faint);
        opacity: 0.8;
    }

    /* Card body */
    .card-body {
        padding: 22px 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 12.5px;
        color: var(--ink-muted);
        flex-wrap: wrap;
    }

    .card-author {
        color: var(--ink);
        font-weight: 600;
    }

    .card-dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #cccac2;
        flex-shrink: 0;
    }

    .card-date, .card-read {
        color: var(--ink-muted);
    }

    .card-title {
        font-family: var(--font-display);
        font-size: 22px;
        font-weight: 400;
        line-height: 1.3;
        color: var(--ink);
        margin-bottom: 10px;
        letter-spacing: -0.015em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color var(--transition);
    }

    .article-card:hover .card-title, .article-card-featured:hover .card-title {
        color: var(--accent);
    }

    .card-excerpt {
        font-size: 14px;
        line-height: 1.65;
        color: var(--ink-secondary);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-weight: 400;
        margin-bottom: 16px;
    }

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid var(--border-subtle);
    }

    .card-footer-text {
        font-size: 12.5px;
        font-weight: 500;
        color: var(--ink-muted);
        transition: color var(--transition);
    }

    .article-card:hover .card-footer-text, .article-card-featured:hover .card-footer-text {
        color: var(--accent);
    }

    .card-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--surface-alt);
        color: var(--ink-muted);
        border: 1px solid var(--border-subtle);
        transition: all var(--transition);
    }

    .article-card:hover .card-arrow, .article-card-featured:hover .card-arrow {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        transform: translate(2px, -2px);
    }

    .card-arrow svg { width: 14px; height: 14px; }

    /* ── EMPTY STATE ── */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 72px 24px;
        background: var(--surface-alt);
        border: 1px dashed var(--border);
        border-radius: var(--radius-card);
        color: var(--ink-muted);
    }

    .empty-state-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 16px;
        color: var(--ink-faint);
    }

    .empty-state h3 {
        font-family: var(--font-display);
        font-size: 24px;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14.5px;
        font-weight: 400;
        max-width: 440px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ── PAGINATION ── */
    .pagination-wrap {
        margin-top: 60px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrap nav {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .pagination-wrap .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .pagination-wrap .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 14px;
        border-radius: var(--radius-pill);
        font-size: 13.5px;
        font-weight: 500;
        color: var(--ink-secondary);
        text-decoration: none;
        border: 1px solid var(--border);
        background: var(--surface);
        transition: all var(--transition);
        font-family: var(--font-body);
    }

    .pagination-wrap .page-item .page-link svg {
        width: 14px;
        height: 14px;
    }

    .pagination-wrap .page-item .page-link:hover {
        background: var(--surface-hover);
        color: var(--ink);
        border-color: #d4d0c5;
        transform: translateY(-1px);
    }

    .pagination-wrap .page-item.active .page-link {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
        font-weight: 600;
    }

    .pagination-wrap .page-item.disabled .page-link {
        opacity: 0.35;
        pointer-events: none;
        background: var(--surface-alt);
    }

    /* ── RESPONSIVE MEDIA QUERIES ── */
    @media (max-width: 960px) {
        .article-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .article-card-featured { grid-template-columns: 1fr; }
        .article-card-featured .card-img-wrap { min-height: 240px; }
        .article-card-featured .card-body { padding: 24px; }
        .masthead { flex-direction: column; align-items: flex-start; gap: 24px; }
        .search-field-wrap { min-width: 220px; }
    }

    @media (max-width: 640px) {
        .blog-wrap { padding: 36px 16px 64px; }
        .article-grid { grid-template-columns: 1fr; gap: 20px; }
        .search-form { width: 100%; }
        .search-field-wrap { width: 100%; min-width: 0; flex: 1; }
        .masthead { margin-bottom: 32px; padding-bottom: 24px; }
    }
</style>
@endpush

@section('content')
<div class="blog-wrap">

    {{-- ── MASTHEAD ── --}}
    <div class="masthead">
        <div class="masthead-left">
            <span class="masthead-eyebrow">Tulisan &amp; Ide</span>
            <h1 class="masthead-title">
                Semua <em>Artikel</em>
            </h1>
        </div>

        {{-- Search form --}}
        <form method="GET" action="{{ route('home') }}" class="search-form">
            <div class="search-field-wrap">
                <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari artikel…"
                    class="search-input"
                    autocomplete="off"
                >
            </div>

            <button type="submit" class="btn-search">Cari</button>

            @if ($search)
                <a href="{{ route('home') }}" class="btn-reset">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 1l10 10M11 1L1 11"/>
                    </svg>
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- ── SEARCH RESULT INFO ── --}}
    @if ($search)
        <p class="search-info">
            Menampilkan <strong>{{ $articles->total() }} artikel</strong>
            untuk "<strong>{{ $search }}</strong>"
        </p>
    @endif

    {{-- ── ARTICLE GRID ── --}}
    <div class="article-grid">
        @forelse ($articles as $article)
            @php
                $isFeatured = $loop->first && !$search && $articles->currentPage() === 1;
            @endphp
            <a href="{{ route('articles.show', $article->slug) }}" class="{{ $isFeatured ? 'article-card-featured' : 'article-card' }}" aria-label="{{ $article->title }}">

                {{-- Thumbnail --}}
                <div class="card-img-wrap">
                    @if ($article->image)
                        <img
                            src="{{ $article->image_url ?? Storage::url($article->image) }}"
                            alt="{{ $article->title }}"
                            class="card-img"
                            loading="{{ $isFeatured ? 'eager' : 'lazy' }}"
                        >
                    @else
                        <div class="card-no-img">
                            <span class="card-no-img-badge">NP</span>
                            <span class="card-no-img-label">Editorial</span>
                        </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="card-body">
                    <div class="card-meta">
                        @if ($article->user)
                            <span class="card-author">{{ $article->user->name }}</span>
                            <span class="card-dot"></span>
                        @endif
                        <span class="card-date">{{ $article->created_at->translatedFormat('d M Y') }}</span>
                        <span class="card-dot"></span>
                        <span class="card-read">{{ $article->reading_time }} mnt baca</span>
                    </div>

                    <h2 class="card-title">{{ $article->title }}</h2>
                    <p class="card-excerpt">{{ Str::limit(strip_tags($article->body), $isFeatured ? 200 : 110) }}</p>

                    <div class="card-footer">
                        <span class="card-footer-text">Baca selengkapnya</span>
                        <span class="card-arrow" aria-hidden="true">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M7 17L17 7M17 7H7M17 7v10"/>
                            </svg>
                        </span>
                    </div>
                </div>

            </a>
        @empty
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                @if ($search)
                    <p>Tidak ada artikel yang cocok dengan pencarian "<strong>{{ $search }}</strong>".</p>
                    <a href="{{ route('home') }}" class="btn-search" style="display:inline-flex; align-items:center; margin-top:16px; text-decoration:none;">
                        Tampilkan Semua Artikel
                    </a>
                @else
                    <p>Belum ada artikel yang dipublikasikan.</p>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ── PAGINATION ── --}}
    @if ($articles->hasPages())
        <div class="pagination-wrap">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection