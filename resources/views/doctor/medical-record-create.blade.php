@extends('layouts.app')
@section('title', 'Isi Rekam Medis')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-journal-medical me-2"></i>Input Rekam Medis</h2>
            <p>Antrean: <strong>{{ $queue->queue_number }}</strong> — {{ $queue->patient->name }}</p>
        </div>
        <a href="{{ route('doctor.queue') }}" class="btn btn-glass"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header"><i class="bi bi-person me-1"></i> Data Pasien</div>
            <div class="card-body">
                <div class="mb-2"><small class="text-secondary">Nama</small><div class="fw-semibold">{{ $queue->patient->name }}</div></div>
                <div class="mb-2"><small class="text-secondary">Jenis Kelamin</small><div>{{ $queue->patient->gender }}</div></div>
                <div class="mb-2"><small class="text-secondary">Usia</small><div>{{ $queue->patient->age }} tahun</div></div>
                <div class="mb-2"><small class="text-secondary">Keluhan</small><div>{{ $queue->complaint }}</div></div>
                <div class="mb-2"><small class="text-secondary">Ruangan</small><div>{{ $queue->schedule->room->room_name }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header"><i class="bi bi-clipboard2-pulse me-1"></i> Form Rekam Medis</div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('doctor.medical-records.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="queue_id" value="{{ $queue->id }}">

                    <div class="mb-3">
                        <label class="form-label">Hasil Pemeriksaan <span class="text-danger">*</span></label>
                        <textarea name="examination_result" class="form-control" rows="4" placeholder="Tekanan darah, suhu, berat badan, dll." required>{{ old('examination_result') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control" rows="3" placeholder="Diagnosis penyakit.." required>{{ old('diagnosis') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Resep Obat <span class="text-danger">*</span></label>
                        <textarea name="prescription" class="form-control" rows="4" placeholder="Nama obat, dosis, dan aturan pakai.." required>{{ old('prescription') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Simpan Rekam Medis & Selesaikan Antrean
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
