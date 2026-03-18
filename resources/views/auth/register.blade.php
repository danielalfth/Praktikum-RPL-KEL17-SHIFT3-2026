@extends('layouts.app')
@section('title', 'Daftar Akun')

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
        padding: 2rem 0;
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
    .auth-card {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.6);
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.06);
        width: 100%;
        max-width: 500px;
        padding: 2.5rem 2rem;
        position: relative;
        z-index: 1;
    }
    .auth-brand {
        text-align: center;
        margin-bottom: 1.75rem;
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
    }
    .auth-brand p {
        font-size: 0.8rem;
        color: #6B7194;
        margin: 0.25rem 0 0;
    }
    .auth-card .form-control, .auth-card .form-select {
        background: rgba(244,246,251,0.7);
        border: 1.5px solid #E8ECF4;
        padding: 0.6rem 0.85rem;
    }
    .auth-card .form-control:focus, .auth-card .form-select:focus {
        background: white;
        border-color: #6C63FF;
    }
    .auth-card .btn-primary {
        background: linear-gradient(135deg, #6C63FF, #8B83FF);
        border: none;
        padding: 0.65rem;
        font-weight: 600;
    }
    .auth-card .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(108,99,255,0.3);
    }
    .auth-link { color: #6C63FF; text-decoration: none; font-weight: 600; }
    .auth-link:hover { color: #5A52E0; }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="icon-wrapper"><i class="bi bi-heart-pulse-fill"></i></div>
            <h3>Pendaftaran Pasien Baru</h3>
            <p>Lengkapi data di bawah untuk membuat akun</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2" style="font-size: 0.82rem; border-radius: 10px;">
                @foreach($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nama lengkap" value="{{ old('name') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-muted" style="font-weight: 400;">(opsional)</span></label>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="">Pilih...</option>
                        <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Usia <span class="text-danger">*</span></label>
                    <input type="number" name="age" class="form-control" placeholder="Tahun" min="1" max="150" value="{{ old('age') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mt-1">
                Buat Akun <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="font-size: 0.82rem; color: #6B7194; margin: 0;">
                Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection
