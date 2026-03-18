@extends('layouts.app')
@section('title', 'Jadwal Dokter')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-calendar2-week me-2"></i>Jadwal Dokter</h2>
    <p>Lihat jadwal praktik dan ketersediaan kuota dokter — Hari ini: <strong>{{ $today }}</strong></p>
</div>

<!-- Today's schedule with remaining quota -->
<h5 class="fw-bold mb-3"><i class="bi bi-calendar-check me-1"></i> Jadwal Hari Ini ({{ $today }})</h5>
<div class="row g-3 mb-4">
    @forelse($todaySchedules as $schedule)
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">{{ $schedule->shift }} &middot; {{ $schedule->room->room_name }}</div>
                        <h6 class="fw-bold mb-2">{{ $schedule->doctor->name }}</h6>
                        <small class="text-secondary">
                            <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="stat-value {{ $schedule->remaining_quota > 0 ? '' : 'text-danger' }}">{{ $schedule->remaining_quota }}</div>
                        <small class="text-secondary">sisa kuota</small>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="progress" style="height: 4px; background: var(--dark);">
                        @php $pct = ($schedule->remaining_quota / $schedule->max_quota) * 100; @endphp
                        <div class="progress-bar" style="width: {{ $pct }}%; background: {{ $pct > 30 ? 'var(--success)' : ($pct > 0 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body text-center py-4">
                    <p class="text-secondary mb-0">Tidak ada jadwal dokter untuk hari ini.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Full weekly schedule -->
<h5 class="fw-bold mb-3"><i class="bi bi-calendar3 me-1"></i> Jadwal Mingguan</h5>
<div class="card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Shift</th>
                        <th>Dokter</th>
                        <th>Ruangan</th>
                        <th>Jam Praktik</th>
                        <th>Kuota Maks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $day => $daySchedules)
                        @foreach($daySchedules as $index => $schedule)
                            <tr>
                                @if($index === 0)
                                    <td rowspan="{{ $daySchedules->count() }}" class="fw-bold align-middle">{{ $day }}</td>
                                @endif
                                <td>
                                    <span class="badge {{ $schedule->shift === 'Pagi' ? 'badge-shift-pagi' : ($schedule->shift === 'Sore' ? 'badge-shift-sore' : 'badge-shift-malam') }}">
                                        {{ $schedule->shift }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $schedule->doctor->name }}</td>
                                <td>{{ $schedule->room->room_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td>{{ $schedule->max_quota }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
