@extends('layouts.app')
@section('title', 'Edit Rekam Medis')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-pencil-square me-2"></i>Edit Rekam Medis</h2>
            <p>{{ $medicalRecord->patient->name }} — {{ $medicalRecord->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <a href="{{ route('doctor.medical-records') }}" class="btn btn-glass"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header"><i class="bi bi-person me-1"></i> Data Pasien</div>
            <div class="card-body">
                <div class="mb-2"><small class="text-secondary">Nama</small><div class="fw-semibold">{{ $medicalRecord->patient->name }}</div></div>
                <div class="mb-2"><small class="text-secondary">Jenis Kelamin</small><div>{{ $medicalRecord->patient->gender }}</div></div>
                <div class="mb-2"><small class="text-secondary">Usia</small><div>{{ $medicalRecord->patient->age }} tahun</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header"><i class="bi bi-clipboard2-pulse me-1"></i> Form Rekam Medis</div>
            <div class="card-body">
                <form action="{{ route('doctor.medical-records.update', $medicalRecord->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Hasil Pemeriksaan <span class="text-danger">*</span></label>
                        <textarea name="examination_result" class="form-control" rows="4" required>{{ old('examination_result', $medicalRecord->examination_result) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control" rows="3" required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Resep Obat <span class="text-danger">*</span></label>
                        <textarea name="prescription" class="form-control" rows="4" required>{{ old('prescription', $medicalRecord->prescription) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Perbarui Rekam Medis
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
