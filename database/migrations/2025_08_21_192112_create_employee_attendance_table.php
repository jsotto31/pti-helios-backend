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
        Schema::create('employee_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->references('employee_id')->on('users')->onDelete('cascade');
            $table->date('date');
            $table->time('sched_start');
            $table->time('sched_end');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->boolean('tardy');
            $table->unsignedInteger('tardy_seconds')->nullable();
            $table->boolean('undertime');
            $table->unsignedInteger('undertime_seconds')->nullable();
            $table->boolean('absent');
            $table->boolean('early_dismiss');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendance');
    }
};
