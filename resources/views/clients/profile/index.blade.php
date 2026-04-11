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

            {{-- NỘI DUNG CHÍNH --}}
            <div class="md:col-span-3 space-y-6">
                
                {{-- Thông báo --}}
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Form Cập Nhật Thông Tin --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-3">Thông Tin Cá Nhân</h2>
                        
                        <form action="{{ route('client.profile.update_info') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email đăng nhập</label>
                                <input type="email" value="{{ $user ? $user->email : '' }}" disabled class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên</label>
                                <input type="text" name="name" value="{{ $customer ? $customer->name : '' }}" required class="w-full px-4 py-2 border rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                                <input type="text" name="phone_number" value="{{ $customer ? $customer->phone_number : '' }}" class="w-full px-4 py-2 border rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                <input type="text" name="address" value="{{ $customer ? $customer->address : '' }}" class="w-full px-4 py-2 border rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition mt-2">Lưu Thông Tin</button>
                        </form>
                    </div>

                    {{-- Form Đổi Mật Khẩu --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-red-500 pl-3">Đổi Mật Khẩu</h2>
                        
                        <form action="{{ route('client.profile.update_password') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" required class="w-full px-4 py-2 border rounded-lg outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                                <input type="password" name="new_password" required class="w-full px-4 py-2 border rounded-lg outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" required class="w-full px-4 py-2 border rounded-lg outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            </div>
                            
                            <button type="submit" class="w-full bg-gray-800 text-white font-bold py-2.5 rounded-lg hover:bg-black transition mt-2">Đổi Mật Khẩu</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection