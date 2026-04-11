@extends('layouts.admin_layout')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h3 class="font-bold text-gray-800 text-2xl m-0">Quản lý Tài khoản</h3>
        <p class="text-sm text-gray-500 mt-1">Quản trị viên, nhân viên và khách hàng</p>
    </div>
    <a href="{{ route('admin.accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2 transform active:scale-95">
        <i class="fas fa-plus"></i> Thêm tài khoản
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    {{-- TOOLBAR (FILTER QUYỀN HẠN & SEARCH) --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            
            {{-- Dropdown lọc quyền hạn --}}
            <div class="w-full md:w-64 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-500 pointer-events-none">
                    <i class="fas fa-user-shield text-sm"></i>
                </span>
                <select name="role" onchange="this.form.submit()" class="block w-full pl-9 pr-10 py-2.5 text-sm font-medium border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white appearance-none cursor-pointer text-gray-700 hover:border-blue-400 transition">
                    <option value="">Tất cả quyền hạn</option>
                    {{-- Dựa vào code của mày: 0=Admin, 1=Nhân viên, khác=Khách hàng --}}
                    <option value="0" {{ request('role') === '0' ? 'selected' : '' }}>Admin</option>
                    <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Nhân viên</option>
                    <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Khách hàng</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            {{-- Ô Tìm kiếm chữ --}}
            <div class="flex gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition" 
                        placeholder="Tìm tên, email, SĐT...">
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition shadow-sm whitespace-nowrap">
                    Tìm
                </button>
                
                {{-- Nút xóa lọc (Chỉ hiện khi có nhập liệu) --}}
                @if((request()->has('search') && request('search') != '') || (request()->has('role') && request('role') != ''))
                    <a href="{{ route('admin.accounts.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2.5 rounded-lg text-sm font-semibold transition flex items-center justify-center border border-red-100" title="Xóa bộ lọc">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200">
                    <th class="px-6 py-4 whitespace-nowrap">Họ Tên / Email</th>
                    <th class="px-6 py-4 whitespace-nowrap">Số điện thoại</th>
                    <th class="px-6 py-4 whitespace-nowrap">Quyền hạn</th>
                    <th class="px-6 py-4 whitespace-nowrap">Trạng thái</th>
                    <th class="px-6 py-4 whitespace-nowrap text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ $user->phone ?? 'Chưa cập nhật' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role == 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Admin</span>
                            @elseif($user->role == 1)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">Nhân viên</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">Khách hàng</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active == 1)
                                <span class="text-green-600 text-xs font-bold flex items-center">
                                    <i class="fas fa-circle text-[8px] mr-1.5"></i>Hoạt động
                                </span>
                            @else
                                <span class="text-gray-400 text-xs font-bold flex items-center">
                                    <i class="fas fa-circle text-[8px] mr-1.5"></i>Bị khóa
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.accounts.edit', $user->id) }}" class="p-1.5 w-8 h-8 flex items-center justify-center text-white bg-yellow-500 hover:bg-yellow-600 rounded transition shadow-sm" title="Sửa">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                
                                {{-- Đã sửa thành Form DELETE chuẩn của Laravel --}}
                                <form action="{{ route('admin.accounts.destroy', $user->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không? Mọi dữ liệu liên quan có thể bị mất!');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 w-8 h-8 flex items-center justify-center text-white bg-red-600 hover:bg-red-700 rounded transition shadow-sm" title="Xóa">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="text-gray-300 mb-3"><i class="fas fa-user-slash text-5xl"></i></div>
                            <div class="text-gray-500 italic text-base">
                                @if((request()->has('search') && request('search') != '') || (request()->has('role') && request('role') != ''))
                                    Không tìm thấy tài khoản nào khớp với bộ lọc.
                                @else
                                    Chưa có tài khoản nào khác trong hệ thống.
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Phân trang --}}
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-center">
        {{ $users->links('pagination::tailwind') }}
    </div>
</div>
@endsection