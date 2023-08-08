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
        Schema::create('book_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->OnDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->OnDelete('cascade');
            $table->unsignedSmallInteger('count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_cart');
    }
};
