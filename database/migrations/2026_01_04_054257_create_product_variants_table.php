<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kiểm tra nếu bảng đã tồn tại thì xóa trước để tránh lỗi
        if (Schema::hasTable('product_variants')) {
            Schema::dropIfExists('product_variants');
        }

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            
            // 1. Tạo cột product_id trước (kiểu số nguyên dương lớn)
            $table->unsignedBigInteger('product_id');
            
            $table->string('name'); 
            $table->decimal('price', 15, 0); 
            $table->timestamps();

            // 2. Sau đó mới thiết lập khóa ngoại thủ công (Cách này an toàn nhất)
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};