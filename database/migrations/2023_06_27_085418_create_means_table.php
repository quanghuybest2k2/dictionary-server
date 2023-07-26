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
        Schema::create('means', function (Blueprint $table) {
            $table->id();
            $table->foreignId('word_id')->constrained('words');
            $table->foreignId('word_type_id')->constrained('word_types')->onDelete('cascade');
            $table->string('means', 200)->nullable(false);
            $table->string('description', 1000)->nullable();
            $table->string('example', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('means');
    }
};
