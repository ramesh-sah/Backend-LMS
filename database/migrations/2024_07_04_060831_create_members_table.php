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
        Schema::create('members', function (Blueprint $table) {
            $table->uuid('member_id')->primary();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->default('')->nullable();
            $table->string('last_name', 100);
            $table->string('roll_number', 100)->default('')->nullable()->unique();
            $table->date('dob');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address', 225)->default('');
            $table->enum('gender', ['male', 'female', 'others'])->default('others');
            $table->string('contact_number', 10)->default(0000000000);
            $table->string('enrollment_year', 4);
            $table->enum('role', ['staff', 'student'])->default('student');
            $table->date('account_creation_date')->default(now());
            $table->enum('account_status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('image_link', 2048)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
