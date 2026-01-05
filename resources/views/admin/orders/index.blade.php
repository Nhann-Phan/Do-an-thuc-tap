@extends('layouts.admin_layout')

@section('content')

{{-- HEADER CARD --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    <div class="px-6 py-4 border-b border-gray-100 flex items-center bg-gray-50/50">
        <h5 class="font-bold text-blue-600 text-lg flex items-center">
            <i class="fas fa-shopping-cart mr-2"></i> Quản Lý Đơn Hàng
        </h5>
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
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                            Chưa có đơn hàng nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex justify-center">
            {{-- Nếu chưa config pagination tailwind, dùng tạm style cơ bản --}}
            <div class="flex space-x-1">
                {{ $orders->onEachSide(1)->links('pagination::tailwind') }} 
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