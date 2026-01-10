<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer; // 🔥 Đừng quên import Model Customer
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // =================================================================
        // LỚP 1: VALIDATE DỮ LIỆU (Đồng bộ với Giao diện)
        // =================================================================
        $request->validate([
            // Tên: Bắt buộc, chuỗi, tối đa 50 ký tự, KHÔNG ĐƯỢC CHỨA SỐ
            'customer_name' => 'required|string|max:50|not_regex:/[0-9]/', 
            
            // SĐT: 10 số, bắt đầu bằng 0
            'phone_number' => ['required', 'regex:/^(0)[0-9]{9}$/'], 
            
            'address' => 'required|string|max:255',
            'booking_time' => 'required|date|after:now', 
            'issue_description' => 'required|string|max:1000',
        ], [
            // Thông báo lỗi tiếng Việt
            'customer_name.max' => 'Tên quá dài (tối đa 50 kí tự).',
            'customer_name.not_regex' => 'Họ tên không được chứa chữ số.',
            'phone_number.regex' => 'Số điện thoại không hợp lệ (phải có 10 số).',
            'booking_time.after' => 'Thời gian hẹn phải là tương lai!',
        ]);

        try {
            // =================================================================
            // LỚP 2: KIỂM TRA LOGIC NGHIỆP VỤ (Business Logic)
            // =================================================================
            
            // 1. Chống Spam (Max 3 đơn pending trên cùng 1 SĐT)
            $pendingCount = Booking::where('phone_number', $request->phone_number)
                                   ->where('status', 'pending')
                                   ->count();
            
            if ($pendingCount >= 3) {
                return redirect()->back()->with('error', 'Bạn đang có quá nhiều lịch hẹn chờ xử lý.');
            }

            // 2. Chống Trùng Lịch (Cùng SĐT + Cùng Giờ)
            $isDuplicate = Booking::where('phone_number', $request->phone_number)
                                  ->where('booking_time', $request->booking_time)
                                  ->exists();

            if ($isDuplicate) {
                return redirect()->back()->with('error', 'Bạn đã đặt lịch vào khung giờ này rồi!');
            }

            // =================================================================
            // LỚP 3: XỬ LÝ KHÁCH HÀNG (CRM) & LƯU DATABASE
            // =================================================================
            
            // A. Tự động Tìm hoặc Tạo Khách hàng
            // Nếu SĐT đã tồn tại -> Cập nhật tên, địa chỉ mới nhất (để data luôn tươi mới)
            // Nếu SĐT chưa có -> Tạo khách hàng mới
            $customer = Customer::updateOrCreate(
                ['phone_number' => $request->phone_number], // Điều kiện tìm kiếm (duy nhất)
                [
                    'name'    => $request->customer_name,
                    'address' => $request->address,
                    // 'email' => $request->email, // Thêm dòng này nếu form có nhập email
                ]
            );

            // B. Tạo Lịch Đặt (Booking)
            Booking::create([
                'customer_id'       => $customer->id, // 🔥 Liên kết khóa ngoại với bảng customers
                
                // Vẫn lưu lại thông tin text để làm "Snapshot" lịch sử (tránh việc khách đổi tên/địa chỉ làm sai lệch đơn cũ)
                'customer_name'     => $request->customer_name,
                'phone_number'      => $request->phone_number,
                'address'           => $request->address,
                
                'booking_time'      => $request->booking_time,
                'issue_description' => $request->issue_description,
                'status'            => 'pending'
            ]);

            return redirect()->back()->with('success', 'Đã đặt lịch thành công! Chúng tôi sẽ liên hệ sớm.');

        } catch (\Exception $e) {
            // Ghi log lỗi hệ thống để Admin kiểm tra
            Log::error("Lỗi đặt lịch: " . $e->getMessage());
            
            // Trả về thông báo lỗi thân thiện cho người dùng
            // (Nếu đang test thì có thể nối thêm $e->getMessage() để xem lỗi)
            return redirect()->back()->with('error', 'Lỗi hệ thống, vui lòng thử lại sau.');
        }
    }
}