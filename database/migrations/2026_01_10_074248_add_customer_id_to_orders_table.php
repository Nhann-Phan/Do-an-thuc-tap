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
        // ĐÂY LÀ ĐOẠN CODE CỦA BẠN
        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột customer_id sau cột id
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            
            // Tạo khóa ngoại tới bảng customers
            // Lưu ý: Đảm bảo bảng 'customers' đã tồn tại trước khi chạy lệnh này
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Đoạn này dùng để xóa cột nếu bạn chạy lệnh rollback (quay lại)
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']); // Xóa khóa ngoại trước
            $table->dropColumn('customer_id');    // Xóa cột sau
        });
    }
};