@extends('layouts.admin_layout')

@section('content')
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.accounts.index') }}" class="text-gray-500 hover:text-blue-600 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h3 class="font-bold text-gray-700 text-xl">Cập Nhật Tài Khoản</h3>
    </div>

    <form action="{{ route('admin.accounts.update', $user->id) }}" method="POST" class="max-w-3xl bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Họ Tên --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Họ và Tên <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email đăng nhập <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Mật khẩu --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới (Để trống nếu không đổi)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Số điện thoại --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Quyền hạn --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phân quyền <span class="text-red-500">*</span></label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                    <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Nhân viên bán hàng</option>
                    <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                    <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Khách hàng</option>
                </select>
                @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Trạng thái --}}
            <div class="flex flex-col justify-center mt-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-700">Tài khoản Hoạt động</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                Cập Nhật
            </button>
        </div>
    </form>
@endsection