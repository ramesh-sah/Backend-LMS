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
        Schema::create('dues', function (Blueprint $table) {
            $table->uuid('due_id')->primary();
            $table->string('description', 400);
            $table->string('amount', 5);
            $table->enum('due_status', ['cleared',  'pending'])->default('pending');
            $table->string('member_id');
            $table->foreign('member_id')->references('member_id')->on('members')->onDelete('cascade');
            $table->string('employee_id')->nullable();
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->string('book_id')->nullable();
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->string('issue_id')->nullable();
            $table->foreign('issue_id')->references('issue_id')->on('issues')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dues');
    }
};
