@extends('layouts.client_layout')

@section('content')
<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-2">Đặt Lại Mật Khẩu</h1>
        <p class="text-center text-gray-500 text-sm mb-6">Vui lòng nhập mã OTP gồm 6 chữ số và mật khẩu mới của bạn.</p>
        
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

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            {{-- Input ẩn để gửi email đi kèm theo form --}}
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mã xác nhận OTP *</label>
                <input type="text" name="token" required placeholder="Ví dụ: 123456" 
                       class="w-full px-4 py-3 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-center tracking-widest font-bold text-lg text-blue-600 bg-blue-50">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới *</label>
                <input type="password" name="password" required placeholder="Ít nhất 6 ký tự" 
                       class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu *</label>
                <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới" 
                       class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition mt-6 shadow-lg shadow-blue-500/30">
                Đổi Mật Khẩu
            </button>
        </form>
    </div>
</div>
@endsection