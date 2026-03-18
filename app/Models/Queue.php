<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'queue_number', 'patient_id', 'schedule_id', 'status', 'complaint', 'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    /**
     * Generate next queue number for a doctor on a given date
     */
    public static function generateQueueNumber(string $doctorCode, string $date): string
    {
        $lastQueue = self::whereHas('schedule.doctor', function ($q) use ($doctorCode) {
            $q->where('doctor_code', $doctorCode);
        })
        ->where('date', $date)
        ->orderBy('id', 'desc')
        ->first();

        if ($lastQueue) {
            $lastNumber = (int) substr($lastQueue->queue_number, 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $doctorCode . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
