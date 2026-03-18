@extends('layouts.app')
@section('title', 'Rekam Medis')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-file-earmark-medical me-2"></i>Riwayat Rekam Medis</h2>
    <p>Riwayat pemeriksaan medis Anda (read-only)</p>
</div>

@if($records->isEmpty())
    <div class="card-modern">
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-medical" style="font-size: 3rem; color: var(--text-secondary);"></i>
            <h5 class="mt-3 text-secondary">Belum ada rekam medis</h5>
            <p class="text-secondary">Rekam medis akan muncul setelah Anda menjalani pemeriksaan.</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($records as $record)
            <div class="col-md-6">
                <div class="card-modern h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">
                            <i class="bi bi-calendar3 me-1"></i> {{ $record->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                        <span class="badge bg-success">Selesai</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-secondary">Dokter</small>
                            <div class="fw-semibold">{{ $record->doctor->name }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-secondary">Hasil Pemeriksaan</small>
                            <div>{{ Str::limit($record->examination_result, 100) }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-secondary">Diagnosis</small>
                            <div>{{ Str::limit($record->diagnosis, 100) }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-secondary">Resep Obat</small>
                            <div>{{ Str::limit($record->prescription, 100) }}</div>
                        </div>
                        <a href="{{ route('patient.medical-records.show', $record->id) }}" class="btn btn-sm btn-glass w-100">
                            <i class="bi bi-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
