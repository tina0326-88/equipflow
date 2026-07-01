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
    Schema::create('repairs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('equipment_id');
        $table->string('title', 200);
        $table->text('description');
        $table->string('status')->default('pending');
        $table->string('priority')->default('medium');

        $table->unsignedBigInteger('reported_by');
        $table->unsignedBigInteger('assigned_to')->nullable();

        $table->timestamp('reported_at')->nullable();
        $table->timestamp('completed_at')->nullable();

        $table->timestamps();

        // FK（可選）
        $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }

    
};
