@extends('layouts.app')
@section('title', 'Detail Rekam Medis')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-file-earmark-medical me-2"></i>Detail Rekam Medis</h2>
            <p>Data pemeriksaan tanggal {{ $medicalRecord->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-glass">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header"><i class="bi bi-person me-1"></i> Informasi Pasien</div>
            <div class="card-body">
                <div class="mb-2"><small class="text-secondary">Nama</small><div class="fw-semibold">{{ $medicalRecord->patient->name }}</div></div>
                <div class="mb-2"><small class="text-secondary">Jenis Kelamin</small><div>{{ $medicalRecord->patient->gender }}</div></div>
                <div class="mb-2"><small class="text-secondary">Usia</small><div>{{ $medicalRecord->patient->age }} tahun</div></div>
                <div class="mb-2"><small class="text-secondary">Dokter Pemeriksa</small><div class="fw-semibold">{{ $medicalRecord->doctor->name }}</div></div>
                <div class="mb-2"><small class="text-secondary">Ruangan</small><div>{{ $medicalRecord->queue->schedule->room->room_name ?? '-' }}</div></div>
                <div><small class="text-secondary">Tanggal</small><div>{{ $medicalRecord->created_at->translatedFormat('d F Y, H:i') }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern mb-3">
            <div class="card-header"><i class="bi bi-clipboard2-pulse me-1"></i> Hasil Pemeriksaan</div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-wrap;">{{ $medicalRecord->examination_result }}</p>
            </div>
        </div>
        <div class="card-modern mb-3">
            <div class="card-header"><i class="bi bi-activity me-1"></i> Diagnosis</div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-wrap;">{{ $medicalRecord->diagnosis }}</p>
            </div>
        </div>
        <div class="card-modern">
            <div class="card-header"><i class="bi bi-capsule me-1"></i> Resep Obat</div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-wrap;">{{ $medicalRecord->prescription }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
