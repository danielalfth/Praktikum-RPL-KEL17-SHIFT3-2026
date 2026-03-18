@extends('layouts.app')
@section('title', 'Jadwal Dokter')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-calendar2-week me-2"></i>Jadwal Praktik Saya</h2>
    <p>Kelola jadwal dan kuota praktik mingguan Anda</p>
</div>

<div class="card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Shift</th>
                        <th>Ruangan</th>
                        <th>Jam Praktik</th>
                        <th>Kuota Maks</th>
                        <th>Sisa Kuota Hari Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $day => $daySchedules)
                        @foreach($daySchedules->where('doctor_id', auth()->id()) as $schedule)
                            <tr>
                                <td class="fw-bold">{{ $day }}</td>
                                <td>
                                    <span class="badge {{ $schedule->shift === 'Pagi' ? 'badge-shift-pagi' : ($schedule->shift === 'Sore' ? 'badge-shift-sore' : 'badge-shift-malam') }}">
                                        {{ $schedule->shift }}
                                    </span>
                                </td>
                                <td>{{ $schedule->room->room_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td>{{ $schedule->max_quota }}</td>
                                <td>
                                    @if($day === $today)
                                        <span class="fw-bold {{ $schedule->remaining_quota > 5 ? 'text-success' : ($schedule->remaining_quota > 0 ? 'text-warning' : 'text-danger') }}">
                                            {{ $schedule->remaining_quota }}
                                        </span>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-glass" data-bs-toggle="modal" data-bs-target="#quotaModal{{ $schedule->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Kuota
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="quotaModal{{ $schedule->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Kuota - {{ $day }}</h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('doctor.schedule.updateQuota', $schedule->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body">
                                                        <label class="form-label">Kuota Maksimal</label>
                                                        <input type="number" name="max_quota" class="form-control" value="{{ $schedule->max_quota }}" min="1" max="50">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
