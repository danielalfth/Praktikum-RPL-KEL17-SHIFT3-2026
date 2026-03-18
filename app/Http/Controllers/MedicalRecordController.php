<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Queue;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Display medical records based on role
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'patient') {
            $records = MedicalRecord::with(['doctor', 'queue.schedule.room'])
                ->where('patient_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('patient.medical-record', compact('records'));

        } elseif ($user->role === 'doctor') {
            $records = MedicalRecord::with(['patient', 'queue.schedule.room'])
                ->where('doctor_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('doctor.medical-record-index', compact('records'));

        } else {
            // Admin: all records read-only
            $records = MedicalRecord::with(['patient', 'doctor', 'queue.schedule.room'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.medical-record', compact('records'));
        }
    }

    /**
     * Show form to create medical record (doctor only)
     */
    public function create(Queue $queue)
    {
        $user = auth()->user();

        if ($user->role !== 'doctor') {
            abort(403);
        }

        // Check if record already exists
        if ($queue->medicalRecord) {
            return redirect()->route('medical-records.edit', $queue->medicalRecord->id);
        }

        $queue->load(['patient', 'schedule.room']);

        return view('doctor.medical-record-create', compact('queue'));
    }

    /**
     * Store medical record (doctor only)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'doctor') {
            abort(403);
        }

        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'examination_result' => 'required|string',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
        ]);

        $queue = Queue::findOrFail($request->queue_id);

        MedicalRecord::create([
            'queue_id' => $queue->id,
            'patient_id' => $queue->patient_id,
            'doctor_id' => $user->id,
            'examination_result' => $request->examination_result,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
        ]);

        // Update queue status to Selesai
        $queue->update(['status' => 'Selesai']);

        return redirect()->route('doctor.queue')->with('success', 'Rekam medis berhasil disimpan.');
    }

    /**
     * Show medical record detail
     */
    public function show(MedicalRecord $medicalRecord)
    {
        $user = auth()->user();

        // Patient can only see own records
        if ($user->role === 'patient' && $medicalRecord->patient_id !== $user->id) {
            abort(403);
        }

        $medicalRecord->load(['patient', 'doctor', 'queue.schedule.room']);

        $view = match ($user->role) {
            'doctor' => 'doctor.medical-record-show',
            'admin' => 'admin.medical-record-show',
            default => 'patient.medical-record-show',
        };

        return view($view, compact('medicalRecord'));
    }

    /**
     * Edit medical record (doctor only)
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        $user = auth()->user();

        if ($user->role !== 'doctor' || $medicalRecord->doctor_id !== $user->id) {
            abort(403);
        }

        $medicalRecord->load(['patient', 'queue.schedule.room']);

        return view('doctor.medical-record-edit', compact('medicalRecord'));
    }

    /**
     * Update medical record (doctor only)
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $user = auth()->user();

        if ($user->role !== 'doctor' || $medicalRecord->doctor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'examination_result' => 'required|string',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
        ]);

        $medicalRecord->update([
            'examination_result' => $request->examination_result,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
        ]);

        return redirect()->route('doctor.medical-records')->with('success', 'Rekam medis berhasil diperbarui.');
    }
}
