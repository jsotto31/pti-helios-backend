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
        Schema::create('approval_setup_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("approval_setup_id");
            $table->foreignId("approver_id");
            $table->integer("sequence");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_setup_items');
    }
};
