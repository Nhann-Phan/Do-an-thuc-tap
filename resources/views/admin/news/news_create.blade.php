@extends('layouts.admin_layout')

@section('content')

{{-- CSS FIX LỖI HIỂN THỊ CKEDITOR NOT SECURE --}}
<style>
    .cke_notification_warning { display: none !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary">Đăng Tin Tức Mới</h3>
        <a href="{{ route('news.index_admin') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
        @endif

        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề tin <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="Nhập tiêu đề bài viết...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn (Summary)</label>
                        {{-- SỬA: name="summary" để khớp với database --}}
                        <textarea name="summary" class="form-control" rows="3" placeholder="Đoạn văn ngắn hiển thị dưới ảnh..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung chi tiết</label>
                        <textarea name="content" id="content" class="form-control" rows="10"></textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình ảnh đại diện</label>
                                <input type="file" name="image" class="form-control mb-2" accept="image/*" onchange="previewImage(this)">
                                {{-- <img id="preview" src="https://via.placeholder.com/300x200?text=No+Image" class="img-fluid rounded border"> --}}
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked>
                                <label class="form-check-label fw-bold" for="activeSwitch">Hiển thị ngay</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase"><i class="fas fa-paper-plane me-1"></i> Đăng bài viết</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', { height: 400 });
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { document.getElementById('preview').src = e.target.result; }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection