<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index()
    {
        // Lấy đơn mới nhất lên đầu, phân trang 10 đơn/trang
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
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