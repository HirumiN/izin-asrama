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
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['pesiar', 'bermalam']);
            $table->string('destination');
            $table->text('reason')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned_on_time', 'returned_late'])->default('pending');
            $table->timestamp('actual_return_time')->nullable();
            $table->integer('lateness_duration')->nullable(); // Dalam menit
            $table->string('return_photo')->nullable();
            $table->string('return_location')->nullable();
            $table->foreignId('action_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('action_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
