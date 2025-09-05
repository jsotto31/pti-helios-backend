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
        Schema::create('correction_application_items', function (Blueprint $table) {
            $table->id();
            $table->string("employee_id");
            $table->time("time_in")->nullable();
            $table->time("time_out")->nullable();
            $table->boolean("tardy")->default(false);
            $table->boolean("absent")->default(false);
            $table->boolean("early_dismiss")->default(false);
            $table->text("notes");
            $table->json("location");
            $table->json("other_details")->default(json_encode([
                'device_id' => 'facial_recog_01',
                'work_from_home' => 'work_from_home',
                'overtime_hours' => 'overtime_hours',
            ]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correction_application_items');
    }
};
