@extends('layouts.client_layout')
@section('content')
<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-2">Đăng Ký Tài Khoản</h1>
        <p class="text-center text-gray-500 mb-8 text-sm">Trở thành thành viên để mua sắm dễ dàng hơn</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu *</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu *</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition mt-4">Tạo Tài Khoản</button>
        </form>
        <div class="text-center mt-6 text-sm">
            Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection