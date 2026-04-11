<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ form
        $search = $request->input('search');

        // Lấy đơn mới nhất lên đầu, phân trang 10 đơn/trang
        $orders = Order::with('user') // Load sẵn thông tin user để chống lỗi N+1 query làm chậm web
            ->when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%") // Tìm theo mã đơn (ID)
                    ->orWhereHas('user', function($q) use ($search) { // Tìm qua bảng users (Tên, SĐT)
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc') // Giữ nguyên logic sắp xếp cũ của mày
            ->paginate(10);

        // Giữ lại tham số search trên URL khi bấm sang trang 2, 3...
        $orders->appends(['search' => $search]);

        return view('admin.orders.index', compact('orders', 'search'));
    }

    // 2. Xem chi tiết đơn hàng
    public function show($id)
    {
        // Lấy đơn hàng kèm theo các món trong đơn (items)
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // 3. Cập nhật trạng thái (Duyệt đơn / Hủy đơn)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
    
    // 4. Xóa đơn hàng (nếu cần)
    public function destroy($id)
    {
        Order::destroy($id);
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng!');
    }
}