@extends('layouts.admin_layout')

@section('content')

<style>
    .cke_notification_warning { display: none !important; }
    /* Toggle Switch Custom CSS */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #2563eb;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #2563eb;
    }
</style>

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h3 class="text-2xl font-bold text-gray-800">Cập nhật sản phẩm</h3>
    
    <a href="{{ route('product.index_admin') }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    
    {{-- Card Header --}}
    <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-100 text-yellow-800 font-bold flex items-center">
        <i class="fas fa-edit mr-2"></i> Đang chỉnh sửa: {{ $product->name }}
    </div>
    
    <div class="p-6">
        {{-- ALERTS --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <div><span class="font-bold">Thành công!</span> {{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- CỘT TRÁI (LỚN): TÊN, MÔ TẢ & BIẾN THỂ --}}
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- PHẦN BIẾN THỂ (VARIANTS) --}}
                    <div class="border border-yellow-200 rounded-xl overflow-hidden mt-6">
                        <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100 flex justify-between items-center">
                            <span class="font-bold text-yellow-800 flex items-center">
                                <i class="fas fa-tags mr-2"></i> Các phiên bản & Tồn kho
                            </span>
                            <small class="text-gray-600 italic text-xs hidden sm:block">Lưu ý: Nếu xóa, dữ liệu tồn kho sẽ mất.</small>
                        </div>
                        
                        <div class="p-4 bg-white">
                            {{-- Header của bảng biến thể --}}
                            {{-- Cập nhật cột grid: 4 (Tên) - 4 (Giá) - 3 (SL) - 1 (Xóa) --}}
                            <div class="grid grid-cols-12 gap-2 mb-2 font-bold text-gray-500 text-xs uppercase border-b border-gray-100 pb-2 text-center">
                                <div class="col-span-4 text-left">Tên phiên bản</div>
                                <div class="col-span-4">Giá tiền (VNĐ)</div>
                                <div class="col-span-3">Số lượng</div>
                                <div class="col-span-1">Xóa</div>
                            </div>

                            <div id="variants-container" class="space-y-3">
                                {{-- HIỂN THỊ CÁC BIẾN THỂ CŨ TỪ DATABASE --}}
                                @foreach($product->variants as $index => $variant)
                                <div class="grid grid-cols-12 gap-2 items-center variant-item bg-gray-50 p-2 rounded-lg border border-gray-100">
                                    {{-- Input ẩn ID --}}
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    
                                    {{-- Cột Tên --}}
                                    <div class="col-span-4">
                                        <input type="text" name="variants[{{ $index }}][name]" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 outline-none" value="{{ $variant->name }}" placeholder="Tên" required>
                                    </div>
                                    
                                    {{-- Cột Giá --}}
                                    <div class="col-span-4">
                                        <input type="number" name="variants[{{ $index }}][price]" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 outline-none" value="{{ $variant->price }}" placeholder="Giá" required>
                                    </div>

                                    {{-- Cột Số lượng (MỚI) --}}
                                    <div class="col-span-3">
                                        <input type="number" name="variants[{{ $index }}][quantity]" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 outline-none text-center" value="{{ $variant->quantity }}" placeholder="SL">
                                    </div>

                                    {{-- Cột Xóa --}}
                                    <div class="col-span-1 flex justify-center">
                                        <div class="flex items-center justify-center" title="Tick vào đây để xóa dòng này khi Lưu">
                                            <input type="checkbox" name="variants[{{ $index }}][delete]" value="1" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" onclick="addVariant()" class="mt-4 w-full py-2 border-2 border-dashed border-yellow-300 text-yellow-700 rounded-lg hover:bg-yellow-50 transition text-sm font-bold flex items-center justify-center">
                                <i class="fas fa-plus mr-1"></i> Thêm phiên bản mới
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI (NHỎ): THÔNG TIN PHỤ & ẢNH --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 mb-6 sticky top-24">
                        
                        {{-- Danh mục --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Danh mục <span class="text-red-500">*</span></label>
                            <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none bg-white" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Thương hiệu</label>
                            <input type="text" name="brand" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none" placeholder="Ví dụ: Dell..." value="{{ old('brand', $product->brand) }}">
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mô tả ngắn</label>
                            <textarea name="short_description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>

                        {{-- Giá bán --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Giá bán chính (VNĐ)</label>
                            <input type="number" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none font-bold text-gray-800" value="{{ old('price', $product->price) }}">
                            <p class="text-xs text-blue-600 mt-1 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i> Giá này sẽ tự động cập nhật.
                            </p>
                        </div>

                        {{-- Ảnh sản phẩm --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ảnh sản phẩm</label>
                            <input type="file" name="image" id="imageInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 mb-2" accept="image/*" onchange="previewImage(this)">
                            
                            <div class="p-2 border border-gray-200 bg-white rounded-lg flex flex-col items-center justify-center min-h-[150px]">
                                <img id="preview" 
                                     src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/150?text=No+Img' }}" 
                                     class="max-h-[200px] rounded object-contain"
                                     onerror="this.src='https://via.placeholder.com/150?text=Lỗi+Ảnh'">
                                
                                <span id="placeholder-text" class="text-gray-400 text-xs italic mt-2">
                                    {{ $product->image ? 'Ảnh hiện tại' : 'Chưa có ảnh' }}
                                </span>
                            </div>
                        </div>

                        <hr class="border-gray-200 my-4">

                        {{-- Switches --}}
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-bold text-gray-700 cursor-pointer" for="activeSwitch">Hiển thị</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="activeSwitch" class="sr-only peer" {{ $product->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between mb-6">
                            <label class="text-sm font-bold text-red-500 cursor-pointer" for="hotSwitch">Sản phẩm HOT</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_hot" id="hotSwitch" class="sr-only peer" {{ $product->is_hot ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95 uppercase flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i> Lưu thay đổi
                        </button>
                    </div>
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
                placeholder.classList.add('text-green-600', 'font-bold'); 
                placeholder.classList.remove('text-gray-400', 'italic');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- SCRIPT THÊM BIẾN THỂ (ĐÃ CẬP NHẬT THÊM Ô SỐ LƯỢNG) ---
    function addVariant() {
        const container = document.getElementById('variants-container');
        // Tạo index ngẫu nhiên để tránh trùng với index cũ trong DB
        const index = 'new_' + new Date().getTime(); 
        
        const html = `
            <div class="grid grid-cols-12 gap-2 items-center variant-item bg-blue-50 p-2 rounded-lg border border-blue-100">
                <div class="col-span-4">
                    <input type="text" name="variants[${index}][name]" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Tên (VD: 2 năm)" required>
                </div>
                <div class="col-span-4">
                    <input type="number" name="variants[${index}][price]" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Giá tiền" required>
                </div>
                
                {{-- MỚI: Cột số lượng cho item thêm bằng JS --}}
                <div class="col-span-3">
                    <input type="number" name="variants[${index}][quantity]" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-center" placeholder="SL" value="0">
                </div>

                <div class="col-span-1 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700 transition p-1" onclick="this.closest('.variant-item').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection