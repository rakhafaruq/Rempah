<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->string('location');
            $table->timestamp('pickup_deadline');
            $table->integer('total_portion');
            $table->integer('remaining_portion');
            $table->enum('status', ['tersedia','habis','expired'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('donations');
    }
};