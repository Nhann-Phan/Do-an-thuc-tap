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
                {{-- ==================================================== --}}
                {{-- CỘT TRÁI (LỚN): TÊN, MÔ TẢ & BIẾN THỂ --}}
                {{-- ==================================================== --}}
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả chi tiết</label>
                        <textarea name="description" id="description" class="form-control" rows="10">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="card border border-warning bg-white mb-3 mt-4">
                        <div class="card-header bg-warning text-dark py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="fas fa-tags me-1"></i> Các phiên bản giá (Tùy chọn)</span>
                            <small class="text-dark fst-italic">Giá thấp nhất sẽ tự động cập nhật làm Giá chính</small>
                        </div>
                        <div class="card-body p-3">
                            {{-- Header của bảng biến thể --}}
                            <div class="row g-2 mb-2 fw-bold text-muted small border-bottom pb-1">
                                <div class="col-6">Tên phiên bản (VD: 6 tháng)</div>
                                <div class="col-5">Giá tiền (VNĐ)</div>
                                <div class="col-1 text-center">Xóa</div>
                            </div>

                            <div id="variants-container">
                                {{-- HIỂN THỊ CÁC BIẾN THỂ CŨ TỪ DATABASE --}}
                                @foreach($product->variants as $index => $variant)
                                <div class="row g-2 mb-3 align-items-center variant-item border-bottom pb-2 bg-light rounded p-2">
                                    {{-- Input ẩn để biết đây là update dòng cũ --}}
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    
                                    <div class="col-6">
                                        <input type="text" name="variants[{{ $index }}][name]" class="form-control" value="{{ $variant->name }}" placeholder="VD: Gói 1 năm" required>
                                    </div>
                                    <div class="col-5">
                                        <input type="number" name="variants[{{ $index }}][price]" class="form-control" value="{{ $variant->price }}" placeholder="Nhập giá" required>
                                    </div>
                                    <div class="col-1 text-center">
                                        {{-- Checkbox xóa --}}
                                        <div class="form-check d-flex justify-content-center m-0 pt-1" title="Tick vào đây để xóa dòng này khi Lưu">
                                            <input class="form-check-input border-danger" type="checkbox" name="variants[{{ $index }}][delete]" value="1">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" onclick="addVariant()" class="btn btn-outline-warning text-dark btn-sm w-100 border-dashed mt-2 py-2 fw-bold">
                                <i class="fas fa-plus me-1"></i> Thêm phiên bản mới
                            </button>
                        </div>
                    </div>
                    </div>

                {{-- ==================================================== --}}
                {{-- CỘT PHẢI (NHỎ): THÔNG TIN PHỤ & ẢNH --}}
                {{-- ==================================================== --}}
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

                            <div class="mb-3">
                                <label class="form-label fw-bold">Thương hiệu</label>
                                <input type="text" name="brand" class="form-control" 
                                       placeholder="Ví dụ: Dell, HP, TP-Link..." 
                                       value="{{ old('brand', $product->brand) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả ngắn</label>
                                <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá bán chính (VNĐ)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                                <div class="form-text text-xs text-primary">
                                    <i class="fas fa-info-circle"></i> Giá này sẽ tự động cập nhật theo giá thấp nhất của các phiên bản (nếu có).
                                </div>
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

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase shadow sticky-top" style="top: 20px; z-index: 10;">
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

    // --- SCRIPT THÊM BIẾN THỂ (Cập nhật giao diện rộng hơn) ---
    function addVariant() {
        const container = document.getElementById('variants-container');
        // Tạo index ngẫu nhiên để tránh trùng với index cũ
        const index = 'new_' + new Date().getTime(); 
        
        const html = `
            <div class="row g-2 mb-3 align-items-center variant-item border-bottom pb-2 bg-light p-2 rounded">
                <div class="col-6">
                    <input type="text" name="variants[${index}][name]" class="form-control" placeholder="Tên (VD: 2 năm)" required>
                </div>
                <div class="col-5">
                    <input type="number" name="variants[${index}][price]" class="form-control" placeholder="Giá tiền" required>
                </div>
                <div class="col-1 text-center">
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="this.closest('.variant-item').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection