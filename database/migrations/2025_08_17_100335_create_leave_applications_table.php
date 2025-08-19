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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->date("date_from");
            $table->date("date_to");
            $table->integer("number_of_days");
            $table->string("type");
            $table->text("reason")->nullable();
            $table->boolean("allow_approver");
            $table->boolean("with_pay");
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
        Schema::dropIfExists('leave_applications');
    }
};
