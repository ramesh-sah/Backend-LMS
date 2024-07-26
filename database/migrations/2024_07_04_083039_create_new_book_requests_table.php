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
        Schema::create('new_book_requests', function (Blueprint $table) {
            $table->uuid('request_id')->primary();
            $table->string('book_name', 225);
            $table->string('author_name', 225);
            $table->date('request_date')->default(now());
            $table->string('member_id');
            $table->foreign('member_id')->references('member_id')->on('members')->onDelete('cascade');
            $table->string('employee_id')->default('');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_book_requests');
    }
};
