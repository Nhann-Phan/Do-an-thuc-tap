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
                            </div>

                            <div class="card border border-warning bg-white mb-3">
                                <div class="card-header bg-warning text-dark py-1">
                                    <small class="fw-bold"><i class="fas fa-tags me-1"></i> Các phiên bản giá</small>
                                </div>
                                <div class="card-body p-2">
                                    <div id="variants-container">
                                        {{-- HIỂN THỊ CÁC BIẾN THỂ CŨ TỪ DATABASE --}}
                                        @foreach($product->variants as $index => $variant)
                                        <div class="row g-1 mb-2 align-items-center variant-item border-bottom pb-2">
                                            {{-- Input ẩn để biết đây là update dòng cũ --}}
                                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                            
                                            <div class="col-5">
                                                <input type="text" name="variants[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $variant->name }}" placeholder="Tên" required>
                                            </div>
                                            <div class="col-5">
                                                <input type="number" name="variants[{{ $index }}][price]" class="form-control form-control-sm" value="{{ $variant->price }}" placeholder="Giá" required>
                                            </div>
                                            <div class="col-2 text-end">
                                                {{-- Checkbox xóa: Nếu tick vào thì Controller sẽ xóa --}}
                                                <div class="form-check form-check-inline m-0" title="Xóa dòng này">
                                                    <input class="form-check-input" type="checkbox" name="variants[{{ $index }}][delete]" value="1">
                                                    <label class="form-check-label text-danger fw-bold text-xs"><i class="fas fa-trash"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <button type="button" onclick="addVariant()" class="btn btn-outline-warning text-dark btn-sm w-100 border-dashed mt-2">
                                        <i class="fas fa-plus"></i> Thêm phiên bản mới
                                    </button>
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

    // --- SCRIPT THÊM BIẾN THỂ ---
    function addVariant() {
        const container = document.getElementById('variants-container');
        // Tạo index ngẫu nhiên lớn để tránh trùng với index của vòng lặp cũ
        const index = 'new_' + new Date().getTime(); 
        
        const html = `
            <div class="row g-1 mb-2 align-items-center variant-item border-bottom pb-2 bg-light">
                <div class="col-5">
                    <input type="text" name="variants[${index}][name]" class="form-control form-control-sm" placeholder="Tên mới (VD: 2 năm)" required>
                </div>
                <div class="col-5">
                    <input type="number" name="variants[${index}][price]" class="form-control form-control-sm" placeholder="Giá" required>
                </div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-sm text-danger" onclick="this.closest('.variant-item').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection