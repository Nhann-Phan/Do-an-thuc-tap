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
        Schema::create('warranties', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name'); // Tên khách
        $table->string('phone_number'); // Số điện thoại (để tra cứu)
        $table->string('product_name'); // Tên sản phẩm bảo hành
        $table->string('serial_number')->nullable(); // Số Serial/IMEI
        $table->date('purchase_date'); // Ngày mua
        $table->date('expiration_date'); // Ngày hết hạn bảo hành
        $table->text('notes')->nullable(); // Ghi chú thêm
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
