@extends('layouts.admin_layout')

@section('content')

{{-- Sử dụng Bootstrap 5 chuẩn --}}
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-secondary mb-1">Quản lý: {{ $category->name }}</h3>
            <small class="text-muted">Danh sách sản phẩm thuộc danh mục này</small>
        </div>
        
        <a href="{{ route('product.create', ['category_id' => $category->id]) }}" 
           class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i> Thêm Sản Phẩm Mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-secondary small text-uppercase fw-bold">
                        <th class="p-3 text-start">#</th>
                        <th class="p-3 text-start">Ảnh</th>
                        <th class="p-3 text-start">Tên sản phẩm</th>
                        <th class="p-3 text-start">Thương hiệu</th>
                        <th class="p-3 text-start">Giá bán</th>
                        <th class="p-3 text-start">Trạng thái</th>
                        <th class="p-3 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($category->products as $product)
                    <tr class="cursor-pointer"
                        onclick="window.location='{{ route('product.edit', $product->id) }}'"
                        style="cursor: pointer;"
                        title="Bấm để chỉnh sửa">
                        
                        <td class="p-3 text-muted small">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-3">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" 
                                     class="rounded border" 
                                     style="height: 64px; width: 64px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/150?text=No+Img'">
                            @else
                                <span class="d-inline-flex align-items-center justify-content-center bg-light border rounded text-secondary small" 
                                      style="height: 64px; width: 64px;">
                                    No Img
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="fw-bold text-dark mb-1">{{ $product->name }}</div>
                            <div class="small text-muted">{{ $product->sku }}</div>
                        </td>

                        <td class="p-3">
                            @if($product->brand)
                                <span class="badge bg-light text-primary border border-primary-subtle text-uppercase">
                                    {{ $product->brand }}
                                </span>
                            @else
                                <span class="text-muted small fst-italic">---</span>
                            @endif
                        </td>

                        <td class="p-3">
                            @if($product->sale_price)
                                <div class="fw-bold text-danger">{{ number_format($product->sale_price) }} đ</div>
                                <div class="small text-muted text-decoration-line-through">{{ number_format($product->price) }} đ</div>
                            @else
                                <div class="fw-bold text-dark">{{ number_format($product->price) }} đ</div>
                            @endif
                        </td>

                        <td class="p-3">
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Hiện</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Ẩn</span>
                            @endif

                            @if($product->is_hot)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1">HOT</span>
                            @endif
                        </td>

                        <td class="p-3 text-end">
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Xóa"
                                        onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-5 text-center text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 text-secondary opacity-25"></i>
                            <p class="mb-0">Chưa có sản phẩm nào trong danh mục này.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection