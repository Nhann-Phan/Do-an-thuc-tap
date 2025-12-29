@extends('layouts.admin_layout')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3">
        <h5 class="m-0 fw-bold text-primary"><i class="fas fa-shopping-cart me-2"></i>Quản Lý Đơn Hàng</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Ngày Đặt</th>
                    <th>Trạng Thái</th>
                    <th class="text-end">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="fw-bold">#{{ $order->id }}</td>
                    <td>
                        <div class="fw-bold">{{ $order->name }}</div>
                        <small class="text-muted">{{ $order->phone }}</small>
                    </td>
                    <td class="fw-bold text-danger">{{ number_format($order->total_money) }}đ</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($order->status == 'pending')
                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                        @elseif($order->status == 'processing')
                            <span class="badge bg-primary">Đang giao</span>
                        @elseif($order->status == 'completed')
                            <span class="badge bg-success">Hoàn thành</span>
                        @else
                            <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info text-white me-1" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection