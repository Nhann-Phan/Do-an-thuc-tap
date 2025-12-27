<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Thư viện xác thực của Laravel

class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLogin() {
        return view('auth.login');
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request) {
        // Lấy email và pass từ form
        $credentials = $request->only('email', 'password');

        // Kiểm tra xem có khớp trong Database không
        if (Auth::attempt($credentials)) {
            // Nếu đúng -> Vào trang admin
            return redirect()->intended('admin');
        }

        // Nếu sai -> Quay lại form báo lỗi
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng!']);
    }

    // 3. Đăng xuất
    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}