@extends('layouts.admin_layout')

@section('content')

<style>
    .cke_notification_warning { display: none !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary">Cập nhật sản phẩm</h3>
    <a href="{{ route('product.index_admin') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-warning text-dark fw-bold">
        <i class="fas fa-edit me-1"></i> Đang chỉnh sửa: {{ $product->name }}
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fas fa-check-circle me-2 text-xl"></i>
                <div><strong>Thành công!</strong> {{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                {{-- CỘT TRÁI: TÊN & MÔ TẢ --}}
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả chi tiết</label>
                        <textarea name="description" id="description" class="form-control" rows="10">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                {{-- CỘT PHẢI: THÔNG TIN PHỤ --}}
                <div class="col-md-4">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- === TRƯỜNG THƯƠNG HIỆU (CẬP NHẬT) === --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Thương hiệu</label>
                                <input type="text" name="brand" class="form-control" 
                                       placeholder="Ví dụ: Dell, HP, TP-Link..." 
                                       value="{{ old('brand', $product->brand) }}">
                            </div>
                            {{-- === KẾT THÚC === --}}

                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh sản phẩm</label>
                                <input type="file" name="image" id="imageInput" class="form-control mb-2" accept="image/*" onchange="previewImage(this)">
                                
                                <div class="p-2 border bg-white rounded text-center" style="min-height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                    <img id="preview" 
                                         src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/150?text=Chưa+có+ảnh' }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px;"
                                         onerror="this.src='https://via.placeholder.com/150?text=Lỗi+Ảnh'">
                                    
                                    <span id="placeholder-text" class="text-muted small mt-2">
                                        {{ $product->image ? 'Ảnh hiện tại' : 'Chưa có ảnh' }}
                                    </span>
                                </div>
                            </div>

                            <hr>

                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $product->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="activeSwitch">Hiển thị</label>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_hot" id="hotSwitch" {{ $product->is_hot ? 'checked' : '' }}>
                                <label class="form-check-label text-danger fw-bold" for="hotSwitch">Sản phẩm HOT</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-uppercase shadow">
                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace( 'description', { height: 400, language: 'vi' });

    function previewImage(input) {
        var preview = document.getElementById('preview');
        var placeholder = document.getElementById('placeholder-text');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                placeholder.innerText = "Ảnh mới chọn (Chưa lưu)"; 
                placeholder.classList.add('text-success', 'fw-bold'); 
                placeholder.classList.remove('text-muted');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection