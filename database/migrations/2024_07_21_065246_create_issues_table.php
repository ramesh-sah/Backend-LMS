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
        Schema::create('issues', function (Blueprint $table) {
            $table->uuid('issue_id')->primary();
            $table->date('check_out_date')->default(now());
            $table->date('due_date');
            $table->date('check_in_date')->nullable();
            $table->date('renewal_request_date')->nullable();
            $table->string('renewal_count', 3)->nullable();
            $table->string('member_id');
            $table->foreign('member_id')->references('member_id')->on('members')->onDelete('cascade');
            $table->string('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->string('membership_id');
            $table->foreign('membership_id')->references('membership_id')->on('memberships')->onDelete('cascade');
            $table->string('reservation_id');
            $table->foreign('reservation_id')->references('reservation_id')->on('book_reservations')->onDelete('cascade');
            $table->string('book_id')->nullable();
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
