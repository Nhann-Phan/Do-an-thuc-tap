@extends('layouts.admin_layout')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Cột trái: Thông tin khách hàng --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Hồ sơ khách hàng</h3>
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-edit"></i> Sửa
            </a>
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 uppercase">Họ tên</label>
                    <p class="font-medium text-lg">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase">Số điện thoại</label>
                    <p class="font-bold text-blue-600 text-lg">{{ $customer->phone_number }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase">Địa chỉ hiện tại</label>
                    <p class="text-gray-700">{{ $customer->address }}</p>
                </div>
                
                {{-- Thống kê tổng quan (Cập nhật mới) --}}
                <div class="grid grid-cols-2 gap-4 pt-2 border-t mt-2">
                    <div class="bg-blue-50 p-3 rounded border border-blue-100 text-center">
                        <label class="text-xs text-blue-500 uppercase block font-semibold">Sửa chữa</label>
                        <p class="font-bold text-blue-700 text-xl">{{ $customer->bookings->count() }}</p>
                        <span class="text-xs text-blue-400">lần đặt</span>
                    </div>
                    <div class="bg-green-50 p-3 rounded border border-green-100 text-center">
                        <label class="text-xs text-green-500 uppercase block font-semibold">Mua hàng</label>
                        {{-- Cần quan hệ orders trong Model Customer --}}
                        <p class="font-bold text-green-700 text-xl">{{ $customer->orders ? $customer->orders->count() : 0 }}</p>
                        <span class="text-xs text-green-400">đơn hàng</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    {{-- Cột phải: Tabs Lịch sử --}}
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 min-h-[600px]">
            
            {{-- 1. THANH ĐIỀU HƯỚNG TABS (MỚI THÊM) --}}
            <div class="border-b border-gray-200 mb-6">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="historyTabs">
                    <li class="mr-2">
                        <button class="inline-flex items-center p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition-all border-blue-600 text-blue-600 group" 
                                id="tab-booking" onclick="switchTab('booking')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Lịch sử Sửa chữa
                        </button>
                    </li>
                    <li class="mr-2">
                        <button class="inline-flex items-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition-all text-gray-500 group" 
                                id="tab-order" onclick="switchTab('order')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Lịch sử Mua hàng
                        </button>
                    </li>
                </ul>
            </div>

            {{-- 2. NỘI DUNG: SỬA CHỮA (Mặc định hiện) --}}
            <div id="content-booking" class="block transition-opacity duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-gray-800 font-bold">Timeline đặt lịch</h4>
                </div>

                @if($customer->bookings->count() > 0)
                    <div class="relative border-l-2 border-gray-200 ml-3 space-y-8">
                        @foreach($customer->bookings as $booking)
                            <div class="relative pl-8">
                                {{-- Dấu chấm tròn --}}
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-white 
                                    {{ $booking->status == 'pending' ? 'bg-yellow-400' : ($booking->status == 'completed' ? 'bg-green-500' : 'bg-gray-400') }}">
                                </div>

                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-1">
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('d/m/Y - H:i') }}
                                    </span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded 
                                        {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 mt-2 hover:bg-gray-100 transition-colors">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-bold text-gray-700">Mô tả lỗi:</span> {{ $booking->issue_description }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        Địa chỉ: {{ $booking->address }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Chưa có lịch sử đặt hẹn nào.
                    </div>
                @endif
            </div>

            {{-- 3. NỘI DUNG: MUA HÀNG (Mặc định ẩn) --}}
            <div id="content-order" class="hidden transition-opacity duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-gray-800 font-bold">Danh sách đơn hàng</h4>
                </div>

                @if($customer->orders && $customer->orders->count() > 0)
                    <div class="relative overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Mã đơn</th>
                                    <th scope="col" class="px-6 py-3">Ngày đặt</th>
                                    <th scope="col" class="px-6 py-3">Tổng tiền</th>
                                    <th scope="col" class="px-6 py-3">Trạng thái</th>
                                    <th scope="col" class="px-6 py-3 text-right">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->orders as $order)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 font-bold text-blue-600">{{ number_format($order->total_money) }} đ</td>
                                    <td class="px-6 py-4">
                                        @if($order->status == 'pending')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-200">Chờ xử lý</span>
                                        @elseif($order->status == 'completed')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Hoàn thành</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{-- Link mẫu, bạn hãy thay bằng route xem chi tiết đơn hàng của bạn --}}
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 font-medium hover:underline">Xem</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-10 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Khách chưa mua đơn hàng nào.
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ CHUYỂN TAB --}}
<script>
    function switchTab(tabName) {
        // Lấy các elements
        const tabBooking = document.getElementById('tab-booking');
        const tabOrder = document.getElementById('tab-order');
        const contentBooking = document.getElementById('content-booking');
        const contentOrder = document.getElementById('content-order');

        // Style class cho trạng thái Active và Inactive (Tailwind)
        const activeClasses = ['border-blue-600', 'text-blue-600'];
        const inactiveClasses = ['border-transparent', 'text-gray-500'];

        if (tabName === 'booking') {
            // Hiện Booking, Ẩn Order
            contentBooking.classList.remove('hidden');
            contentOrder.classList.add('hidden');

            // Cập nhật Tab Booking -> Active
            tabBooking.classList.add(...activeClasses);
            tabBooking.classList.remove(...inactiveClasses);
            
            // Cập nhật Tab Order -> Inactive
            tabOrder.classList.remove(...activeClasses);
            tabOrder.classList.add(...inactiveClasses);
            
        } else if (tabName === 'order') {
            // Hiện Order, Ẩn Booking
            contentOrder.classList.remove('hidden');
            contentBooking.classList.add('hidden');

            // Cập nhật Tab Order -> Active
            tabOrder.classList.add(...activeClasses);
            tabOrder.classList.remove(...inactiveClasses);
            
            // Cập nhật Tab Booking -> Inactive
            tabBooking.classList.remove(...activeClasses);
            tabBooking.classList.add(...inactiveClasses);
        }
    }
</script>
@endsection