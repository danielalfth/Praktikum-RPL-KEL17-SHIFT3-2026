@extends('layouts.app')
@section('title', 'Status Antrean')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-list-ol me-2"></i>Status Antrean</h2>
            <p>Pantau status antrean Anda hari ini — {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="refresh-indicator">
            <span class="dot"></span> Auto-refresh aktif
        </div>
    </div>
</div>

<div id="queue-container">
    @if($queues->isEmpty())
        <div class="card-modern">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-secondary);"></i>
                <h5 class="mt-3 text-secondary">Belum ada antrean hari ini</h5>
                <p class="text-secondary">Silakan datang ke klinik dan daftarkan diri melalui resepsionis.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($queues as $queue)
                <div class="col-md-6">
                    <div class="card-modern">
                        <div class="card-body text-center p-4">
                            <div class="queue-number mb-2">{{ $queue->queue_number }}</div>
                            <div class="mb-3">
                                <span class="badge-status badge-{{ strtolower($queue->status) }}">
                                    @if($queue->status === 'Diperiksa')
                                        <span class="pulse-dot me-1" style="background: var(--primary-light);"></span>
                                    @endif
                                    {{ $queue->status }}
                                </span>
                            </div>
                            <hr style="border-color: var(--border-color);">
                            <div class="row text-start mt-3">
                                <div class="col-6 mb-2">
                                    <small class="text-secondary d-block">Dokter</small>
                                    <span class="fw-semibold">{{ $queue->schedule->doctor->name }}</span>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-secondary d-block">Ruangan</small>
                                    <span class="fw-semibold">{{ $queue->schedule->room->room_name }}</span>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-secondary d-block">Jam Praktik</small>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($queue->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($queue->schedule->end_time)->format('H:i') }}</span>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-secondary d-block">Shift</small>
                                    <span class="fw-semibold">{{ $queue->schedule->shift }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Hash-based change detection for reliable auto-refresh
    let lastDataHash = '';

    function computeHash(data) {
        // Create a string fingerprint from queue data
        if (!data.queues || data.queues.length === 0) return 'empty';
        return data.queues.map(q => q.id + ':' + q.status + ':' + q.queue_number).join('|');
    }

    // Capture initial state from server-rendered page
    @if($queues->isEmpty())
        lastDataHash = 'empty';
    @else
        lastDataHash = '{!! $queues->map(fn($q) => $q->id . ":" . $q->status . ":" . $q->queue_number)->implode("|") !!}';
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
            var newHash = computeHash(data);
            if (newHash !== lastDataHash) {
                console.log('Queue data changed, reloading...', lastDataHash, '->', newHash);
                location.reload();
            }
        })
        .catch(function(err) { console.log('Auto-refresh error:', err); });
    }, 5000);
</script>
@endpush
