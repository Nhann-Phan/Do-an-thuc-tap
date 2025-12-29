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
    // 1. Tạo bảng orders (đơn hàng)
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('name');          // Tên người nhận
        $table->string('email')->nullable();
        $table->string('phone');         // SĐT
        $table->string('address');       // Địa chỉ
        $table->text('note')->nullable();// Ghi chú
        $table->integer('total_money');  // Tổng tiền
        $table->string('status')->default('pending'); // Trạng thái đơn
        $table->string('payment_method')->default('COD'); // Hình thức thanh toán
        $table->timestamps();
    });

    // 2. Tạo bảng order_items (chi tiết sản phẩm mua)
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('product_id');
        $table->string('product_name');  // Lưu tên SP lúc mua
        $table->integer('quantity');     // Số lượng
        $table->integer('price');        // Giá lúc mua
        $table->timestamps();

        // Khóa ngoại: Xóa đơn hàng là xóa luôn chi tiết
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
