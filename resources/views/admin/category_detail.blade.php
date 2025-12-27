@extends('layouts.admin_layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Quản lý: {{ $category->name }}</h3>
        <small class="text-muted">Danh sách sản phẩm thuộc danh mục này</small>
    </div>
    <a href="{{ route('product.create', ['category_id' => $category->id]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Thêm Sản Phẩm Mới
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th width="80">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá bán</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($category->products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" 
                            class="img-fluid rounded border" 
                            style="max-height: 150px;"
                            onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                        @else
                            <span class="text-muted small">No img</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ $product->name }}</div>
                        <small class="text-muted">{{ $product->sku }}</small>
                    </td>
                    <td>
                        @if($product->sale_price)
                            <span class="text-danger fw-bold">{{ number_format($product->sale_price) }}</span>
                            <br><small class="text-decoration-line-through text-muted">{{ number_format($product->price) }}</small>
                        @else
                            {{ number_format($product->price) }}
                        @endif
                    </td>
                    <td>
                        @if($product->is_active) <span class="badge bg-success">Hiện</span> @else <span class="badge bg-secondary">Ẩn</span> @endif
                        @if($product->is_hot) <span class="badge bg-danger">HOT</span> @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Chưa có sản phẩm nào trong danh mục này.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection