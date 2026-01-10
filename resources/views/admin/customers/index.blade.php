@extends('layouts.admin_layout') {{-- Thay bằng layout admin của bạn --}}

@section('content')
<div class="p-6 bg-white rounded-lg shadow-sm">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Danh sách Khách hàng (CRM)</h2>
    
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
                @foreach($customers as $customer)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $customer->name }}
                        <div class="text-xs text-gray-400">ID: #{{ $customer->id }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-blue-600">{{ $customer->phone_number }}</span>
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
                           class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-3 py-2">
                            <i class="fas fa-history mr-1"></i> Xem lịch sử
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
@endsection