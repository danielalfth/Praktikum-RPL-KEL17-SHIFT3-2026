<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number'); // e.g. A-01, D-05
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->enum('status', ['Menunggu', 'Diperiksa', 'Selesai', 'Dilewati', 'Dibatalkan'])->default('Menunggu');
            $table->text('complaint'); // keluhan awal
            $table->date('date'); // queue date
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
