@extends('layouts.client_layout')

@section('content')
<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-2">Quên Mật Khẩu</h1>
        <p class="text-center text-gray-500 text-sm mb-6">Vui lòng nhập email của bạn, chúng tôi sẽ gửi mã xác nhận OTP để đặt lại mật khẩu.</p>
        
        @if(session('status'))
            <div class="bg-green-50 text-green-600 p-3 rounded-lg mb-6 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Nhập email của bạn" class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition mt-4">
                Gửi mã xác nhận
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-600">
            Đã nhớ lại mật khẩu? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection