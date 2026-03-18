@extends('layouts.app')
@section('title', 'Rekam Medis')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-file-earmark-medical me-2"></i>Rekam Medis (Verifikasi)</h2>
    <p>Lihat rekam medis seluruh pasien (read-only)</p>
</div>

@if($records->isEmpty())
    <div class="card-modern">
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-medical" style="font-size: 3rem; color: var(--text-secondary);"></i>
            <h5 class="mt-3 text-secondary">Belum ada rekam medis</h5>
        </div>
    </div>
@else
    <div class="card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Antrean</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Diagnosis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="fw-bold" style="color: var(--primary-light);">{{ $record->queue->queue_number }}</span></td>
                                <td class="fw-semibold">{{ $record->patient->name }}</td>
                                <td>{{ $record->doctor->name }}</td>
                                <td>{{ Str::limit($record->diagnosis, 40) }}</td>
                                <td>
                                    <a href="{{ route('admin.medical-records.show', $record->id) }}" class="btn btn-sm btn-glass">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
