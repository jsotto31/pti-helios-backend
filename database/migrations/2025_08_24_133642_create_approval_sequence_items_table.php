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
        Schema::create('approval_sequence_items', function (Blueprint $table) {
            $table->id();
            $table->morphs("application");
            $table->string("employee_id");
            $table->string("status")->default("pending");
            $table->boolean("can_approve")->default(false);
            $table->boolean("last_approver")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_sequence_items');
    }
};
