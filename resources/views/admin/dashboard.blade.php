@extends('layouts.admin_layout')

@section('content')
<h3 class="mb-4 fw-bold text-secondary">Tổng quan hệ thống</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd !important;">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Tổng đặt lịch</h6>
                <h2 class="mb-0 text-primary">{{ $total_bookings ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Đang chờ xử lý</h6>
                <h2 class="mb-0 text-warning">{{ $pending_count ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Đã hoàn thành</h6>
                <h2 class="mb-0 text-success">{{ $completed_count ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold">Đã hủy</h6>
                <h2 class="mb-0 text-danger">{{ $cancelled_count ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold py-3">
        <i class="fas fa-calendar-alt me-2 text-primary"></i> Đơn đặt lịch mới nhất
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Khách hàng</th>
                    <th>Dịch vụ/Sự cố</th>
                    <th>Thời gian hẹn</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($bookings))
                    @foreach($bookings as $booking)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $booking->customer_name }}</div> <div class="small text-muted">{{ $booking->phone_number }}</div> </td>
                        <td>{{ $booking->issue_description }}</td> <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($booking->status == 'pending') 
                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            @elseif($booking->status == 'completed') 
                                <span class="badge bg-success">Hoàn thành</span>
                            @else 
                                <span class="badge bg-danger">Đã hủy</span> 
                            @endif
                        </td>
                        <td class="text-end">
                            @if($booking->status == 'pending')
                                <a href="{{ url('/admin/booking/update/'.$booking->id.'/completed') }}" 
                                class="btn btn-sm btn-success" 
                                title="Đã làm xong"
                                onclick="return confirm('Xác nhận đã xử lý xong yêu cầu này?')">
                                    <i class="fas fa-check"></i>
                                </a>

                                <a href="{{ url('/admin/booking/update/'.$booking->id.'/cancelled') }}" 
                                class="btn btn-sm btn-danger" 
                                title="Hủy bỏ"
                                onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')">
                                    <i class="fas fa-times"></i>
                                </a>
                            @else
                                @if($booking->status == 'completed')
                                    <span class="text-muted small">Đã liên hệ hỗ trợ</span>
                                @else
                                    <span class="text-muted small">Đơn đã hủy</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection