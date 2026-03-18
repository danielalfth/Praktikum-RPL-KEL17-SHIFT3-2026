<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Qlinic',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'gender' => 'Laki-laki',
            'age' => 30,
        ]);

        // Create 9 Doctors (A through I)
        $doctors = [
            ['name' => 'dr. Andi Pratama',    'email' => 'doctor1@doctor.com', 'code' => 'A'],
            ['name' => 'dr. Budi Santoso',    'email' => 'doctor2@doctor.com', 'code' => 'B'],
            ['name' => 'dr. Citra Dewi',      'email' => 'doctor3@doctor.com', 'code' => 'C'],
            ['name' => 'dr. Dian Sari',       'email' => 'doctor4@doctor.com', 'code' => 'D'],
            ['name' => 'dr. Eko Wijaya',      'email' => 'doctor5@doctor.com', 'code' => 'E'],
            ['name' => 'dr. Fitri Handayani', 'email' => 'doctor6@doctor.com', 'code' => 'F'],
            ['name' => 'dr. Gilang Ramadhan', 'email' => 'doctor7@doctor.com', 'code' => 'G'],
            ['name' => 'dr. Hana Permata',    'email' => 'doctor8@doctor.com', 'code' => 'H'],
            ['name' => 'dr. Irfan Hakim',     'email' => 'doctor9@doctor.com', 'code' => 'I'],
        ];

        foreach ($doctors as $doc) {
            User::create([
                'name' => $doc['name'],
                'email' => $doc['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'doctor_code' => $doc['code'],
                'gender' => 'Laki-laki',
                'age' => 35,
            ]);
        }

        // Create 3 Rooms
        $rooms = [];
        for ($i = 1; $i <= 3; $i++) {
            $rooms[$i] = Room::create(['room_name' => 'Ruang ' . $i]);
        }

        // Shift definitions
        $shifts = [
            'Pagi'  => ['start' => '08:00', 'end' => '14:00', 'doctors' => ['A', 'D', 'G'], 'rooms' => [1, 2, 3]],
            'Sore'  => ['start' => '15:00', 'end' => '21:00', 'doctors' => ['B', 'E', 'H'], 'rooms' => [1, 2, 3]],
            'Malam' => ['start' => '22:00', 'end' => '04:00', 'doctors' => ['C', 'F', 'I'], 'rooms' => [1, 2, 3]],
        ];

        // Days of the week (weekdays)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Create schedules for each day × shift × doctor-room
        foreach ($days as $day) {
            foreach ($shifts as $shiftName => $shiftData) {
                foreach ($shiftData['doctors'] as $index => $doctorCode) {
                    $doctor = User::where('doctor_code', $doctorCode)->first();
                    $roomId = $rooms[$shiftData['rooms'][$index]]->id;

                    Schedule::create([
                        'doctor_id' => $doctor->id,
                        'room_id' => $roomId,
                        'day_of_week' => $day,
                        'shift' => $shiftName,
                        'start_time' => $shiftData['start'],
                        'end_time' => $shiftData['end'],
                        'max_quota' => 20,
                    ]);
                }
            }
        }

        // Create a sample patient
        User::create([
            'name' => 'Pasien Demo',
            'email' => 'pasien@gmail.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'gender' => 'Laki-laki',
            'age' => 25,
        ]);
    }
}
