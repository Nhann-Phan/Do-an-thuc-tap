<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Nhận từ khóa tìm kiếm
        $search = $request->input('search');

        // 1. Tối ưu danh sách: Bắt buộc dùng paginate(10)
        $bookings = Booking::when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%") 
                    ->orWhere('phone_number', 'LIKE', "%{$search}%");
            })
            ->latest() // Sắp xếp theo thời gian tạo mới nhất
            ->paginate(10); 
            
        // Giữ nguyên tham số search khi chuyển trang
        $bookings->appends(['search' => $search]);

        // 2. Tính toán số liệu thống kê
        $total_bookings = Booking::count();
        $pending_count = Booking::where('status', 'pending')->count();
        $completed_count = Booking::where('status', 'completed')->count();
        $cancelled_count = Booking::where('status', 'cancelled')->count();

        // 3. Trả về view
        return view('admin.dashboard.dashboard', compact(
            'bookings', 
            'total_bookings', 
            'pending_count', 
            'completed_count', 
            'cancelled_count',
            'search'
        ));
    }

    //Hàm sử lý trạng thái đơn hàng
    public function updateStatus($id, $status){
        //Tìm đơn hàng theo ID
        $booking = Booking::find($id);
        // Cập nhật trạng thái
        if($booking){
            $booking->status = $status; // Gán trạng thái mới (completed, canceled)
            $booking->save(); // Lưu thay đổi vào database
            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy đơn hàng!');
    }
}