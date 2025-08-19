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
            $table->foreignId("correction_application_id");
            $table->time("actual_time_in")->nullable();
            $table->time("actual_time_out")->nullable();
            $table->time("request_time_in")->nullable();
            $table->time("request_time_out")->nullable();
            $table->string("status")->default("NEW");
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
