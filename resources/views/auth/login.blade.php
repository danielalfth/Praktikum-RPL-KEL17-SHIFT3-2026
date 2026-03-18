@extends('layouts.app')
@section('title', 'Login')

@push('styles')
<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #F4F6FB 0%, #EDE7F6 50%, #E3F2FD 100%);
        position: relative;
        overflow: hidden;
    }
    .auth-page::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(108,99,255,0.06) 0%, transparent 70%);
    }
    .auth-page::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,201,167,0.05) 0%, transparent 70%);
    }
    .auth-card {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.6);
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.06);
        width: 100%;
        max-width: 420px;
        padding: 2.5rem 2rem;
        position: relative;
        z-index: 1;
    }
    .auth-brand {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-brand .icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #6C63FF, #8B83FF);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }
    .auth-brand h3 {
        font-weight: 800;
        font-size: 1.3rem;
        color: #1A1D2E;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .auth-brand p {
        font-size: 0.8rem;
        color: #6B7194;
        margin: 0.25rem 0 0;
    }
    .auth-card .form-control {
        background: rgba(244,246,251,0.7);
        border: 1.5px solid #E8ECF4;
        padding: 0.65rem 0.9rem;
    }
    .auth-card .form-control:focus {
        background: white;
        border-color: #6C63FF;
    }
    .auth-card .input-group-text {
        background: rgba(244,246,251,0.7);
        border: 1.5px solid #E8ECF4;
        border-right: none;
        color: #9CA3C5;
    }
    .auth-card .input-group .form-control {
        border-left: none;
    }
    .auth-card .btn-primary {
        background: linear-gradient(135deg, #6C63FF, #8B83FF);
        border: none;
        padding: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.2px;
    }
    .auth-card .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(108,99,255,0.3);
    }
    .auth-link {
        color: #6C63FF;
        text-decoration: none;
        font-weight: 600;
    }
    .auth-link:hover {
        color: #5A52E0;
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="icon-wrapper">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <h3>Masuk ke Qlinic</h3>
            <p>Masukkan kredensial Anda untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2" style="font-size: 0.82rem; border-radius: 10px;">
                @foreach($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email atau Nomor Telepon</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="login" class="form-control" placeholder="email@contoh.com atau 08xx" value="{{ old('login') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" style="font-size: 0.82rem; color: #6B7194;" for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                Masuk <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="font-size: 0.82rem; color: #6B7194; margin: 0;">
                Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
