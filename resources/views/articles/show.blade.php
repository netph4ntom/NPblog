{{--
    views/articles/show.blade.php
    Halaman publik untuk membaca satu artikel secara lengkap.
    Diakses via URL: /articles/{slug}
--}}
@extends('layouts.public')

@section('title', $article->title)
@section('meta_description', Str::limit(strip_tags($article->body), 155))
@section('og_type', 'article')
@section('og_image', $article->image_url ?? ($article->image ? Storage::url($article->image) : asset('images/Logo.jpg')))

@push('styles')
<style>
    /* ── PAGE LAYOUT ── */
    .article-page {
        max-width: 740px;
        margin: 0 auto;
        padding: 44px 24px 96px;
        animation: pageIn 400ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── BACK LINK ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--ink-muted);
        text-decoration: none;
        padding: 6px 14px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border);
        background: var(--surface);
        margin-bottom: 36px;
        transition: all var(--transition);
    }

    .back-link svg { width: 14px; height: 14px; flex-shrink: 0; transition: transform var(--transition); }
    .back-link:hover { color: var(--ink); border-color: #d4d0c5; background: var(--surface-hover); }
    .back-link:hover svg { transform: translateX(-3px); }

    /* ── ARTICLE HEADER ── */
    .article-header { margin-bottom: 36px; }

    .article-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 14px;
    }

    .article-category::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent);
    }

    .article-title {
        font-family: var(--font-display);
        font-size: clamp(32px, 5.5vw, 52px);
        font-weight: 400;
        line-height: 1.12;
        letter-spacing: -0.025em;
        color: var(--ink);
        margin-bottom: 22px;
    }

    /* ── META ROW ── */
    .article-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px 12px;
        padding: 16px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    .meta-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--ink);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        font-weight: 600;
        flex-shrink: 0;
        letter-spacing: 0.02em;
    }

    .meta-author {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink);
    }

    .meta-sep {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #cccac2;
        flex-shrink: 0;
    }

    .meta-date,
    .meta-read,
    .meta-edited {
        font-size: 13px;
        color: var(--ink-muted);
    }

    .meta-edited {
        font-style: italic;
    }

    .meta-badge {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 500;
        padding: 2px 10px;
        border-radius: var(--radius-pill);
        background: var(--surface-alt);
        color: var(--ink-muted);
        border: 1px solid var(--border);
        margin-left: auto;
    }

    /* ── HERO IMAGE ── */
    .article-hero {
        margin: 36px 0;
        border-radius: var(--radius-card);
        overflow: hidden;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        aspect-ratio: 16/9;
    }

    .article-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        animation: heroFade 500ms ease forwards;
    }

    @keyframes heroFade {
        from { opacity: 0; transform: scale(1.02); }
        to   { opacity: 1; transform: scale(1); }
    }

    /* ── ARTICLE BODY ── */
    .article-body {
        font-family: var(--font-body);
        font-size: 18px;
        line-height: 1.85;
        color: var(--ink);
        font-weight: 400;
        letter-spacing: -0.005em;
    }

    .article-body p {
        margin-bottom: 1.6em;
    }

    .article-body p:first-child::first-letter {
        font-family: var(--font-display);
        font-size: 4.2em;
        line-height: 0.8;
        float: left;
        margin: 4px 12px 0 0;
        color: var(--ink);
    }

    /* ── DIVIDER ── */
    .article-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 52px 0;
    }

    .article-divider::before,
    .article-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .article-divider-dots {
        display: flex;
        gap: 6px;
    }

    .article-divider-dots span {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--ink-faint);
    }

    /* ── SHARE ROW ── */
    .share-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        margin: 40px 0 20px;
        flex-wrap: wrap;
    }

    .share-label {
        font-size: 13.5px;
        font-weight: 500;
        color: var(--ink-muted);
    }

    .share-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-share {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 34px;
        padding: 0 14px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border);
        background: var(--surface-alt);
        color: var(--ink);
        font-size: 12.5px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        font-family: var(--font-body);
        transition: all var(--transition);
    }

    .btn-share svg { width: 14px; height: 14px; }
    .btn-share:hover {
        background: var(--surface-hover);
        border-color: #d4d0c5;
        transform: translateY(-1px);
        color: var(--accent);
    }
    .btn-share:active { transform: scale(0.97); }

    /* ── BOTTOM CTA ── */
    .article-cta {
        margin-top: 56px;
        padding: 36px 40px;
        background: var(--surface-alt);
        border-radius: var(--radius-card);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .cta-text-label {
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 4px;
    }

    .cta-text-heading {
        font-family: var(--font-display);
        font-size: 24px;
        font-weight: 400;
        color: var(--ink);
        letter-spacing: -0.015em;
    }

    .btn-back-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 44px;
        padding: 0 24px;
        background: var(--ink);
        color: #fff;
        border: 1px solid var(--ink);
        border-radius: var(--radius-pill);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: all var(--transition);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-back-cta svg { width: 14px; height: 14px; }
    .btn-back-cta:hover { background: var(--accent); border-color: var(--accent); color: #fff; transform: translateY(-1px); }
    .btn-back-cta:active { transform: scale(0.97); }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        .article-page { padding: 28px 16px 64px; }
        .article-hero { margin: 24px 0; }
        .meta-badge { margin-left: 0; width: 100%; text-align: center; }
        .share-box { flex-direction: column; align-items: stretch; gap: 12px; }
        .share-buttons { justify-content: flex-start; }
        .article-cta { padding: 24px; flex-direction: column; align-items: stretch; text-align: left; }
        .btn-back-cta { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="article-page">

    {{-- ── BACK LINK ── --}}
    <a href="{{ route('home') }}" class="back-link">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M19 12H5M5 12l7-7M5 12l7 7"/>
        </svg>
        Kembali ke Beranda
    </a>

    {{-- ── ARTICLE HEADER ── --}}
    <header class="article-header">
        <span class="article-category">Artikel</span>
        <h1 class="article-title">{{ $article->title }}</h1>

        {{-- Meta row --}}
        <div class="article-meta">
            {{-- Avatar inisial --}}
            @php
                $initials = collect(explode(' ', $article->user->name ?? 'Admin'))
                    ->filter()
                    ->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                $wordCount = str_word_count(strip_tags($article->body));
            @endphp

            <div class="meta-avatar" aria-hidden="true">{{ $initials ?: 'A' }}</div>
            <span class="meta-author">{{ $article->user->name ?? 'Admin' }}</span>
            <span class="meta-sep"></span>
            <span class="meta-date">{{ $article->created_at->translatedFormat('d M Y') }}</span>
            <span class="meta-sep"></span>
            <span class="meta-read">{{ $article->reading_time }} mnt baca</span>

            @if ($article->updated_at->ne($article->created_at))
                <span class="meta-sep"></span>
                <span class="meta-edited">Diedit {{ $article->updated_at->diffForHumans() }}</span>
            @endif

            <span class="meta-badge">
                {{ number_format($wordCount) }} kata
            </span>
        </div>
    </header>

    {{-- ── HERO IMAGE ── --}}
    @if ($article->image)
        <div class="article-hero">
            <img
                src="{{ $article->image_url ?? Storage::url($article->image) }}"
                alt="{{ $article->title }}"
                loading="eager"
            >
        </div>
    @endif

    {{-- ── ARTICLE BODY ── --}}
    <div class="article-body">
        {!! nl2br(e($article->body)) !!}
    </div>

    {{-- ── SHARE BOX ── --}}
    <div class="share-box">
        <span class="share-label">Bagikan tulisan ini:</span>
        <div class="share-buttons">
            <button type="button" class="btn-share" onclick="copyArticleLink()" id="copyBtn">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                <span id="copyText">Salin Tautan</span>
            </button>
            <a href="https://api.whatsapp.com/send?text={{ rawurlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn-share">
                WhatsApp
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ rawurlencode($article->title) }}&url={{ rawurlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="btn-share">
                X / Twitter
            </a>
        </div>
    </div>

    {{-- ── DIVIDER ── --}}
    <div class="article-divider" aria-hidden="true">
        <div class="article-divider-dots">
            <span></span><span></span><span></span>
        </div>
    </div>

    {{-- ── BOTTOM CTA ── --}}
    <div class="article-cta">
        <div>
            <p class="cta-text-label">Selesai membaca</p>
            <p class="cta-text-heading">Temukan artikel lainnya</p>
        </div>
        <a href="{{ route('home') }}" class="btn-back-cta">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M5 12l7-7M5 12l7 7"/>
            </svg>
            Lihat Semua Artikel
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function copyArticleLink() {
        if (!navigator.clipboard) return;
        navigator.clipboard.writeText(window.location.href).then(() => {
            const text = document.getElementById('copyText');
            if (text) {
                const original = text.textContent;
                text.textContent = 'Tersalin!';
                setTimeout(() => text.textContent = original, 2000);
            }
        }).catch(() => {});
    }
</script>
@endpush
