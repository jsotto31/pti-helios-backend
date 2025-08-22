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
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->references('employee_id')->on('users')->onDelete('cascade');
            $table->time("start")->nullable();
            $table->time("end")->nullable();
            $table->string("day")->nullable();
            $table->time("tardy_start")->nullable();
            $table->time("absent_start")->nullable();
            $table->time("early_dismiss")->nullable();
            $table->timestamp("date_effective")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
