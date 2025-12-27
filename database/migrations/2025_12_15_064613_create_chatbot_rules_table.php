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
        Schema::create('chatbot_rules', function (Blueprint $table) {
        $table->id();
        $table->string('keyword'); // Từ khóa người dùng nhập (ví dụ: "giá", "bảo hành")
        $table->text('response'); // Câu trả lời của bot
        $table->boolean('is_active')->default(true); // Bật/tắt luật này
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_rules');
    }
};
