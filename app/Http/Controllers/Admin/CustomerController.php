<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // 1. Danh sách khách hàng
    public function index()
    {
        // latest('updated_at'): Khách nào mới tương tác (mua/sửa) thì hiện lên đầu
        // withCount(['bookings', 'orders']): Đếm số lần sửa và số đơn mua để hiện thống kê
        $customers = Customer::withCount(['bookings', 'orders'])
                             ->latest('updated_at')
                             ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    // 2. Xem chi tiết lịch sử (Sửa chữa + Mua hàng)
    public function show($id)
    {
        // Lấy thông tin khách kèm theo 2 danh sách:
        // 1. bookings: Sắp xếp theo ngày hẹn giảm dần
        // 2. orders: Sắp xếp theo ngày đặt (created_at) giảm dần
        $customer = Customer::with([
            'bookings' => function($query) {
                $query->orderBy('booking_time', 'desc');
            },
            'orders' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    // 3. Hiển thị form sửa
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // 4. Xử lý cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Kiểm tra trùng SĐT nhưng trừ chính khách hàng này ra (. $id)
            'phone_number' => 'required|string|max:20|unique:customers,phone_number,' . $id,
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ], [
            'phone_number.unique' => 'Số điện thoại này đã được sử dụng bởi khách hàng khác.',
            'name.required' => 'Vui lòng nhập tên khách hàng.'
        ]);

        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes, // Ghi chú nội bộ về khách (ví dụ: Khách khó tính, khách VIP...)
        ]);

        return redirect()->route('admin.customers.show', $id)
                         ->with('success', 'Cập nhật thông tin khách hàng thành công!');
    }
}