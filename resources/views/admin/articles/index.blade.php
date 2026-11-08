{{-- 
    views/admin/articles/index.blade.php
    Halaman dashboard admin untuk menampilkan daftar semua artikel.
--}}
@extends('layouts.app')

@push('styles')
<style>
    /* ── PAGE WRAP ── */
    .admin-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 44px 24px 88px;
    }

    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .page-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 8px;
    }

    .page-eyebrow::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent);
    }

    .page-title {
        font-family: var(--font-display);
        font-size: clamp(30px, 4vw, 42px);
        font-weight: 400;
        letter-spacing: -0.02em;
        color: var(--ink);
        line-height: 1.1;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        height: 42px;
        padding: 0 20px;
        background: var(--ink);
        color: #fff;
        border-radius: var(--radius-pill);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: all var(--transition);
        white-space: nowrap;
        flex-shrink: 0;
        border: 1px solid var(--ink);
        cursor: pointer;
    }

    .btn-primary svg { width: 15px; height: 15px; }
    .btn-primary:hover { background: var(--accent); border-color: var(--accent); color: #fff; transform: translateY(-1px); }
    .btn-primary:active { transform: scale(0.97); }

    /* ── TOOLBAR ── */
    .toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 440px;
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-faint);
        pointer-events: none;
        width: 15px;
        height: 15px;
    }

    .search-input {
        width: 100%;
        height: 42px;
        border: 1px solid var(--border);
        border-radius: var(--radius-pill);
        background: var(--surface);
        padding: 0 16px 0 40px;
        font-family: var(--font-body);
        font-size: 13.5px;
        color: var(--ink);
        outline: none;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
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
    .btn-search:hover { background: var(--accent); border-color: var(--accent); transform: translateY(-1px); }
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all var(--transition);
        white-space: nowrap;
    }
    .btn-reset:hover { background: var(--surface-hover); color: var(--ink); border-color: #d4d0c5; }
    .btn-reset svg { width: 12px; height: 12px; }

    /* ── SEARCH INFO ── */
    .search-info {
        font-size: 13.5px;
        color: var(--ink-muted);
        margin-bottom: 20px;
        padding: 10px 16px;
        background: var(--accent-light);
        border-radius: 10px;
        border-left: 3px solid var(--accent);
    }
    .search-info strong { color: var(--ink); font-weight: 600; }

    /* ── TOAST ── */
    .toast {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 13.5px;
        font-weight: 500;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        animation: slideDown 300ms ease forwards;
    }

    .toast svg { width: 18px; height: 18px; flex-shrink: 0; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── STAT CHIPS ── */
    .stat-row {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .stat-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-pill);
        font-size: 13px;
        color: var(--ink-muted);
    }

    .stat-chip strong {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
    }

    /* ── TABLE CARD ── */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(20, 20, 19, 0.02);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead tr {
        background: var(--surface-alt);
        border-bottom: 1px solid var(--border);
    }

    .data-table th {
        padding: 12px 20px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--ink-faint);
        white-space: nowrap;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--border-subtle);
        transition: background var(--transition);
    }

    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: var(--surface-alt); }

    .data-table td {
        padding: 16px 20px;
        vertical-align: middle;
        font-size: 13.5px;
        color: var(--ink-muted);
    }

    /* Row number */
    .row-num {
        font-size: 12.5px;
        color: var(--ink-faint);
        font-variant-numeric: tabular-nums;
        width: 44px;
    }

    /* Thumbnail */
    .thumb {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        object-fit: cover;
        display: block;
        border: 1px solid var(--border);
    }

    .thumb-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background: linear-gradient(135deg, #fbfaf7 0%, #f0eee8 100%);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--ink-faint);
        font-family: var(--font-display);
        font-size: 18px;
        font-style: italic;
    }

    /* Title cell */
    .cell-title {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 4px;
        line-height: 1.35;
    }

    .cell-date-sub {
        display: none;
        font-size: 12px;
        color: var(--ink-muted);
        margin-bottom: 4px;
    }

    .cell-excerpt {
        font-size: 12.5px;
        color: var(--ink-secondary);
        font-weight: 400;
        line-height: 1.5;
    }

    /* Date */
    .cell-date {
        font-size: 13px;
        color: var(--ink-muted);
        white-space: nowrap;
    }

    /* ── ACTION BUTTONS ── */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 32px;
        padding: 0 12px;
        background: var(--surface-alt);
        color: var(--ink-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius-pill);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 500;
        transition: all var(--transition);
        white-space: nowrap;
    }

    .btn-view svg { width: 13px; height: 13px; }
    .btn-view:hover { background: var(--surface); color: var(--accent); border-color: var(--accent); transform: translateY(-1px); }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 32px;
        padding: 0 12px;
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        border-radius: var(--radius-pill);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 500;
        transition: all var(--transition);
        white-space: nowrap;
    }

    .btn-edit svg { width: 13px; height: 13px; }
    .btn-edit:hover { background: #fef3c7; border-color: #f59e0b; transform: translateY(-1px); }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 32px;
        padding: 0 12px;
        background: #fff1f1;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: var(--radius-pill);
        font-size: 12.5px;
        font-weight: 500;
        cursor: pointer;
        font-family: var(--font-body);
        transition: all var(--transition);
        white-space: nowrap;
    }

    .btn-delete svg { width: 13px; height: 13px; }
    .btn-delete:hover { background: #fee2e2; border-color: #ef4444; transform: translateY(-1px); }

    /* ── EMPTY STATE ── */
    .empty-row td {
        padding: 72px 24px;
        text-align: center;
        color: var(--ink-muted);
    }

    .empty-row svg {
        width: 44px;
        height: 44px;
        margin: 0 auto 14px;
        display: block;
        color: var(--ink-faint);
    }

    .empty-row p {
        font-size: 14.5px;
        margin-bottom: 16px;
    }

    /* ── PAGINATION ── */
    .pagination-wrap {
        margin-top: 32px;
        display: flex;
        justify-content: flex-end;
    }

    .pagination-wrap .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
    }

    .pagination-wrap .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: var(--radius-pill);
        font-size: 13px;
        font-weight: 500;
        color: var(--ink-muted);
        text-decoration: none;
        border: 1px solid var(--border);
        background: var(--surface);
        transition: all var(--transition);
        font-family: var(--font-body);
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
        opacity: .35;
        pointer-events: none;
        background: var(--surface-alt);
    }

    /* ── MOBILE RESPONSIVE ADAPTATION ── */
    @media (max-width: 768px) {
        .admin-wrap { padding: 28px 16px 64px; }
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .btn-primary { width: 100%; justify-content: center; }
        .toolbar { flex-direction: column; align-items: stretch; }
        .search-wrap { max-width: 100%; min-width: 0; }
        .btn-search { width: 100%; justify-content: center; }

        .table-card {
            background: transparent;
            border: none;
            box-shadow: none;
            overflow: visible;
        }

        .data-table, .data-table tbody {
            display: block;
            width: 100%;
        }

        .data-table thead {
            display: none;
        }

        .data-table tbody tr {
            display: grid;
            grid-template-columns: 60px 1fr;
            grid-template-areas:
                "thumb info"
                "actions actions";
            gap: 12px 14px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 4px rgba(20, 20, 19, 0.03);
        }

        .data-table tbody tr.empty-row {
            display: block;
            grid-template-columns: none;
        }

        .data-table td.row-num {
            display: none;
        }

        .data-table td:nth-child(2) {
            grid-area: thumb;
            padding: 0;
        }

        .data-table td:nth-child(2) .thumb,
        .data-table td:nth-child(2) .thumb-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 10px;
        }

        .data-table td:nth-child(3) {
            grid-area: info;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cell-date-sub {
            display: block;
        }

        .data-table td:nth-child(4) {
            display: none;
        }

        .data-table td:nth-child(5) {
            grid-area: actions;
            padding: 12px 0 0;
            border-top: 1px solid var(--border-subtle);
        }

        .action-group {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            width: 100%;
            gap: 8px;
        }

        .action-group form {
            display: contents;
        }

        .btn-view, .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
            height: 36px;
        }

        .pagination-wrap { justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="admin-wrap">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header">
        <div class="page-header-left">
            <p class="page-eyebrow">Admin Panel</p>
            <h1 class="page-title">Daftar Artikel</h1>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Artikel
        </a>
    </div>

    {{-- ── STAT CHIPS ── --}}
    <div class="stat-row">
        <div class="stat-chip">
            Total artikel&nbsp;<strong>{{ $articles->total() }}</strong>
        </div>
        @if ($search)
            <div class="stat-chip">
                Hasil pencarian&nbsp;<strong>{{ $articles->total() }}</strong>
            </div>
        @endif
    </div>

    {{-- ── TOOLBAR (search) ── --}}
    <form method="GET" action="{{ route('admin.articles.index') }}">
        <div class="toolbar">
            <div class="search-wrap">
                <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari judul atau isi artikel…"
                    class="search-input"
                    autocomplete="off"
                >
            </div>

            <button type="submit" class="btn-search">Cari</button>

            @if ($search)
                <a href="{{ route('admin.articles.index') }}" class="btn-reset">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- ── SEARCH INFO ── --}}
    @if ($search)
        <p class="search-info">
            Menampilkan <strong>{{ $articles->total() }} artikel</strong>
            untuk "<strong>{{ $search }}</strong>"
        </p>
    @endif

    {{-- ── SUCCESS TOAST ── --}}
    @if (session('success'))
        <div class="toast">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── TABLE ── --}}
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Artikel</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($articles as $article)
                    <tr>
                        {{-- No --}}
                        <td class="row-num">{{ $articles->firstItem() ? ($articles->firstItem() + $loop->index) : $loop->iteration }}</td>

                        {{-- Thumbnail --}}
                        <td>
                            @if ($article->image)
                                <img
                                    src="{{ $article->image_url ?? Storage::url($article->image) }}"
                                    alt="{{ $article->title }}"
                                    class="thumb"
                                    loading="lazy"
                                >
                            @else
                                <div class="thumb-placeholder">
                                    <span>Si</span>
                                </div>
                            @endif
                        </td>

                        {{-- Title + excerpt --}}
                        <td>
                            <p class="cell-title">{{ $article->title }}</p>
                            <p class="cell-date-sub">{{ $article->created_at->translatedFormat('d M Y') }}</p>
                            <p class="cell-excerpt">{{ Str::limit(strip_tags($article->body), 70) }}</p>
                        </td>

                        {{-- Date --}}
                        <td class="cell-date">
                            {{ $article->created_at->translatedFormat('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="action-group">
                                <a href="{{ route('articles.show', $article->slug) }}" class="btn-view" target="_blank" title="Lihat di Web">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat
                                </a>

                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn-edit">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.articles.destroy', $article) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus artikel ini?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr class="empty-row">
                        <td colspan="5">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            @if ($search)
                                <p>Tidak ada artikel yang cocok dengan pencarian "<strong>{{ $search }}</strong>".</p>
                                <a href="{{ route('admin.articles.index') }}" class="btn-primary" style="display:inline-flex; margin: 0 auto;">
                                    Reset Pencarian
                                </a>
                            @else
                                <p>Belum ada artikel. Mulai tambahkan!</p>
                                <a href="{{ route('admin.articles.create') }}" class="btn-primary" style="display:inline-flex; margin: 0 auto;">
                                    + Tambah Artikel Pertama
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── PAGINATION ── --}}
    @if ($articles->hasPages())
        <div class="pagination-wrap">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection