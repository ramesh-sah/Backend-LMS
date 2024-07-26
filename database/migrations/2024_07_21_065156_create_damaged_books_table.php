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
        Schema::create('damaged_books', function (Blueprint $table) {
            $table->uuid('damaged_book_id')->primary();
            $table->string('book_id');
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->date('damaged_on')->default(now());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_books');
    }
};
