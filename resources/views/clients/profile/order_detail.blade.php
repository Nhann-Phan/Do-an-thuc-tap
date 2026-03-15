@extends('layouts.client_layout')
@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="container mx-auto px-4">
        
        {{-- Nút Quay Lại --}}
        <div class="mb-6">
            <a href="{{ route('client.profile.orders') }}" class="text-blue-600 hover:text-blue-800 transition font-medium text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách đơn hàng
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- CỘT TRÁI: THÔNG TIN ĐƠN & KHÁCH HÀNG --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Box Trạng thái --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Thông tin đơn hàng</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Mã đơn:</span>
                            <span class="font-bold text-blue-600">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ngày đặt:</span>
                            <span class="font-medium text-gray-800">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-500">Trạng thái:</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-500">Thanh toán:</span>
                            <span class="font-medium text-gray-800 uppercase">{{ $order->payment_method }}</span>
                        </div>
                    </div>
                </div>

                {{-- Box Thông tin giao hàng --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Địa chỉ giao hàng</h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong class="text-gray-800">{{ $order->name }}</strong></p>
                        <p><i class="fas fa-phone-alt text-gray-400 w-5"></i> {{ $order->phone }}</p>
                        <p><i class="fas fa-envelope text-gray-400 w-5"></i> {{ $order->email ?? 'Không có email' }}</p>
                        <p class="leading-relaxed mt-2"><i class="fas fa-map-marker-alt text-gray-400 w-5"></i> {{ $order->address }}</p>
                    </div>
                    @if($order->note)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-100 rounded-lg text-sm text-gray-700">
                            <strong><i class="fas fa-comment-dots text-yellow-500 mr-1"></i> Ghi chú:</strong> {{ $order->note }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- CỘT PHẢI: DANH SÁCH SẢN PHẨM --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-3">Sản phẩm đã đặt</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                    <th class="p-4 font-semibold">Tên sản phẩm</th>
                                    <th class="p-4 font-semibold text-center">Đơn giá</th>
                                    <th class="p-4 font-semibold text-center">SL</th>
                                    <th class="p-4 font-semibold text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($orderItems as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $item->product_name }}
                                    </td>
                                    <td class="p-4 text-center text-gray-600">
                                        {{ number_format($item->price, 0, ',', '.') }} ₫
                                    </td>
                                    <td class="p-4 text-center font-bold text-gray-700">
                                        x{{ $item->quantity }}
                                    </td>
                                    <td class="p-4 text-right font-bold text-gray-800">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tổng kết tiền --}}
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <div class="flex justify-between items-center text-lg">
                            <span class="font-bold text-gray-800">TỔNG CỘNG:</span>
                            <span class="font-bold text-red-600 text-xl">{{ number_format($order->total_money, 0, ',', '.') }} ₫</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection