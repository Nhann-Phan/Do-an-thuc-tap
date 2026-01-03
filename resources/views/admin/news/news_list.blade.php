@extends('layouts.admin_layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary">Quản lý Tin Tức</h3>
    <a href="{{ route('news.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Viết bài mới
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3">#</th>
                        <th class="p-3" style="width: 100px;">Ảnh</th>
                        <th class="p-3">Tiêu đề & Tóm tắt</th>
                        <th class="p-3 text-center">Trạng thái</th>
                        <th class="p-3">Ngày đăng</th>
                        <th class="p-3 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsList as $news)
                    {{-- 
                        THAY ĐỔI Ở ĐÂY: 
                        1. Thêm onclick để chuyển hướng sang trang Edit 
                        2. Thêm style cursor: pointer 
                    --}}
                    <tr onclick="window.location='{{ route('news.edit', $news->id) }}'" style="cursor: pointer;" title="Nhấn để chỉnh sửa">
                        
                        <td class="p-3">{{ $loop->iteration }}</td>
                        
                        <td class="p-3">
                            @if($news->image)
                                <img src="{{ asset($news->image) }}" class="rounded border" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        
                        <td class="p-3">
                            <h6 class="mb-1 fw-bold text-dark">{{ $news->title }}</h6>
                            <small class="text-muted text-truncate d-block" style="max-width: 300px;">{{ $news->summary }}</small>
                        </td>
                        
                        <td class="p-3 text-center">
                            @if($news->is_active)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        
                        <td class="p-3 text-muted small">
                            {{ $news->created_at->format('d/m/Y') }}
                        </td>
                        
                        <td class="p-3 text-end">
                            {{-- Đã xóa nút Edit (hình cây bút) vì click dòng là sửa rồi --}}
                            
                            <form action="{{ route('news.destroy', $news->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                {{-- 
                                    QUAN TRỌNG: Thêm event.stopPropagation() 
                                    để ngăn việc click nút Xóa kích hoạt sự kiện click của dòng (tr)
                                --}}
                                <button onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa bài này?');" class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-4 text-muted">Chưa có bài viết nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($newsList->hasPages())
    <div class="card-footer bg-white">
        {{ $newsList->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection