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

            {{-- NỘI DUNG CHÍNH: LỊCH SỬ ĐẶT LỊCH --}}
            <div class="md:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-3">Lịch sử đặt lịch sửa chữa</h2>
                    
                    @if($bookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($bookings as $booking)
                                <div class="border border-gray-100 rounded-xl p-5 hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-gray-50 pb-4 mb-4">
                                        <div>
                                            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Mã lịch hẹn: #{{ $booking->id }}</span>
                                            <h3 class="font-bold text-gray-900 mt-1">Thời gian: {{ \Carbon\Carbon::parse($booking->booking_time)->format('d/m/Y - H:i') }}</h3>
                                        </div>
                                        <div>
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                                    'completed' => 'bg-green-100 text-green-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                ];
                                                $statusText = [
                                                    'pending' => 'Đang chờ xác nhận',
                                                    'confirmed' => 'Đã xác nhận lịch',
                                                    'completed' => 'Đã hoàn thành',
                                                    'cancelled' => 'Đã hủy',
                                                ];
                                            @endphp
                                            <span class="px-4 py-1.5 text-xs font-bold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $statusText[$booking->status] ?? $booking->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-2">
                                        <p><i class="fas fa-map-marker-alt w-5 text-gray-400"></i> <strong>Địa chỉ:</strong> {{ $booking->address }}</p>
                                        <p><i class="fas fa-exclamation-circle w-5 text-gray-400"></i> <strong>Tình trạng máy:</strong> {{ $booking->issue_description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-tools text-6xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500 font-medium">Bạn chưa có lịch hẹn sửa chữa nào.</p>
                            <a href="#" class="hidden inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">Đặt lịch ngay</a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection