<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Display queue based on role
     */
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $currentShift = Schedule::getCurrentShift();
        $dayName = Schedule::getTodayName();

        if ($user->role === 'admin') {
            // Admin sees all queues for today grouped by room/doctor
            $schedules = Schedule::with(['doctor', 'room', 'queues' => function ($q) use ($today) {
                $q->where('date', $today)->with('patient')->orderBy('id');
            }])
            ->where('day_of_week', $dayName)
            ->where('shift', $currentShift)
            ->orderBy('room_id')
            ->get();

            // Get all patients for search/selection
            $patients = User::where('role', 'patient')->orderBy('name')->get();

            return view('admin.queue', compact('schedules', 'patients', 'today', 'currentShift'));

        } elseif ($user->role === 'doctor') {
            // Doctor sees only their queue for today
            $schedule = Schedule::with(['room', 'queues' => function ($q) use ($today) {
                $q->where('date', $today)->with('patient')->orderBy('id');
            }])
            ->where('doctor_id', $user->id)
            ->where('day_of_week', $dayName)
            ->where('shift', $currentShift)
            ->first();

            return view('doctor.queue', compact('schedule', 'today', 'currentShift'));

        } else {
            // Patient sees their own queue status for today
            $queues = Queue::with(['schedule.doctor', 'schedule.room'])
                ->where('patient_id', $user->id)
                ->where('date', $today)
                ->orderBy('id', 'desc')
                ->get();

            return view('patient.queue', compact('queues', 'today'));
        }
    }

    /**
     * Admin registers a patient to queue
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'complaint' => 'required|string|max:1000',
        ]);

        $schedule = Schedule::with('doctor')->findOrFail($request->schedule_id);
        $today = Carbon::today()->toDateString();

        // Check quota
        $usedQuota = Queue::where('schedule_id', $schedule->id)
            ->where('date', $today)
            ->whereNotIn('status', ['Dibatalkan'])
            ->count();

        if ($usedQuota >= $schedule->max_quota) {
            return back()->with('error', 'Kuota dokter ' . $schedule->doctor->name . ' sudah penuh untuk hari ini.');
        }

        // Generate queue number
        $queueNumber = Queue::generateQueueNumber($schedule->doctor->doctor_code, $today);

        Queue::create([
            'queue_number' => $queueNumber,
            'patient_id' => $request->patient_id,
            'schedule_id' => $schedule->id,
            'status' => 'Menunggu',
            'complaint' => $request->complaint,
            'date' => $today,
        ]);

        return back()->with('success', 'Pasien berhasil didaftarkan ke antrean dengan nomor: ' . $queueNumber);
    }

    /**
     * Update queue status
     */
    public function updateStatus(Request $request, Queue $queue)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diperiksa,Selesai,Dilewati,Dibatalkan',
        ]);

        $oldStatus = $queue->status;
        $queue->update(['status' => $request->status]);

        $statusMessages = [
            'Menunggu' => 'Status antrean dikembalikan ke Menunggu.',
            'Diperiksa' => 'Pasien sedang diperiksa.',
            'Selesai' => 'Pemeriksaan pasien selesai.',
            'Dilewati' => 'Pasien dilewati.',
            'Dibatalkan' => 'Antrean pasien dibatalkan.',
        ];

        return back()->with('success', $statusMessages[$request->status] ?? 'Status diperbarui.');
    }

    /**
     * AJAX endpoint for live queue status
     */
    public function apiStatus(Request $request)
    {
        // Release session to prevent blocking
        session_write_close();

        $today = Carbon::today()->toDateString();
        $user = auth()->user();
        $currentShift = Schedule::getCurrentShift();
        $dayName = Schedule::getTodayName();

        if ($user->role === 'patient') {
            $queues = Queue::with(['schedule.doctor', 'schedule.room'])
                ->where('patient_id', $user->id)
                ->where('date', $today)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json(['queues' => $queues]);
        }

        if ($user->role === 'doctor') {
            $schedule = Schedule::where('doctor_id', $user->id)
                ->where('day_of_week', $dayName)
                ->where('shift', $currentShift)
                ->first();

            if (!$schedule) {
                return response()->json(['queues' => []]);
            }

            $queues = Queue::with('patient')
                ->where('schedule_id', $schedule->id)
                ->where('date', $today)
                ->orderBy('id')
                ->get();

            return response()->json([
                'queues' => $queues,
                'schedule' => $schedule->load(['doctor', 'room']),
            ]);
        }

        // Admin: all rooms for current shift
        $schedules = Schedule::with(['doctor', 'room', 'queues' => function ($q) use ($today) {
            $q->where('date', $today)->with('patient')->orderBy('id');
        }])
        ->where('day_of_week', $dayName)
        ->where('shift', $currentShift)
        ->orderBy('room_id')
        ->get();

        return response()->json(['schedules' => $schedules]);
    }
}
