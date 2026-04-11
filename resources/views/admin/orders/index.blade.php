@extends('layouts.admin_layout')

@section('content')

{{-- HEADER CARD --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    {{-- PHẦN HEADER & THANH TÌM KIẾM --}}
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between bg-gray-50/50 gap-4">
        <h5 class="font-bold text-blue-600 text-lg flex items-center">
            <i class="fas fa-shopping-cart mr-2"></i> Quản Lý Đơn Hàng
        </h5>

        {{-- Form Tìm kiếm --}}
        <form action="{{ route('admin.orders.index') }}" method="GET" class="w-full md:w-auto flex gap-2">
            <div class="relative w-full md:w-72">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                    class="block w-full pl-10 pr-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                    placeholder="Tìm mã đơn, tên, SĐT...">
            </div>
            
            <button type="submit" class="inline-flex items-center px-4 py-1.5 bg-blue-600 rounded-lg font-semibold text-xs text-white uppercase hover:bg-blue-700 transition">
                Tìm
            </button>
            
            {{-- Nút xóa lọc (Chỉ hiện khi có tìm kiếm) --}}
            @if(!empty($search))
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300 transition" title="Xóa bộ lọc">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold border-b border-gray-200">
                        <th class="px-6 py-3 whitespace-nowrap">Mã Đơn</th>
                        <th class="px-6 py-3 whitespace-nowrap">Khách Hàng</th>
                        <th class="px-6 py-3 whitespace-nowrap">Tổng Tiền</th>
                        <th class="px-6 py-3 whitespace-nowrap">Ngày Đặt</th>
                        <th class="px-6 py-3 whitespace-nowrap text-center">Trạng Thái</th>
                        <th class="px-6 py-3 whitespace-nowrap text-right">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700 bg-white">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 font-bold text-gray-900">#{{ $order->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $order->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $order->phone }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-red-600">
                            {{ number_format($order->total_money) }}đ
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusStyles = match($order->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                };
                                $statusLabel = match($order->status) {
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang giao',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                    default => 'Không rõ'
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusStyles }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Nút Xem --}}
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="p-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded transition shadow-sm" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye text-xs px-0.5"></i>
                                </a>

                                {{-- Nút Xóa (Dùng SweetAlert2 thay confirm mặc định để đẹp hơn) --}}
                                <button type="button" onclick="confirmDeleteOrder('{{ $order->id }}')" 
                                        class="p-1.5 bg-red-500 hover:bg-red-600 text-white rounded transition shadow-sm" 
                                        title="Xóa">
                                    <i class="fas fa-trash text-xs px-0.5"></i>
                                </button>

                                {{-- Form xóa ẩn --}}
                                <form id="delete-order-{{ $order->id }}" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="text-gray-300 mb-3"><i class="fas fa-receipt text-5xl"></i></div>
                            <div class="text-gray-400 italic text-base">
                                @if(!empty($search))
                                    Không tìm thấy đơn hàng nào khớp với từ khóa "<span class="font-bold text-gray-600">{{ $search }}</span>"
                                @else
                                    Chưa có đơn hàng nào.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex justify-center bg-white">
            <div class="flex space-x-1">
                {{ $orders->appends(['search' => $search ?? ''])->links('pagination::tailwind') }} 
            </div>
        </div>
    </div>
</div>

{{-- Script xử lý xóa (SweetAlert2) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteOrder(id) {
        Swal.fire({
            title: 'Xóa đơn hàng?',
            text: "Bạn có chắc chắn muốn xóa đơn hàng này không?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500
            cancelButtonColor: '#6b7280', // Gray-500
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-order-' + id).submit();
            }
        })
    }
</script>

@endsection