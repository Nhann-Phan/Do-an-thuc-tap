@extends('layouts.admin_layout')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- CỘT TRÁI (CHI TIẾT SẢN PHẨM) --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Card Chi tiết --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center bg-white">
                <h5 class="font-bold text-blue-600 text-lg">
                    Chi tiết đơn hàng #{{ $order->id }}
                </h5>
                <span class="text-gray-500 text-sm">
                    Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 text-sm font-bold border-b border-gray-200">
                                <th class="px-6 py-3 whitespace-nowrap">Sản phẩm</th>
                                <th class="px-6 py-3 whitespace-nowrap text-center">SL</th>
                                <th class="px-6 py-3 whitespace-nowrap text-right">Giá</th>
                                <th class="px-6 py-3 whitespace-nowrap text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $item->product_name }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($item->price) }}đ</td>
                                <td class="px-6 py-4 text-right font-bold">{{ number_format($item->price * $item->quantity) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700 uppercase">Tổng cộng:</td>
                                <td class="px-6 py-4 text-right font-bold text-red-600 text-lg">
                                    {{ number_format($order->total_money) }}đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Nút Quay lại --}}
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition shadow-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    {{-- CỘT PHẢI (THÔNG TIN & XỬ LÝ) --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Card Thông tin khách hàng --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50">
                <h6 class="font-bold text-gray-800">Thông tin khách hàng</h6>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-bold text-gray-500">Họ tên:</span>
                    <span class="font-medium text-gray-900">{{ $order->name }}</span>
                </p>
                <p class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-bold text-gray-500">SĐT:</span>
                    <span class="font-medium text-gray-900">{{ $order->phone }}</span>
                </p>
                <p class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-bold text-gray-500">Email:</span>
                    <span class="font-medium text-gray-900">{{ $order->email ?? 'Không có' }}</span>
                </p>
                <div class="pb-2">
                    <span class="block font-bold text-gray-500 mb-1">Địa chỉ:</span>
                    <span class="block font-medium text-gray-900 bg-gray-50 p-2 rounded">{{ $order->address }}</span>
                </div>
                
                {{-- Ghi chú (Alert Warning) --}}
                <div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg text-sm">
                    <strong class="font-bold block mb-1"><i class="fas fa-sticky-note mr-1"></i> Ghi chú:</strong>
                    {{ $order->note ?? 'Không có ghi chú' }}
                </div>
            </div>
        </div>

        {{-- Card Xử lý đơn hàng --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Header màu xanh --}}
            <div class="px-6 py-3 bg-blue-600 text-white">
                <h6 class="font-bold m-0">Xử lý đơn hàng</h6>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Trạng thái đơn:</label>
                        <div class="relative">
                            <select name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm border">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                <option value="cancel" {{ $order->status == 'cancel' ? 'selected' : '' }}>Hủy đơn</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg shadow transition duration-150 ease-in-out uppercase text-sm">
                        Cập nhật trạng thái
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection