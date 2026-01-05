@extends('layouts.client_layout')

@section('content')

{{-- BREADCRUMB --}}
<nav class="bg-gray-50 border-b border-gray-200 py-4 mb-8">
    <div class="container mx-auto px-4">
        <ol class="flex text-sm text-gray-500 items-center gap-2 overflow-hidden whitespace-nowrap font-medium">
            <li>
                <a href="/" class="hover:text-blue-600 transition flex items-center">
                    <i class="fas fa-home mr-1.5"></i> Trang chủ
                </a>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            <li>
                <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition">
                    Giỏ hàng
                </a>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            <li class="text-gray-900 font-bold">Thanh toán</li>
        </ol>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div class="container mx-auto px-4 pb-16 mb-15">

    {{-- Tiêu đề --}}
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- CỘT TRÁI: THÔNG TIN GIAO HÀNG --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="font-bold text-gray-800 uppercase text-sm flex items-center tracking-wide">
                            <i class="fas fa-id-card mr-2 text-blue-600"></i> Thông tin nhận hàng
                        </h2>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        {{-- Các trường input giữ nguyên như cũ vì đã ổn --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required 
                                   class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400" 
                                   placeholder="Nhập họ tên người nhận...">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" required 
                                       class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400" 
                                       placeholder="09xxxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Email (Tùy chọn)</label>
                                <input type="email" name="email" 
                                       class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400" 
                                       placeholder="email@example.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Địa chỉ nhận hàng <span class="text-red-500">*</span></label>
                            <input type="text" name="address" required 
                                   class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400" 
                                   placeholder="Số nhà, tên đường, xã/phường/quận/huyện...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Ghi chú đơn hàng</label>
                            <textarea name="note" rows="3" 
                                      class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400" 
                                      placeholder="Ví dụ: Giao hàng giờ hành chính..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: ĐƠN HÀNG (ĐÃ CHỈNH SỬA) --}}
            <div class="lg:col-span-5">
                <div class="bg-white rounded-xl shadow-lg border border-blue-100 p-6 sticky top-24">
                    
                    <h2 class="font-bold text-gray-800 uppercase text-sm mb-5 flex items-center tracking-wide border-b border-gray-100 pb-3">
                        <i class="fas fa-shopping-cart mr-2 text-blue-600"></i> Đơn hàng của bạn
                    </h2>
                    
                    {{-- DANH SÁCH SẢN PHẨM --}}
                    <div class="space-y-5 mb-6 max-h-80 overflow-y-auto pr-2 checkout-scrollbar">
                        @php $total = 0; @endphp
                        @if($cart)
                            @foreach($cart as $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                
                                {{-- Item Row --}}
                                <div class="flex gap-4 items-start pb-4 border-b border-dashed border-gray-100 last:border-0 last:pb-0">
                                    {{-- 1. Ảnh sản phẩm --}}
                                    <div class="relative w-16 h-16 flex-shrink-0 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden">
                                        <img src="{{ $details['image'] }}" class="max-w-full max-h-full object-contain p-1 mix-blend-multiply">
                                    </div>
                                    
                                    {{-- 2. Thông tin (Tên + Phân loại) --}}
                                    <div class="flex-grow">
                                        {{-- Tên sản phẩm --}}
                                        <h3 class="text-sm font-bold text-gray-700 leading-snug line-clamp-2 mb-1">
                                            {{ $details['name'] }}
                                        </h3>
                                        
                                        {{-- Phân loại (Nếu có) --}}
                                        @if(isset($details['variant_name']))
                                            <div class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded border border-gray-200">
                                                Phân loại: <span class="font-medium text-gray-700">{{ $details['variant_name'] }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 3. Giá tiền --}}
                                    <div class="flex-shrink-0 text-right">
                                        <span class="block text-sm font-bold text-gray-800">
                                            {{ number_format($details['price'] * $details['quantity']) }}đ
                                        </span>
                                        {{-- Nếu số lượng > 1 thì hiện đơn giá nhỏ bên dưới --}}
                                        @if($details['quantity'] > 1)
                                            <span class="block text-[10px] text-gray-400">
                                                {{ number_format($details['price']) }} x {{ $details['quantity'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    {{-- TỔNG KẾT --}}
                    <div class="border-t border-dashed border-gray-300 pt-4 space-y-2.5">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tạm tính</span>
                            <span class="font-medium">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Phí vận chuyển</span>
                            <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-0.5 rounded uppercase">Miễn phí</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-2">
                            <span class="text-base font-bold text-gray-800">Tổng thanh toán</span>
                            <span class="text-2xl font-extrabold text-red-600">{{ number_format($total) }}đ</span>
                        </div>
                    </div>

                    {{-- PHƯƠNG THỨC THANH TOÁN --}}
                    <div class="mt-6 space-y-3">
                        <label class="flex items-center p-3 border border-blue-200 bg-blue-50/50 rounded-lg cursor-pointer transition hover:bg-blue-50 ring-1 ring-transparent hover:ring-blue-200 group">
                            <input type="radio" name="payment_method" value="COD" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm font-bold text-gray-700 flex items-center group-hover:text-blue-700">
                                <i class="fas fa-money-bill-wave text-green-500 mr-2 text-lg"></i> Thanh toán khi nhận hàng (COD)
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg bg-gray-50 opacity-60 cursor-not-allowed">
                            <input type="radio" name="payment_method" value="BANK" disabled class="w-4 h-4 text-gray-400 border-gray-300">
                            <span class="ml-3 text-sm font-medium text-gray-500 flex items-center">
                                <i class="fas fa-university text-gray-400 mr-2 text-lg"></i> Chuyển khoản (Đang bảo trì)
                            </span>
                        </label>
                    </div>

                    {{-- BUTTONS --}}
                    <button type="submit" class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-200 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wide flex items-center justify-center group">
                        <i class="fas fa-check-circle mr-2 group-hover:scale-110 transition-transform"></i> HOÀN TẤT ĐẶT HÀNG
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="block text-center text-xs font-medium text-gray-400 hover:text-blue-600 mt-4 transition hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>

        </div>
    </form>
</div>

{{-- CSS Custom Scrollbar --}}
<style>
    .checkout-scrollbar::-webkit-scrollbar { width: 4px; }
    .checkout-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .checkout-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .checkout-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

@endsection