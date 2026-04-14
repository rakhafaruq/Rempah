<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->cascadeOnDelete();
            $table->string('receiver_name');
            $table->enum('receiver_type', ['pemulung','tunawisma','ojol','lainnya']);
            $table->string('location');
            $table->text('story')->nullable();
            $table->string('photo_path');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('distributions');
    }
};