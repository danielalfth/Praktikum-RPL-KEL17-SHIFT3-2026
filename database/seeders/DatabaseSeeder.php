<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Qlinic',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'gender' => 'Laki-laki',
                'age' => 30,
            ]
        );

        $doctors = [
            ['name' => 'dr. Andi Pratama', 'email' => 'doctor1@doctor.com', 'code' => 'A'],
            ['name' => 'dr. Budi Santoso', 'email' => 'doctor2@doctor.com', 'code' => 'B'],
            ['name' => 'dr. Citra Dewi', 'email' => 'doctor3@doctor.com', 'code' => 'C'],
            ['name' => 'dr. Dian Sari', 'email' => 'doctor4@doctor.com', 'code' => 'D'],
            ['name' => 'dr. Eko Wijaya', 'email' => 'doctor5@doctor.com', 'code' => 'E'],
            ['name' => 'dr. Fitri Handayani', 'email' => 'doctor6@doctor.com', 'code' => 'F'],
            ['name' => 'dr. Gilang Ramadhan', 'email' => 'doctor7@doctor.com', 'code' => 'G'],
            ['name' => 'dr. Hana Permata', 'email' => 'doctor8@doctor.com', 'code' => 'H'],
            ['name' => 'dr. Irfan Hakim', 'email' => 'doctor9@doctor.com', 'code' => 'I'],
        ];

        foreach ($doctors as $doc) {
            User::updateOrCreate(
                ['email' => $doc['email']],
                [
                    'name' => $doc['name'],
                    'password' => Hash::make('password'),
                    'role' => 'doctor',
                    'doctor_code' => $doc['code'],
                    'gender' => 'Laki-laki',
                    'age' => 35,
                ]
            );
        }

        $rooms = [];
        for ($i = 1; $i <= 3; $i++) {
            $rooms[$i] = Room::updateOrCreate(
                ['room_name' => 'Ruang ' . $i],
                ['room_name' => 'Ruang ' . $i]
            );
        }

        $shifts = [
            'Pagi' => ['start' => '08:00', 'end' => '14:00', 'doctors' => ['A', 'D', 'G'], 'rooms' => [1, 2, 3]],
            'Sore' => ['start' => '15:00', 'end' => '21:00', 'doctors' => ['B', 'E', 'H'], 'rooms' => [1, 2, 3]],
            'Malam' => ['start' => '22:00', 'end' => '04:00', 'doctors' => ['C', 'F', 'I'], 'rooms' => [1, 2, 3]],
        ];

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($days as $day) {
            foreach ($shifts as $shiftName => $shiftData) {
                foreach ($shiftData['doctors'] as $index => $doctorCode) {
                    $doctor = User::where('doctor_code', $doctorCode)->first();
                    $room = $rooms[$shiftData['rooms'][$index]];

                    Schedule::updateOrCreate(
                        [
                            'doctor_id' => $doctor->id,
                            'room_id' => $room->id,
                            'day_of_week' => $day,
                            'shift' => $shiftName,
                        ],
                        [
                            'start_time' => $shiftData['start'],
                            'end_time' => $shiftData['end'],
                            'max_quota' => 20,
                        ]
                    );
                }
            }
        }

        User::updateOrCreate(
            ['email' => 'pasien@gmail.com'],
            [
                'name' => 'Pasien Demo',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'gender' => 'Laki-laki',
                'age' => 25,
            ]
        );
    }
}
