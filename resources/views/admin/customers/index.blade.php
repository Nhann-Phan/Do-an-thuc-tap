@extends('layouts.admin_layout')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-sm">
    
    {{-- PHẦN HEADER & THANH TÌM KIẾM --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
        <h2 class="text-xl font-bold text-gray-800">Danh sách Khách hàng</h2>

        <form action="{{ route('admin.customers.index') }}" method="GET" class="w-full md:w-auto flex gap-2">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" 
                    placeholder="Tìm tên, SĐT">
            </div>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                Tìm kiếm
            </button>
            
            {{-- Nút xóa lọc chỉ hiện khi đang có từ khóa tìm kiếm --}}
            @if(!empty($search))
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-semibold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150" title="Xóa bộ lọc">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
    
    {{-- PHẦN BẢNG DỮ LIỆU --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Khách hàng</th>
                    <th class="px-6 py-3">Liên hệ</th>
                    <th class="px-6 py-3 text-center">Số lần đặt</th>
                    <th class="px-6 py-3 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $customer->name }}
                        <div class="text-xs text-gray-400">ID: #{{ $customer->id }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-blue-600">{{ $customer->phone }}</span>
                            <span>{{ Str::limit($customer->address, 30) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- Hiển thị số lần đặt --}}
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                            {{ $customer->bookings_count }} lần
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.customers.show', $customer->id) }}" 
                           class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-3 py-2 shadow-sm transition">
                            <i class="fas fa-history mr-1"></i> Xem lịch sử
                        </a>
                    </td>
                </tr>
                @empty
                {{-- Hiển thị khi không có dữ liệu --}}
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="text-gray-400 mb-2"><i class="fas fa-users-slash text-4xl"></i></div>
                        <div class="text-gray-500 italic text-base">
                            Không tìm thấy khách hàng nào khớp với từ khóa "<span class="font-bold text-gray-700">{{ $search ?? '' }}</span>"
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
@endsection