@extends('layouts.admin_layout')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-primary">Chi tiết đơn hàng #{{ $order->id }}</h5>
                <span class="text-muted small">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->price) }}đ</td>
                            <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity) }}đ</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TỔNG CỘNG:</td>
                            <td class="text-end fw-bold text-danger fs-5">{{ number_format($order->total_money) }}đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Quay lại danh sách</a>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold">Thông tin khách hàng</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Họ tên:</strong> {{ $order->name }}</p>
                <p class="mb-2"><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p class="mb-2"><strong>Email:</strong> {{ $order->email ?? 'Không có' }}</p>
                <p class="mb-2"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                <div class="alert alert-warning mt-3 mb-0">
                    <strong>Ghi chú:</strong> {{ $order->note ?? 'Không có ghi chú' }}
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 fw-bold">Xử lý đơn hàng</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái đơn:</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="cancel" {{ $order->status == 'cancel' ? 'selected' : '' }}>Hủy đơn</option>
                        </select>
                    </div>
                    <button class="btn btn-success w-100 fw-bold">CẬP NHẬT TRẠNG THÁI</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection