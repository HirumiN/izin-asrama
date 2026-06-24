<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prayer_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('prayer_time'); // subuh, dzuhur, ashar, maghrib, isya
            $table->string('status'); // berjamaah, munfarid, sakit, izin, alpa
            $table->timestamps();

            // Memastikan mahasiswa hanya memiliki 1 catatan per waktu shalat per hari
            $table->unique(['student_id', 'date', 'prayer_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_attendances');
    }
};
