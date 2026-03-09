<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Booking;

class ProfileController extends Controller
{
    // 1. Hiển thị trang thông tin tài khoản
    public function index()
    {
        $user = Auth::user();
        return view('clients.profile.index', compact('user'));
    }

    // 2. Cập nhật thông tin tài khoản
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
        ]);

        User::where('id', $user->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    // 3. Xử lý đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu cũ có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác!');
        }

        // Cập nhật mật khẩu mới
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // 4. Hiển thị Lịch sử mua hàng
    public function orders()
    {
        // Lấy danh sách đơn hàng của user đang đăng nhập
        $orders = Order::where('customer_id', Auth::id())->latest()->paginate(10); 
        
        return view('clients.profile.orders', compact('orders'));
    }

    // 5. Hiển thị Lịch sử đặt lịch sửa chữa
    public function bookings()
    {
        // Theo như cấu trúc bảng bookings của bạn, cột liên kết là 'customer_id'
        $bookings = Booking::where('customer_id', Auth::id())->latest()->paginate(10);
        
        return view('clients.profile.bookings', compact('bookings'));
    }
}