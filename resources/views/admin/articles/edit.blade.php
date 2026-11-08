{{-- 
    views/admin/articles/edit.blade.php
    Form untuk mengedit artikel yang sudah ada.
    Data artikel di-populate otomatis dari database via $article.
    Design system disesuaikan dengan create.blade.php
--}}
@extends('layouts.app')

@push('styles')
<style>
    /* ── PAGE WRAP ── */
    .form-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 44px 24px 88px;
        animation: pageIn 350ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes pageIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── BACK LINK ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        color: var(--ink-muted);
        text-decoration: none;
        padding: 6px 14px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border);
        background: var(--surface);
        transition: all var(--transition);
        margin-bottom: 18px;
    }

    .back-link svg { width: 14px; height: 14px; transition: transform var(--transition); }
    .back-link:hover { color: var(--ink); border-color: #d4d0c5; background: var(--surface-hover); }
    .back-link:hover svg { transform: translateX(-3px); }

    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
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
        font-size: clamp(28px, 4vw, 36px);
        font-weight: 400;
        letter-spacing: -0.02em;
        color: var(--ink);
        line-height: 1.1;
    }

    /* Badge tanggal terakhir diedit */
    .edit-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #b45309;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: var(--radius-pill);
        padding: 5px 12px;
        margin-top: 4px;
        align-self: flex-start;
    }

    .edit-badge svg { width: 13px; height: 13px; }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(20, 20, 19, 0.02);
    }

    .form-body {
        padding: 32px 32px 0;
        display: flex;
        flex-direction: column;
        gap: 26px;
    }

    /* ── FIELD ── */
    .field { display: flex; flex-direction: column; gap: 7px; }

    .field-label {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .field-label .required { color: #dc2626; font-size: 15px; line-height: 1; }

    .field-input {
        width: 100%;
        height: 44px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface-alt);
        padding: 0 16px;
        font-family: var(--font-body);
        font-size: 14px;
        color: var(--ink);
        outline: none;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    }

    .field-input::placeholder { color: var(--ink-faint); }
    .field-input:focus {
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(30, 79, 217, 0.12);
    }

    .field-input.is-error { border-color: #dc2626; background: #fff5f5; }
    .field-input.is-error:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12); }

    .field-textarea {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface-alt);
        padding: 14px 16px;
        font-family: var(--font-body);
        font-size: 14.5px;
        color: var(--ink);
        outline: none;
        resize: vertical;
        min-height: 240px;
        line-height: 1.7;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    }

    .field-textarea::placeholder { color: var(--ink-faint); }
    .field-textarea:focus {
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(30, 79, 217, 0.12);
    }

    .field-textarea.is-error { border-color: #dc2626; background: #fff5f5; }

    .field-hint { font-size: 12.5px; color: var(--ink-muted); }

    .field-error {
        font-size: 12.5px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }

    .field-error svg { width: 14px; height: 14px; flex-shrink: 0; }

    /* ── IMAGE UPLOAD ── */
    .upload-zone {
        border: 1.5px dashed var(--border);
        border-radius: 12px;
        background: var(--surface-alt);
        padding: 28px 24px;
        text-align: center;
        cursor: pointer;
        transition: border-color var(--transition), background var(--transition);
        position: relative;
    }

    .upload-zone:hover { border-color: var(--accent); background: var(--accent-light); }

    .upload-zone.has-file {
        border-style: solid;
        border-color: var(--border);
        background: var(--surface);
        padding: 0;
        overflow: hidden;
    }

    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .upload-placeholder svg {
        width: 36px;
        height: 36px;
        color: var(--ink-faint);
        margin: 0 auto 10px;
        display: block;
    }

    .upload-placeholder p { font-size: 14px; font-weight: 500; color: var(--ink); margin-bottom: 4px; }
    .upload-placeholder span { font-size: 12px; color: var(--ink-muted); }

    /* Gambar existing (sebelum diganti) */
    .existing-image-wrap {
        position: relative;
        display: block;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border);
        margin-bottom: 12px;
    }

    .existing-image-wrap img {
        width: 100%;
        max-height: 260px;
        object-fit: cover;
        display: block;
    }

    .existing-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(20, 20, 19, 0.7);
        color: #fff;
        font-size: 11.5px;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: var(--radius-pill);
        backdrop-filter: blur(4px);
    }

    /* Preview gambar baru */
    #preview {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        display: none;
    }

    #preview.visible { display: block; }

    .upload-change-hint {
        position: absolute;
        inset: 0;
        background: rgba(20, 20, 19, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 13.5px;
        font-weight: 500;
        gap: 6px;
        backdrop-filter: blur(3px);
    }

    .upload-change-hint svg { width: 16px; height: 16px; }
    .upload-zone.has-file:hover .upload-change-hint { display: flex; }

    /* ── CHAR COUNTER ── */
    .field-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .char-counter {
        font-size: 12.5px;
        color: var(--ink-muted);
        font-variant-numeric: tabular-nums;
        flex-shrink: 0;
    }

    /* ── FORM ACTIONS ── */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 22px 32px;
        border-top: 1px solid var(--border);
        background: var(--surface-alt);
        margin-top: 32px;
    }

    .btn-cancel {
        height: 42px;
        padding: 0 20px;
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
        transition: all var(--transition);
    }

    .btn-cancel:hover { background: var(--surface-hover); color: var(--ink); border-color: #d4d0c5; }

    .btn-submit {
        height: 42px;
        padding: 0 24px;
        background: var(--ink);
        color: #fff;
        border: 1px solid var(--ink);
        border-radius: var(--radius-pill);
        font-family: var(--font-body);
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all var(--transition);
    }

    .btn-submit svg { width: 15px; height: 15px; }
    .btn-submit:hover { background: var(--accent); border-color: var(--accent); color: #fff; transform: translateY(-1px); }
    .btn-submit:active { transform: scale(0.97); }

    @media (max-width: 600px) {
        .form-wrap { padding: 28px 16px 64px; }
        .form-body { padding: 22px 18px 0; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .btn-cancel, .btn-submit { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- ── BACK + HEADER ── --}}
    <a href="{{ route('admin.articles.index') }}" class="back-link">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M19 12H5M5 12l7-7M5 12l7 7"/>
        </svg>
        Kembali ke Daftar Artikel
    </a>

    <div class="page-header">
        <div>
            <p class="page-eyebrow">Admin · Artikel</p>
            <h1 class="page-title">Edit Artikel</h1>
        </div>
        {{-- Badge info terakhir diedit --}}
        <div class="edit-badge">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
            </svg>
            Terakhir diubah {{ $article->updated_at->diffForHumans() }}
        </div>
    </div>

    {{-- ── FORM ── --}}
    <form action="{{ route('admin.articles.update', $article) }}" method="POST"
          enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')

        <div class="form-body">

            {{-- Judul --}}
            <div class="field">
                <label for="title" class="field-label">
                    Judul <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $article->title) }}"
                    placeholder="Masukkan judul artikel…"
                    class="field-input {{ $errors->has('title') ? 'is-error' : '' }}"
                    autocomplete="off"
                    oninput="updateSlugPreview()"
                    required
                >
                <p class="field-hint" id="slugHint" style="margin-top:4px;">
                    URL: <span style="color:var(--accent); font-weight:500;">/articles/<span id="slugText">{{ $article->slug }}</span></span>
                </p>
                @error('title')
                    <p class="field-error">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Gambar --}}
            <div class="field">
                <label class="field-label">Gambar Utama</label>

                {{-- 
                    Jika artikel sudah punya gambar, tampilkan dulu sebagai existing image.
                    Upload zone di bawahnya untuk mengganti jika diperlukan.
                --}}
                @if ($article->image)
                    <div class="existing-image-wrap">
                        <img src="{{ $article->image_url ?? Storage::url($article->image) }}" alt="{{ $article->title }}">
                        <span class="existing-badge">Gambar saat ini</span>
                    </div>
                @endif

                <div class="upload-zone" id="uploadZone">
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        onchange="previewImage(event)"
                    >
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                        <p>Klik untuk ganti gambar</p>
                        <span>PNG, JPG, WEBP — maks. 2MB · Kosongkan untuk tetap pakai gambar lama</span>
                    </div>
                    <img id="preview" src="#" alt="Preview Baru">
                    <div class="upload-change-hint">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                        </svg>
                        Ganti Gambar
                    </div>
                </div>

                @error('image')
                    <p class="field-error">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Isi Artikel --}}
            <div class="field">
                <label for="body" class="field-label">
                    Isi Artikel <span class="required">*</span>
                </label>
                <textarea
                    id="body"
                    name="body"
                    placeholder="Tulis isi artikel di sini…"
                    class="field-textarea {{ $errors->has('body') ? 'is-error' : '' }}"
                    oninput="updateCounter()"
                >{{ old('body', $article->body) }}</textarea>
                <div class="field-footer">
                    @error('body')
                        <p class="field-error">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <span class="field-hint">Tulis dengan jelas dan informatif</span>
                    @enderror
                    <span class="char-counter" id="charCounter">0 kata</span>
                </div>
            </div>

        </div>{{-- /form-body --}}

        {{-- ── ACTIONS ── --}}
        <div class="form-actions">
            <a href="{{ route('admin.articles.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                </svg>
                Update Artikel
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    // Live slug preview
    function updateSlugPreview() {
        const title = document.getElementById('title').value.trim();
        const text  = document.getElementById('slugText');

        if (!title) return;

        const slug = title
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        text.textContent = slug || 'artikel';
    }

    // Image preview with validation
    function handleFile(file) {
        if (!file) return;

        // Validasi ukuran maks 2MB
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
            return;
        }

        // Validasi tipe image
        if (!file.type.startsWith('image/')) {
            alert('File yang dipilih harus berupa gambar!');
            return;
        }

        const preview     = document.getElementById('preview');
        const zone        = document.getElementById('uploadZone');
        const placeholder = document.getElementById('uploadPlaceholder');

        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.add('visible');
            placeholder.style.display = 'none';
            zone.classList.add('has-file');
        };
        reader.readAsDataURL(file);
    }

    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            handleFile(input.files[0]);
        }
    }

    // Drag & drop support
    const zone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('image');

    ['dragenter', 'dragover'].forEach(eventName => {
        zone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            zone.style.borderColor = 'var(--accent)';
            zone.style.background = 'var(--accent-light)';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        zone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            zone.style.borderColor = '';
            zone.style.background = '';
        }, false);
    });

    zone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (dt.files && dt.files[0]) {
            fileInput.files = dt.files;
            handleFile(dt.files[0]);
        }
    });

    // Word counter
    function updateCounter() {
        const body  = document.getElementById('body').value.trim();
        const words = body ? body.split(/\s+/).filter(Boolean).length : 0;
        document.getElementById('charCounter').textContent = words + ' kata';
    }

    // Form double-submit prevention
    const form = document.querySelector('form.form-card');
    form.addEventListener('submit', function() {
        const btn = form.querySelector('.btn-submit');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
            btn.innerHTML = `
                <svg class="animate-spin" style="animation: spin 1s linear infinite;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                    <path d="M12 2a10 10 0 0110 10" stroke-linecap="round"></path>
                </svg>
                Menyimpan…
            `;
        }
    });

    // Init counter on load
    updateCounter();
</script>
@endpush