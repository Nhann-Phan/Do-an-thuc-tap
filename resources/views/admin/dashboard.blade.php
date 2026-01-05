@extends('layouts.admin_layout')

@section('content')

    {{-- TIÊU ĐỀ --}}
    <h3 class="mb-6 font-bold text-gray-500 text-xl">Tổng quan hệ thống</h3>

    {{-- THỐNG KÊ (STATS GRID) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        {{-- Card 1: Tổng --}}
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-blue-600 p-4 h-full">
            <div class="flex flex-col h-full justify-between">
                <h6 class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-2">Tổng đặt lịch</h6>
                <h2 class="text-2xl font-bold text-blue-600">{{ $total_bookings ?? 0 }}</h2>
            </div>
        </div>

        {{-- Card 2: Chờ xử lý --}}
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-yellow-400 p-4 h-full">
            <div class="flex flex-col h-full justify-between">
                <h6 class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-2">Đang chờ xử lý</h6>
                <h2 class="text-2xl font-bold text-yellow-500">{{ $pending_count ?? 0 }}</h2>
            </div>
        </div>

        {{-- Card 3: Hoàn thành --}}
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-600 p-4 h-full">
            <div class="flex flex-col h-full justify-between">
                <h6 class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-2">Đã hoàn thành</h6>
                <h2 class="text-2xl font-bold text-green-600">{{ $completed_count ?? 0 }}</h2>
            </div>
        </div>

        {{-- Card 4: Đã hủy --}}
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-red-600 p-4 h-full">
            <div class="flex flex-col h-full justify-between">
                <h6 class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-2">Đã hủy</h6>
                <h2 class="text-2xl font-bold text-red-600">{{ $cancelled_count ?? 0 }}</h2>
            </div>
        </div>
    </div>

    {{-- BẢNG DỮ LIỆU (TABLE) --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        
        {{-- Table Header --}}
        <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center font-bold text-gray-700">
            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Đơn đặt lịch mới nhất
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm font-bold border-b border-gray-200">
                        <th class="px-6 py-3 whitespace-nowrap">Khách hàng</th>
                        <th class="px-6 py-3 whitespace-nowrap">Dịch vụ/Sự cố</th>
                        <th class="px-6 py-3 whitespace-nowrap">Thời gian hẹn</th>
                        <th class="px-6 py-3 whitespace-nowrap">Trạng thái</th>
                        <th class="px-6 py-3 whitespace-nowrap text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @if(isset($bookings) && count($bookings) > 0)
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition duration-150 group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $booking->customer_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $booking->phone_number }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-gray-600" title="{{ $booking->issue_description }}">
                                {{ $booking->issue_description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->status == 'pending') 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        Chờ xử lý
                                    </span>
                                @elseif($booking->status == 'completed') 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        Hoàn thành
                                    </span>
                                @else 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        Đã hủy
                                    </span> 
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($booking->status == 'pending')
                                        <a href="{{ url('/admin/booking/update/'.$booking->id.'/completed') }}" 
                                           class="p-1.5 text-white bg-green-600 hover:bg-green-700 rounded transition shadow-sm" 
                                           title="Đã làm xong"
                                           onclick="return confirm('Xác nhận đã xử lý xong yêu cầu này?')">
                                            <i class="fas fa-check text-xs px-0.5"></i>
                                        </a>

                                        <a href="{{ url('/admin/booking/update/'.$booking->id.'/cancelled') }}" 
                                           class="p-1.5 text-white bg-red-600 hover:bg-red-700 rounded transition shadow-sm" 
                                           title="Hủy bỏ"
                                           onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')">
                                            <i class="fas fa-times text-xs px-0.5"></i>
                                        </a>
                                    @else
                                        @if($booking->status == 'completed')
                                            <span class="text-xs text-gray-400 italic">Đã liên hệ hỗ trợ</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Đơn đã hủy</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3 opacity-30"></i>
                                    <span class="text-sm italic">Chưa có dữ liệu đặt lịch nào.</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection