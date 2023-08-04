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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('english_name')->nullable();
            $table->longText('description');
            $table->unsignedBigInteger('price');
            $table->string('photo');
            $table->unsignedSmallInteger('print_series');
            $table->string('isbn')->unique(); // شابک
            $table->string('book_cover_type')->nullable(); // نوع جلد
            $table->string('format'); // قطع
            $table->string('pages');
            $table->string('publish_year');
            $table->string('count');

            $table->foreignId('writer_id')->constrained('writers')->OnDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
