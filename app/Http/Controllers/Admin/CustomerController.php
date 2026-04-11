<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // 1. Danh sách khách hàng
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ form
        $search = $request->input('search');

        $customers = User::where('role', 2)
            ->withCount(['orders', 'bookings'])
            // Nếu có từ khóa tìm kiếm thì mới chạy khối lệnh này
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })
            ->latest('updated_at')
            ->paginate(10);

        // Quan trọng: Giữ lại từ khóa tìm kiếm trên URL khi bấm sang trang 2, trang 3
        $customers->appends(['search' => $search]);

        // Trả thêm biến $search ra view để hiển thị lại chữ đã gõ vào ô input
        return view('admin.customers.index', compact('customers', 'search'));
    }

    // 2. Xem chi tiết lịch sử
    public function show($id)
    {
        $customer = User::with([
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
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // 4. Xử lý cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers,phone_number,' . $id,
            'email' => 'nullable|email|max:255,unique:customers,email,' . $id,
            'address' => 'nullable|string|max:255',
        ], [
            'phone_number.unique' => 'Số điện thoại này đã được sử dụng bởi khách hàng khác.',
            'email.unique' => 'Email này đã được sử dụng bởi khách hàng khác.',
            'name.required' => 'Vui lòng nhập tên khách hàng.'
        ]);

        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.customers.show', $id)
                         ->with('success', 'Cập nhật thông tin khách hàng thành công!');
    }
}