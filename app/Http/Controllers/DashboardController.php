<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.queue'),
            'doctor' => redirect()->route('doctor.queue'),
            'patient' => redirect()->route('patient.queue'),
            default => redirect()->route('login'),
        };
    }

    public function adminDashboard()
    {
        return redirect()->route('admin.queue');
    }

    public function doctorDashboard()
    {
        return redirect()->route('doctor.queue');
    }

    public function patientDashboard()
    {
        return redirect()->route('patient.queue');
    }
}
