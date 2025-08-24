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
        Schema::create('overtime_applications', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->date("date");
            $table->time("time_from");
            $table->time("time_to");
            $table->time("duration");
            $table->text("reason")->nullable();
            $table->boolean("allow_approver");
            $table->string("status")->default("pending");
            $table->string("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_applications');
    }
};
