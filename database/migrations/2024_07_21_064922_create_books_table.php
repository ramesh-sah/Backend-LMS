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
            $table->uuid('book_id')->primary();
            $table->date('added_date')->default(now());
            $table->enum('book_status', ['available', 'reserved', 'issued'])->default('available');
            $table->string('purchase_id');
            $table->foreign('purchase_id')->references('purchase_id')->on('book_purchases')->onDelete('cascade');


            // $table->string('image_id');
            // $table->foreign('image_id')->references('image_id')->on('cover_images');
            // $table->string('publisher_id');
            // $table->foreign('publisher_id')->references('publisher_id')->on('publishers');
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
