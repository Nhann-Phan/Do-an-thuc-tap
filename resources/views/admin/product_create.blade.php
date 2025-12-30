@extends('layouts.admin_layout')

@section('content')

<style>
    .cke_notification_warning { display: none !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary">
        @if($selectedCategoryId)
            {{-- Hiển thị tên danh mục đang nhập liệu --}}
            @php $catName = $categories->find($selectedCategoryId)->name ?? 'Mới'; @endphp
            Bảng nhập liệu: <span class="text-primary">{{ $catName }}</span>
        @else
            Thêm sản phẩm mới
        @endif
    </h3>
    
    <a href="{{ $selectedCategoryId ? route('admin.category.products', $selectedCategoryId) : route('product.index_admin') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-list me-2"></i> Xem danh sách đã nhập
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fas fa-check-circle me-2 text-xl"></i>
                <div>
                    <strong>Thành công!</strong> {{ session('success') }}
                </div>
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

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Nhập tên sản phẩm tiếp theo..." value="{{ old('name') }}" autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả chi tiết</label>
                        <textarea name="description" id="description" class="form-control" rows="10">{{ old('description') }}</textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" 
                                            {{-- Logic: ID trên URL khớp ID danh mục -> Selected --}}
                                            {{ (isset($selectedCategoryId) && $selectedCategoryId == $cat->id) || old('category_id') == $cat->id ? 'selected' : '' }}>
                                            
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                <input type="number" name="price" class="form-control" placeholder="0" value="{{ old('price') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" name="image" id="imageInput" class="form-control mb-2" accept="image/*" onchange="previewImage(this)">
                                <div class="p-2 border bg-white rounded text-center" style="min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <img id="preview" src="#" class="img-fluid rounded" style="max-height: 200px; display: none;">
                                    <span id="placeholder-text" class="text-muted small">Chưa chọn ảnh</span>
                                </div>
                            </div>

                            <hr>

                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked>
                                <label class="form-check-label fw-bold" for="activeSwitch">Hiển thị ngay</label>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_hot" id="hotSwitch">
                                <label class="form-check-label text-danger fw-bold" for="hotSwitch">Sản phẩm HOT</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold text-uppercase shadow">
                        <i class="fas fa-plus-circle me-1"></i> Lưu & Nhập tiếp
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
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }
</script>
@endsection