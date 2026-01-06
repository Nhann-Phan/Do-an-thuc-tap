<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            // Liên kết với bảng pages, xóa page thì xóa luôn section
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade'); 
            
            $table->string('title')->nullable(); // Tiêu đề của khối (VD: Tầm nhìn)
            $table->string('type'); // Loại khối: 'text_image', 'stats', 'features', 'cta'
            $table->json('data')->nullable(); // Lưu trữ dữ liệu linh hoạt (ảnh, nội dung...)
            $table->integer('position')->default(0); // Thứ tự hiển thị
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_sections');
    }
};