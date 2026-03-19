@extends('layouts.app')
@section('title', 'Manajemen Antrean')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-gear-wide-connected me-2"></i>Manajemen Antrean</h2>
            <p>Shift <strong>{{ $currentShift ?? 'Di Luar Jam Operasional' }}</strong> — {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="refresh-indicator">
                <span class="dot"></span> Auto-refresh aktif
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQueueModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Antrean
            </button>
        </div>
    </div>
</div>

<!-- Room Cards -->
<div class="row g-4" id="rooms-container">
    @forelse($schedules as $schedule)
        <div class="col-md-4">
            <div class="room-card">
                <div class="room-card-header">
                    <div>
                        <h6><i class="bi bi-door-open me-1"></i> {{ $schedule->room->room_name }}</h6>
                        <small class="text-secondary">{{ $schedule->doctor->name }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $schedule->remaining_quota > 0 ? 'bg-success' : 'bg-danger' }}">
                            Kuota: {{ $schedule->remaining_quota }}/{{ $schedule->max_quota }}
                        </span>
                    </div>
                </div>
                <div class="p-0">
                    @if($schedule->queues->isEmpty())
                        <div class="text-center py-4 text-secondary">
                            <i class="bi bi-inbox"></i> Belum ada antrean
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-modern mb-0" style="font-size: 0.85rem;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Pasien</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedule->queues as $queue)
                                        <tr>
                                            <td><span class="fw-bold" style="color: var(--primary-light);">{{ $queue->queue_number }}</span></td>
                                            <td class="fw-semibold">{{ $queue->patient->name }}</td>
                                            <td><span class="badge-status badge-{{ strtolower($queue->status) }}">{{ $queue->status }}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-glass dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                                                    <ul class="dropdown-menu">
                                                        @if($queue->status === 'Menunggu')
                                                            <li>
                                                                <form action="{{ route('admin.queue.updateStatus', $queue->id) }}" method="POST">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="Diperiksa">
                                                                    <button class="dropdown-item"><i class="bi bi-telephone-inbound me-1"></i> Panggil</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.queue.updateStatus', $queue->id) }}" method="POST">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="Dibatalkan">
                                                                    <button class="dropdown-item"><i class="bi bi-x-circle me-1"></i> Batalkan</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if($queue->status === 'Diperiksa')
                                                            <li>
                                                                <form action="{{ route('admin.queue.updateStatus', $queue->id) }}" method="POST">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="Selesai">
                                                                    <button class="dropdown-item"><i class="bi bi-check-circle me-1"></i> Selesai</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if($queue->status === 'Dilewati')
                                                            <li>
                                                                <form action="{{ route('admin.queue.updateStatus', $queue->id) }}" method="POST">
                                                                    @csrf @method('PUT')
                                                                    <input type="hidden" name="status" value="Menunggu">
                                                                    <button class="dropdown-item"><i class="bi bi-arrow-counterclockwise me-1"></i> Kembalikan</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: var(--text-secondary);"></i>
                    <h5 class="mt-3 text-secondary">Tidak ada jadwal dokter untuk shift {{ $currentShift }} hari ini</h5>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Add Queue Modal -->
<div class="modal fade" id="addQueueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-1"></i> Tambah Antrean</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.queue.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Pasien</label>
                        <select name="patient_id" class="form-select" required id="patientSelect">
                            <option value="">-- Cari/Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->phone ?? $patient->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Dokter / Ruangan</label>
                        <select name="schedule_id" class="form-select" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ $schedule->remaining_quota <= 0 ? 'disabled' : '' }}>
                                    {{ $schedule->doctor->name }} — {{ $schedule->room->room_name }}
                                    (Sisa: {{ $schedule->remaining_quota }})
                                    {{ $schedule->remaining_quota <= 0 ? '⛔ PENUH' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keluhan Awal <span class="text-danger">*</span></label>
                        <textarea name="complaint" class="form-control" rows="3" placeholder="Deskripsi keluhan pasien..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Daftarkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Hash-based change detection for reliable auto-refresh
var lastAdminHash = '';

function computeAdminHash(data) {
    if (!data.schedules || data.schedules.length === 0) return 'empty';
    return data.schedules.map(function(s) {
        var queueInfo = '';
        if (s.queues && s.queues.length > 0) {
            queueInfo = s.queues.map(function(q) { return q.id + ':' + q.status; }).join(',');
        }
        return s.id + '[' + queueInfo + ']';
    }).join('|');
}

// Capture initial state
@if($schedules->isEmpty())
    lastAdminHash = 'empty';
@else
    lastAdminHash = '{!! $schedules->map(function($s) { $qi = $s->queues->map(fn($q) => $q->id . ":" . $q->status)->implode(","); return $s->id . "[" . $qi . "]"; })->implode("|") !!}';
@endif
    
setInterval(function() {
    fetch('{{ route("api.queue.status") }}', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(function(r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(function(data) {
        var newHash = computeAdminHash(data);
        if (newHash !== lastAdminHash) {
            console.log('Admin queue data changed, reloading...', lastAdminHash, '->', newHash);
            location.reload();
        }
    })
    .catch(function(err) { console.log('Auto-refresh error:', err); });
}, 5000);
</script>
@endpush
