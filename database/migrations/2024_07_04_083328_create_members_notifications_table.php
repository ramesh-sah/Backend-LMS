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
        Schema::create('members_notifications', function (Blueprint $table) {
            $table->uuid('member_notification_id')->primary();
            $table->string('member_id');
            $table->foreign('member_id')->references('member_id')->on('members')->onDelete('cascade');
            $table->string('notification_id');
            $table->foreign('notification_id')->references('notification_id')->on('notifications')->onDelete('cascade');
            $table->boolean('isRead')->default(false);
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members_notifications');
    }
};
