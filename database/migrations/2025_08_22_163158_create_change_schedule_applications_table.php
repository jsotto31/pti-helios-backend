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
        Schema::create('change_schedule_applications', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->date("date_from")->nullable();
            $table->date("date_to")->nullable();
            $table->date("date")->nullable();
            $table->string("type")->default("permanent");
            $table->text("reason")->nullable();
            $table->boolean("allow_approver")->default(false);
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
        Schema::dropIfExists('change_schedule_applications');
    }
};
