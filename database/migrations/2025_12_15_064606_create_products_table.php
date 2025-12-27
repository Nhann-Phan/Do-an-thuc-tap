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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // 1. Thông tin cơ bản
            $table->string('name'); // Tên sản phẩm
            $table->string('slug')->nullable(); // Đường dẫn tĩnh (SEO) - VD: camera-wifi-imou
            $table->string('sku')->nullable();  // Mã sản phẩm - VD: DH-IPC-T1B40P
            
            // 2. Giá và Kho
            $table->decimal('price', 15, 0)->nullable(); // Giá bán (VND thường không cần số thập phân)
            $table->decimal('sale_price', 15, 0)->nullable(); // Giá khuyến mãi
            $table->integer('quantity')->default(0); // Số lượng tồn kho
            
            // 3. Nội dung mô tả
            $table->text('short_description')->nullable(); // Mô tả ngắn (Hiện ở trang danh sách)
            $table->longText('description')->nullable();   // Mô tả chi tiết (Dùng Editor HTML)
            
            // 4. Hình ảnh
            $table->string('image')->nullable(); // Ảnh đại diện chính
            $table->text('gallery')->nullable(); // Thư viện ảnh phụ (Lưu dạng JSON)

            // 5. Trạng thái & Phân loại
            $table->boolean('is_active')->default(true); // Hiển thị / Ẩn
            $table->boolean('is_hot')->default(false);   // Sản phẩm nổi bật (HOT)
            
            // 6. Khóa ngoại liên kết danh mục
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};