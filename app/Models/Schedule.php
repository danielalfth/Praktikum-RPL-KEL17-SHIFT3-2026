<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id', 'room_id', 'day_of_week', 'shift', 'start_time', 'end_time', 'max_quota',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    /**
     * Get remaining quota for today
     */
    public function getRemainingQuotaAttribute(): int
    {
        $usedQuota = $this->queues()
            ->where('date', Carbon::today()->toDateString())
            ->whereNotIn('status', ['Dibatalkan'])
            ->count();

        return max(0, $this->max_quota - $usedQuota);
    }

    /**
     * Get the current shift based on time
     */
    public static function getCurrentShift(): string
    {
        $hour = (int) Carbon::now()->format('H');

        if ($hour >= 8 && $hour < 14) {
            return 'Pagi';
        } elseif ($hour >= 15 && $hour < 21) {
            return 'Sore';
        } else {
            return 'Malam';
        }
    }

    /**
     * Get day name in Indonesian
     */
    public static function getTodayName(): string
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $days[Carbon::now()->format('l')] ?? 'Senin';
    }
}
