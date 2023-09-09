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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('comment');
            $table->boolean('status')->default(false);
            $table->foreignId('user_id')->constrained('users')->OnDelete('cascade');
            $table->foreignId('book_id')->nullable()->constrained('books')->OnDelete('cascade');
            $table->foreignId('article_id')->nullable()->constrained('articles')->OnDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
