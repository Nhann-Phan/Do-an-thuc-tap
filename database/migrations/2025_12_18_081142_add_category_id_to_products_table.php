<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    // Thêm cột category_id vào bảng products
    Schema::table('products', function (Blueprint $table) {
        // 👇 Chỉ thêm cột nếu nó CHƯA tồn tại
        if (!Schema::hasColumn('products', 'category_id')) {
            $table->unsignedBigInteger('category_id')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
