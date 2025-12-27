<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class AdminController extends Controller
{
    public function index()
    {
        // Lấy tất cả dữ liệu đặt lịch từ database - Sắp xếp theo thời gian tạo mới nhất
        $bookings = Booking::orderBy('created_at', 'desc')->get();
        // Tính toán số liệu thống kê
        $total_bookings = Booking::count();
        //Đếm số đơn đơn chờ xác nhận
        $pending_count = Booking::where('status', 'pending')->count();
        //Đếm số đơn đã hoàn thành
        $completed_count = Booking::where('status', 'completed')->count();
        //Đếm số đơn đã hủy
        $cancelled_count = Booking::where('status', 'cancelled')->count();
        //Trả về view kèm số liệu 
        return view('admin.dashboard', compact('bookings', 'total_bookings', 'pending_count', 'completed_count', 'cancelled_count'));
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