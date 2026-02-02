<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_variants')) {
            
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                
                // 1. Tạo cột product_id
                $table->unsignedBigInteger('product_id');
                
                $table->string('name');
                $table->decimal('price', 15, 0)->nullable();
                $table->timestamps();

                // 2. Thiết lập khóa ngoại (Liên kết với bảng products)
                $table->foreign('product_id')
                      ->references('id')
                      ->on('products')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};