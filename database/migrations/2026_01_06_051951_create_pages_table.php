<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');            // Tiêu đề (VD: Về chúng tôi)
            $table->string('slug')->unique();   // Slug (VD: ve-chung-toi)
            $table->text('summary')->nullable();// Mô tả ngắn (cho SEO meta description)
            $table->longText('content');        // Nội dung chi tiết (CKEditor)
            $table->integer('position')->default(0); // Thứ tự sắp xếp (số nhỏ lên trước)
            $table->boolean('is_active')->default(true); // Trạng thái hiển thị
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
};