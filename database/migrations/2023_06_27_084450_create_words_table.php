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
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('word_name', 100)->nullable(false)->unique();
            $table->string('pronunciations', 100)->nullable(false)->comment('phát âm');
            $table->foreignId('specialization_id')->constrained('specializations')->onDelete('cascade');
            $table->string('synonymous', 1000)->nullable();
            $table->string('antonyms', 1000)->nullable();
            $table->integer('status')->default(0)->comment('0 = chưa duyệt, 1= duyệt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
