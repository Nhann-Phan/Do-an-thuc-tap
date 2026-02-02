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
    Schema::create('news', function (Blueprint $table) {
        $table->id();
        $table->string('title');                // Tiêu đề tin
        $table->string('slug')->unique();       // Đường dẫn (SEO)
        $table->text('summary')->nullable();    // Tóm tắt ngắn
        $table->longText('content')->nullable();// Nội dung chi tiết (cho phép null nếu chỉ có ảnh)
        $table->string('image')->nullable();    // Đường dẫn ảnh
        $table->boolean('is_active')->default(true); // Trạng thái hiển thị
        $table->integer('view_count')->default(0);   // Đếm lượt xem (nếu cần sau này)
        $table->timestamps();   // Tự động tạo created_at và updated_at
    });
}

    public function down()
    {
        Schema::dropIfExists('news');
    }
};
