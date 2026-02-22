@extends('layouts.admin_layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-gray-700 text-xl">Quản lý Tài khoản</h3>
        <a href="{{ route('admin.accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
            <i class="fas fa-plus"></i> Thêm tài khoản
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm font-bold border-b border-gray-200">
                        <th class="px-6 py-3 whitespace-nowrap">Họ Tên / Email</th>
                        <th class="px-6 py-3 whitespace-nowrap">Số điện thoại</th>
                        <th class="px-6 py-3 whitespace-nowrap">Quyền hạn</th>
                        <th class="px-6 py-3 whitespace-nowrap">Trạng thái</th>
                        <th class="px-6 py-3 whitespace-nowrap text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $user->phone ?? 'Chưa cập nhật' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Admin</span>
                                @elseif($user->role == 1)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Nhân viên</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">Khách hàng</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active == 1)
                                    <span class="text-green-600 text-xs font-bold"><i class="fas fa-circle text-[8px] mr-1"></i>Hoạt động</span>
                                @else
                                    <span class="text-gray-400 text-xs font-bold"><i class="fas fa-circle text-[8px] mr-1"></i>Bị khóa</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.accounts.edit', $user->id) }}" class="p-1.5 text-white bg-yellow-500 hover:bg-yellow-600 rounded transition shadow-sm" title="Sửa">
                                        <i class="fas fa-edit text-xs px-0.5"></i>
                                    </a>
                                    <a href="{{ route('admin.accounts.destroy', $user->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')" class="p-1.5 text-white bg-red-600 hover:bg-red-700 rounded transition shadow-sm" title="Xóa">
                                        <i class="fas fa-trash text-xs px-0.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                Chưa có tài khoản nào khác trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Phân trang --}}
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
@endsection