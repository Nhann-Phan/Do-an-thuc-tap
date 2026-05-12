@extends('layouts.client_layout')
@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            {{-- SIDEBAR MENU CÁ NHÂN --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-24">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                            <p class="text-xs text-gray-500">Khách hàng thành viên</p>
                        </div>
                    </div>
                    <nav class="space-y-2">
                        <a href="{{ route('client.profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('client.profile.index') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <i class="fas fa-user-edit w-5 text-center"></i> Thông tin tài khoản
                        </a>
                        <a href="{{ route('client.profile.orders') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('client.profile.orders') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <i class="fas fa-shopping-bag w-5 text-center"></i> Đơn hàng của tôi
                        </a>
                        <a href="{{ route('client.profile.bookings') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('client.profile.bookings') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <i class="fas fa-calendar-check w-5 text-center"></i> Lịch sửa chữa
                        </a>
                        <a href="{{ route('logout') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 mt-4 border-t border-gray-50 pt-4">
                            <i class="fas fa-sign-out-alt w-5 text-center"></i> Đăng xuất
                        </a>
                    </nav>
                </div>
            </div>

            {{-- NỘI DUNG CHÍNH: DANH SÁCH ĐƠN HÀNG --}}
            <div class="md:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-3">Đơn hàng của tôi</h2>
                    
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                        <th class="p-4 font-semibold">Mã ĐH</th>
                                        <th class="p-4 font-semibold">Ngày đặt</th>
                                        <th class="p-4 font-semibold">Tổng tiền</th>
                                        <th class="p-4 font-semibold">Trạng thái</th>
                                        <th class="p-4 font-semibold text-center">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($orders as $order)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="p-4 font-bold text-blue-600">#{{ $order->id }}</td>
                                        <td class="p-4 text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-4 font-bold text-red-600">{{ number_format($order->total_money, 0, ',', '.') }} ₫</td>
                                        <td class="p-4">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <a href="{{ route('client.profile.orders.detail', $order->id) }}" class="text-blue-500 hover:text-blue-700 underline text-xs font-bold">Xem chi tiết</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-box-open text-6xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500 font-medium">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('product.index') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">Mua sắm ngay</a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection