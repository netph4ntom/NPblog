@extends('layouts.app')

@push('styles')
<style>
    .profile-wrap {
        max-width: 820px;
        margin: 0 auto;
        padding: 44px 24px 88px;
        animation: pageIn 350ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes pageIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .profile-header {
        margin-bottom: 32px;
    }

    .profile-eyebrow {
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

    .profile-eyebrow::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent);
    }

    .profile-page-title {
        font-family: var(--font-display);
        font-size: clamp(30px, 4.5vw, 40px);
        font-weight: 400;
        letter-spacing: -0.02em;
        color: var(--ink);
        line-height: 1.1;
    }

    .profile-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .profile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        padding: 32px;
        box-shadow: 0 2px 10px rgba(20, 20, 19, 0.02);
    }

    .profile-card.danger-zone {
        border-color: #fecaca;
        background: #fffafa;
    }

    .card-title {
        font-family: var(--font-display);
        font-size: 24px;
        font-weight: 400;
        letter-spacing: -0.015em;
        color: var(--ink);
        margin-bottom: 6px;
    }

    .card-desc {
        font-size: 13.5px;
        color: var(--ink-muted);
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-field label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
    }

    .form-input {
        width: 100%;
        height: 44px;
        padding: 0 16px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface-alt);
        font-family: var(--font-body);
        font-size: 14px;
        color: var(--ink);
        outline: none;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    }

    .form-input:focus {
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(30, 79, 217, 0.12);
    }

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

    .btn-submit:hover {
        background: var(--accent);
        border-color: var(--accent);
        transform: translateY(-1px);
    }

    .btn-submit:active { transform: scale(0.97); }

    .btn-danger {
        height: 42px;
        padding: 0 22px;
        background: #dc2626;
        color: #fff;
        border: 1px solid #dc2626;
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

    .btn-danger:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-danger:active { transform: scale(0.97); }

    .status-msg {
        font-size: 13px;
        font-weight: 500;
        color: #15803d;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    @media (max-width: 640px) {
        .profile-wrap { padding: 28px 16px 64px; }
        .profile-card { padding: 22px 18px; }
        .btn-submit, .btn-danger { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="profile-wrap">

    <div class="profile-header">
        <span class="profile-eyebrow">Admin · Akun</span>
        <h1 class="profile-page-title">Pengaturan Profil</h1>
    </div>

    <div class="profile-grid">

        <div class="profile-card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="profile-card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="profile-card danger-zone">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

</div>
@endsection
