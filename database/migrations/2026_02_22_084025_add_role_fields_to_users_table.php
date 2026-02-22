<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm các cột mới vào sau cột password
            $table->tinyInteger('role')->default(2)->after('password')
                  ->comment('0: Admin, 1: Nhân viên, 2: Khách hàng');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('address')->nullable()->after('phone');
            $table->boolean('is_active')->default(1)->after('address')
                  ->comment('1: Hoạt động, 0: Bị khóa');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa cột nếu lỡ rollback
            $table->dropColumn(['role', 'phone', 'address', 'is_active']);
        });
    }
};