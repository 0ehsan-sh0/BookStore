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
        Schema::create('book_translator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->OnDelete('cascade');
            $table->foreignId('translator_id')->constrained('translators')->OnDelete('cascade');

            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_translator');
    }
};
