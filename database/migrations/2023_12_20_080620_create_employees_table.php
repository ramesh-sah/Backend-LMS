<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('employee_id')->primary();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->default('');
            $table->string('last_name', 100);
            $table->date('dob');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address', 225)->default('');
            $table->enum('role', ['employee', 'coordinator'])->default('employee');
            $table->enum('gender', ['male', 'female', 'others'])->default('others');
            $table->string('contact_number', 10)->default(0000000000);
            $table->string('enrollment_year', 4);
            $table->date('account_creation_date')->default(now());
            $table->enum('account_status', ['active', 'inactive', 'closed'])->default('active');
            $table->string('image_link', 2048)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
