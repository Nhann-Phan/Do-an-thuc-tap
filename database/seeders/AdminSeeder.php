<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cập nhật tài khoản cũ của bạn thành ADMIN (Role = 0)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Tìm tài khoản có email này
            [
                'role' => 0, // Cấp quyền Admin cao nhất
                'is_active' => 1
                // Không ghi đè password để bạn vẫn dùng pass cũ đăng nhập được
            ]
        );

        // 2. Tạo thêm 1 tài khoản NHÂN VIÊN mới tinh để bạn test (Role = 1)
        User::updateOrCreate(
            ['email' => 'nhanvien@gmail.com'],
            [
                'name' => 'Nhân Viên Bán Hàng',
                'password' => Hash::make('12345678'), // Mật khẩu là: 12345678
                'role' => 1, // Quyền nhân viên
                'phone' => '0123456789',
                'is_active' => 1
            ]
        );
    }
}