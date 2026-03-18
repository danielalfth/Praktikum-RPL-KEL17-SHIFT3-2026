@extends('layouts.app')
@section('title', 'Daftar Pasien')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-people-fill me-2"></i>Daftar Pasien</h2>
            <p>Antrean pasien shift <strong>{{ $currentShift }}</strong> — {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="refresh-indicator">
            <span class="dot"></span> Auto-refresh aktif
        </div>
    </div>
</div>

@if(!$schedule)
    <div class="card-modern">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-x" style="font-size: 3rem; color: var(--text-secondary);"></i>
            <h5 class="mt-3 text-secondary">Tidak ada jadwal untuk shift saat ini</h5>
            <p class="text-secondary">Anda tidak memiliki jadwal praktik pada shift {{ $currentShift }} hari ini.</p>
        </div>
    </div>
@else
    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(99,102,241,0.15); color: var(--primary-light);"><i class="bi bi-geo-alt"></i></div>
                <div class="stat-value">{{ $schedule->room->room_name }}</div>
                <div class="stat-label">Ruangan</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245,158,11,0.15); color: var(--warning);"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-value" id="stat-menunggu">{{ $schedule->queues->where('status', 'Menunggu')->count() }}</div>
                <div class="stat-label">Menunggu</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(99,102,241,0.15); color: var(--primary-light);"><i class="bi bi-person-check"></i></div>
                <div class="stat-value" id="stat-diperiksa">{{ $schedule->queues->where('status', 'Diperiksa')->count() }}</div>
                <div class="stat-label">Diperiksa</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16,185,129,0.15); color: var(--success);"><i class="bi bi-check-circle"></i></div>
                <div class="stat-value" id="stat-selesai">{{ $schedule->queues->where('status', 'Selesai')->count() }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Queue List -->
    <div class="card-modern" id="queue-table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-1"></i> Antrean Pasien</span>
            <span class="badge bg-primary">{{ $schedule->queues->count() }} pasien</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>No. Antrean</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="queue-tbody">
                        @forelse($schedule->queues as $queue)
                            <tr>
                                <td><span class="fw-bold" style="color: var(--primary-light);">{{ $queue->queue_number }}</span></td>
                                <td class="fw-semibold">{{ $queue->patient->name }}</td>
                                <td>{{ Str::limit($queue->complaint, 50) }}</td>
                                <td><span class="badge-status badge-{{ strtolower($queue->status) }}">{{ $queue->status }}</span></td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if($queue->status === 'Menunggu')
                                            <form action="{{ route('doctor.queue.updateStatus', $queue->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="Diperiksa">
                                                <button class="btn btn-sm btn-primary"><i class="bi bi-telephone-inbound me-1"></i> Panggil</button>
                                            </form>
                                            <form action="{{ route('doctor.queue.updateStatus', $queue->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="Dilewati">
                                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-skip-forward me-1"></i> Lewati</button>
                                            </form>
                                        @elseif($queue->status === 'Diperiksa')
                                            <a href="{{ route('doctor.medical-records.create', $queue->id) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-journal-medical me-1"></i> Isi Rekam Medis
                                            </a>
                                        @elseif($queue->status === 'Dilewati')
                                            <form action="{{ route('doctor.queue.updateStatus', $queue->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="Menunggu">
                                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-arrow-counterclockwise me-1"></i> Kembalikan</button>
                                            </form>
                                        @elseif($queue->status === 'Selesai')
                                            @if($queue->medicalRecord)
                                                <a href="{{ route('doctor.medical-records.show', $queue->medicalRecord->id) }}" class="btn btn-sm btn-glass">
                                                    <i class="bi bi-eye me-1"></i> Lihat RM
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-secondary">Belum ada pasien di antrean.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
setInterval(function() {
    fetch('{{ route("api.queue.status") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.queues) {
            let menunggu = 0, diperiksa = 0, selesai = 0;
            data.queues.forEach(q => {
                if (q.status === 'Menunggu') menunggu++;
                if (q.status === 'Diperiksa') diperiksa++;
                if (q.status === 'Selesai') selesai++;
            });
            document.getElementById('stat-menunggu').textContent = menunggu;
            document.getElementById('stat-diperiksa').textContent = diperiksa;
            document.getElementById('stat-selesai').textContent = selesai;
        }
    })
    .catch(err => console.log('Auto-refresh error:', err));
}, 5000);
</script>
@endpush
