<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Booking;
use App\Models\OrderItem;
use App\Models\Customer;

class ProfileController extends Controller
{
    // 1. Hiển thị trang thông tin tài khoản
    public function index()
    {
        $user = Auth::user();
        // Lấy thêm thông tin customer nếu có để hiển thị địa chỉ ra view (nếu cần)
        $customer = Customer::where('user_id', $user->id)->first();
        return view('clients.profile.index', compact('user', 'customer'));
    }

// 2. Cập nhật thông tin tài khoản (Dành riêng cho Client)
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        // 1. Gom số điện thoại lại
        $phoneNumber = $request->phone ?? $request->phone_number;

        // Bắt lỗi rỗng ngay từ vòng gửi xe
        if (empty($phoneNumber)) {
            return back()->with('error', 'Vui lòng nhập số điện thoại, không được bỏ trống!');
        }

        // 2. Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        // 3. CẬP NHẬT BẢNG USERS
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'phone' => $phoneNumber, // Truyền biến đã gom ở trên vào
        ]);

        // 4. CẬP NHẬT BẢNG CUSTOMERS
        Customer::updateOrCreate(
            ['user_id' => $user->id], 
            [
                'name' => $request->name,
                'email' => $user->email, 
                'phone_number' => $phoneNumber, // Ép số điện thoại vào đúng cột phone_number
                'address' => $request->address,
            ]
        );

        return back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
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

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác!');
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // 4. Hiển thị Lịch sử mua hàng
    public function orders()
    {
        // BƯỚC 1: Tìm ID của Customer thuộc về User này
        $customer = Customer::where('user_id', Auth::id())->first();
        $customerId = $customer ? $customer->id : null;

        // BƯỚC 2: Lấy đơn hàng dựa trên customer_id
        $orders = Order::where('customer_id', $customerId)->latest()->paginate(10); 
        
        return view('clients.profile.orders', compact('orders'));
    }

    // 5. Hiển thị Lịch sử đặt lịch sửa chữa
    public function bookings()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $customerId = $customer ? $customer->id : null;

        $bookings = Booking::where('customer_id', $customerId)->latest()->paginate(10);
        
        return view('clients.profile.bookings', compact('bookings'));
    }

    // 6. Xem chi tiết đơn hàng
    public function showOrder($id){
        $customer = Customer::where('user_id', Auth::id())->first();
        $customerId = $customer ? $customer->id : null;

        // Bắt buộc đơn hàng phải thuộc về customer_id này
        $order = Order::where('id', $id)->where('customer_id', $customerId)->with('items')->firstOrFail();
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        
        return view('clients.profile.order_detail', compact('order','orderItems'));
    }
}