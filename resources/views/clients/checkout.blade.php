@extends('layouts.client_layout')

@section('content')

<div class="bg-gray-50 py-2.5 border-b shadow-sm font-sans">
    <div class="container mx-auto px-4">
        <nav class="text-xs font-medium text-gray-500">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="/" class="text-gray-500 hover:text-blue-600 transition"><i class="fas fa-home mr-1"></i> Trang chủ</a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-blue-600 transition">Giỏ hàng</a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                <li class="text-blue-600 font-bold" aria-current="page">Thanh toán</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-6 font-sans">
    
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            <div class="lg:col-span-7">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center uppercase tracking-wide">
                        <i class="fas fa-id-card mr-2 text-blue-600"></i> Thông tin nhận hàng
                    </h2>
                    
                    <div class="mb-3">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase">Họ và tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required 
                               class="w-full border border-gray-300 px-3 py-2 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition placeholder-gray-400" 
                               placeholder="Nhập họ tên...">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" required 
                                   class="w-full border border-gray-300 px-3 py-2 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition placeholder-gray-400" 
                                   placeholder="09xxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase">Email (Tùy chọn)</label>
                            <input type="email" name="email" 
                                   class="w-full border border-gray-300 px-3 py-2 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition placeholder-gray-400" 
                                   placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase">Địa chỉ <span class="text-red-500">*</span></label>
                        <input type="text" name="address" required 
                               class="w-full border border-gray-300 px-3 py-2 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition placeholder-gray-400" 
                               placeholder="Số nhà, tên đường, xã/phường...">
                    </div>

                    <div class="mb-1">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase">Ghi chú</label>
                        <textarea name="note" rows="2" 
                                  class="w-full border border-gray-300 px-3 py-2 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition placeholder-gray-400" 
                                  placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-5 sticky top-4">
                    <h2 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center uppercase tracking-wide">
                        <i class="fas fa-shopping-cart mr-2 text-blue-600"></i> Đơn hàng
                    </h2>
                    
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                        @php $total = 0 @endphp
                        @if($cart)
                            @foreach($cart as $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <div class="flex justify-between items-start">
                                    <div class="flex items-start">
                                        <div class="relative flex-shrink-0">
                                            <img src="{{ $details['image'] }}" class="w-10 h-10 object-cover rounded border border-gray-200">
                                            <span class="absolute -top-1.5 -right-1.5 bg-gray-600 text-white text-[9px] w-4 h-4 flex items-center justify-center rounded-full shadow border border-white">{{ $details['quantity'] }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-[11px] font-bold text-gray-700 line-clamp-2 w-36">{{ $details['name'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600">{{ number_format($details['price'] * $details['quantity']) }}đ</span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-3 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-500">
                            <span>Tạm tính</span>
                            <span class="font-bold">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Vận chuyển</span>
                            <span class="text-green-600 font-bold">Miễn phí</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 mt-1 border-t border-gray-100">
                            <span class="text-sm font-bold text-gray-800">Tổng cộng</span>
                            <span class="text-xl font-bold text-red-600">{{ number_format($total) }}đ</span>
                        </div>
                    </div>

                    <div class="mt-4 mb-4 space-y-2">
                        <label class="flex items-center p-2.5 border border-blue-200 bg-blue-50 rounded cursor-pointer transition hover:bg-blue-100">
                            <input type="radio" name="payment_method" value="COD" checked class="form-radio h-3.5 w-3.5 text-blue-600">
                            <span class="ml-2 text-[11px] font-bold text-gray-700 flex items-center">
                                <i class="fas fa-money-bill-wave text-green-500 mr-1.5"></i> Thanh toán khi nhận hàng (COD)
                            </span>
                        </label>
                        <label class="flex items-center p-2.5 border border-gray-200 rounded opacity-60 cursor-not-allowed bg-gray-50">
                            <input type="radio" name="payment_method" value="BANK" disabled class="form-radio h-3.5 w-3.5 text-gray-400">
                            <span class="ml-2 text-[11px] font-medium text-gray-500 flex items-center">
                                <i class="fas fa-university text-gray-400 mr-1.5"></i> Chuyển khoản (Bảo trì)
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded shadow-md transition transform hover:-translate-y-0.5 text-xs uppercase tracking-wide flex items-center justify-center">
                        <i class="fas fa-check-circle mr-1.5"></i> HOÀN TẤT ĐẶT HÀNG
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="block text-center text-[10px] text-gray-400 hover:text-blue-600 mt-3 underline">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại giỏ
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection