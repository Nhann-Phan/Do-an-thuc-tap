<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50|not_regex:/[0-9]/', 
            'phone_number' => ['required', 'regex:/^(0)[0-9]{9}$/'], 
            'address' => 'required|string|max:255',
            'booking_time' => 'required|date|after:now', 
            'issue_description' => 'required|string|max:1000',
        ], [
            'customer_name.max' => 'Tên quá dài (tối đa 50 kí tự).',
            'customer_name.not_regex' => 'Họ tên không được chứa chữ số.',
            'phone_number.regex' => 'Số điện thoại không hợp lệ (phải có 10 số).',
            'booking_time.after' => 'Thời gian hẹn phải là tương lai!',
        ]);

        try {
            // 1. Chống Spam (Dựa theo ID Khách hàng hoặc SĐT)
            $pendingCount = Booking::where('customer_id', Auth::id())
                                   ->where('status', 'pending')
                                   ->count();
            
            if ($pendingCount >= 3) {
                return redirect()->back()->with('error', 'Bạn đang có quá nhiều lịch hẹn chờ xử lý.');
            }

            // 2. Chống Trùng Lịch
            $isDuplicate = Booking::where('customer_id', Auth::id())
                                  ->where('booking_time', $request->booking_time)
                                  ->exists();

            if ($isDuplicate) {
                return redirect()->back()->with('error', 'Bạn đã đặt lịch vào khung giờ này rồi!');
            }

            // 3. TẠO LỊCH ĐẶT (Lưu trực tiếp ID Khách hàng đang đăng nhập)
            Booking::create([
                'customer_id'       => Auth::id(),
                'customer_name'     => $request->customer_name,
                'phone_number'      => $request->phone_number,
                'address'           => $request->address,
                'booking_time'      => $request->booking_time,
                'issue_description' => $request->issue_description,
                'status'            => 'pending'
            ]);

            return redirect()->back()->with('success', 'Đã đặt lịch thành công! Chúng tôi sẽ liên hệ sớm.');

        } catch (\Exception $e) {
            Log::error("Lỗi đặt lịch: " . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi hệ thống, vui lòng thử lại sau.');
        }
    }
}