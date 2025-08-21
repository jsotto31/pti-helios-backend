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
        Schema::create('schedule_shift_details', function (Blueprint $table) {
            $table->id();
            $table->integer("schedule_shift_id");
            $table->time("start")->nullable();
            $table->time("end")->nullable();
            $table->string("day")->nullable();
            $table->time("tardy_start")->nullable();
            $table->time("absent_start")->nullable();
            $table->time("early_dismiss")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_shift_details');
    }
};
