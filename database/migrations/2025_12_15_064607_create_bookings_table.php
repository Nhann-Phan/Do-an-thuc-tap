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
        Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('phone_number');
        $table->dateTime('booking_time'); // Thời gian khách hẹn
        $table->text('issue_description')->nullable(); // Mô tả vấn đề/nhu cầu
        $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending'); // Trạng thái lịch hẹn
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
