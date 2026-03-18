<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display schedules based on user role
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Schedule::getTodayName();

        $schedules = Schedule::with(['doctor', 'room'])
            ->orderByRaw("FIELD(shift, 'Pagi', 'Sore', 'Malam')")
            ->orderBy('room_id')
            ->get()
            ->groupBy('day_of_week');

        // Calculate remaining quota for today for each schedule
        $todaySchedules = Schedule::with(['doctor', 'room'])
            ->where('day_of_week', $today)
            ->orderByRaw("FIELD(shift, 'Pagi', 'Sore', 'Malam')")
            ->orderBy('room_id')
            ->get();

        $view = match ($user->role) {
            'admin' => 'admin.schedule',
            'doctor' => 'doctor.schedule',
            'patient' => 'patient.schedule',
        };

        return view($view, [
            'schedules' => $schedules,
            'todaySchedules' => $todaySchedules,
            'today' => $today,
            'currentShift' => Schedule::getCurrentShift(),
        ]);
    }

    /**
     * Doctor updates own schedule quota
     */
    public function updateQuota(Request $request, Schedule $schedule)
    {
        $user = auth()->user();

        if ($user->role !== 'doctor' || $schedule->doctor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'max_quota' => 'required|integer|min:1|max:50',
        ]);

        $schedule->update(['max_quota' => $request->max_quota]);

        return back()->with('success', 'Kuota berhasil diperbarui.');
    }
}
