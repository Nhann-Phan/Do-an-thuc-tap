<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone_number')->unique(); // SĐT là duy nhất để định danh
        $table->string('address')->nullable();
        $table->string('email')->nullable();
        $table->text('notes')->nullable(); // Ghi chú về khách (VD: Khách khó tính, hay dễ tính ...)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
