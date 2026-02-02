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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Tên sản phẩm
        $table->string('sku')->nullable(); // Mã sản phẩm (VD: DH-IPC-T1B40P)
        $table->string('slug')->unique(); // URL thân thiện
        $table->unsignedBigInteger('category_id'); // Liên kết danh mục
        $table->text('short_description')->nullable(); // Mô tả ngắn (hiện ở danh sách)
        $table->longText('description')->nullable(); // Nội dung chi tiết (Editor)
        $table->decimal('price', 15, 0)->nullable(); // Giá bán
        $table->decimal('sale_price', 15, 0)->nullable(); // Giá khuyến mãi
        $table->string('image')->nullable(); // Ảnh đại diện
        $table->text('gallery')->nullable(); // Thư viện ảnh (JSON)
        $table->boolean('is_active')->default(true); // Trạng thái hiển thị
        $table->boolean('is_hot')->default(false); // Sản phẩm nổi bật
        $table->timestamps();

        // Khóa ngoại liên kết với bảng categories
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
