@extends('layouts.client_layout')
@section('content')
<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-2">Đăng Nhập</h1>
        <p class="text-center text-gray-500 mb-8 text-sm">Chào mừng bạn quay lại TechShop</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu *</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm"><input type="checkbox" name="remember" class="mr-2"> Ghi nhớ</label>
                <a href="#" class="text-sm text-blue-600 hover:underline">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition mt-4">Đăng Nhập</button>
        </form>
        <div class="text-center mt-6 text-sm">
            Chưa có tài khoản? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Đăng ký ngay</a>
        </div>
    </div>
</div>
@endsection