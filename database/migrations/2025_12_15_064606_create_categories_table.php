<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên danh mục
            $table->string('slug')->unique(); // Đường dẫn tĩnh
            $table->string('icon')->nullable(); // Icon (nếu có)
            
            // --- CỘT QUAN TRỌNG ĐANG THIẾU ---
            $table->unsignedBigInteger('parent_id')->nullable(); 
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            // ---------------------------------

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};