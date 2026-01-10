@extends('layouts.admin_layout')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h3 class="text-2xl font-bold text-gray-800">Cập nhật thông tin khách</h3>
    
    <a href="{{ route('admin.customers.show', $customer->id) }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại chi tiết
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="bg-blue-50 px-6 py-3 border-b border-blue-100 text-blue-800 font-bold flex items-center">
        <i class="fas fa-user-edit mr-2"></i> Chỉnh sửa: {{ $customer->name }}
    </div>

    <div class="p-6">
        
        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- CỘT TRÁI --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Họ và Tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800" required>
                        <p class="text-xs text-gray-500 mt-1 italic">SĐT dùng để định danh khách hàng khi đặt lịch/mua hàng.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Địa chỉ</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ghi chú nội bộ</label>
                        <textarea name="notes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-yellow-50 border-yellow-200 placeholder-yellow-400" placeholder="Ghi chú về khách hàng này (VD: Khách khó tính, thích giao hàng buổi tối...)">{{ old('notes', $customer->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.customers.show', $customer->id) }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition">Hủy bỏ</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition transform active:scale-95">
                    <i class="fas fa-save mr-2"></i> Lưu thay đổi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection