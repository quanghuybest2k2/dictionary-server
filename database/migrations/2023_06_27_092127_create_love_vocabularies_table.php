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
        Schema::create('love_vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('english', 400)->nullable();
            $table->string('pronunciations', 100);
            $table->string('vietnamese', 400)->nullable();
            $table->string('Note', 400)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('love_vocabularies');
    }
};
