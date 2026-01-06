@extends('layouts.admin_layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý nội dung: {{ $page->title }}</h2>
            <a href="{{ route('pages.index') }}" class="text-sm text-blue-600 hover:underline"><i class="fas fa-arrow-left"></i> Quay lại danh sách trang</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- CỘT TRÁI: DANH SÁCH CÁC KHỐI ĐÃ TẠO --}}
        <div class="lg:col-span-2 space-y-4">
            <h3 class="font-bold text-gray-700 uppercase text-sm border-b pb-2">Danh sách các khối hiển thị</h3>
            
            @if(isset($sections) && $sections->count() > 0)
                @foreach($sections as $section)
                    <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500 flex justify-between items-center">
                        <div>
                            <div class="text-xs text-gray-400 font-bold uppercase">{{ $section->type }}</div>
                            <h4 class="font-bold text-lg text-gray-800">{{ $section->title ?? '(Không tiêu đề)' }}</h4>
                            <div class="text-sm text-gray-500">Thứ tự: {{ $section->position }}</div>
                        </div>
                        <div>
                            {{-- NÚT SỬA --}}
                            <a href="{{ route('page_sections.edit', $section->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded mr-1 inline-block" title="Sửa khối này">
                                <i class="fas fa-pen"></i>
                            </a>

                            {{-- NÚT XÓA --}}
                            <form action="{{ route('page_sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Xóa khối này?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-8 bg-gray-50 rounded border border-dashed border-gray-300 text-gray-500">
                    <i class="fas fa-box-open text-3xl mb-2 text-gray-300"></i><br>
                    Chưa có khối nội dung nào. Hãy thêm ở cột bên phải.
                </div>
            @endif
        </div>

        {{-- CỘT PHẢI: FORM THÊM MỚI --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md sticky top-6 border border-gray-100">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plus-circle text-blue-600 mr-2"></i> Thêm khối mới
                </h3>

                <form action="{{ route('page_sections.store', $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- 1. Chọn loại khối --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Loại khối (Type)</label>
                        <select name="type" id="section_type" class="w-full border p-2 rounded bg-blue-50 focus:outline-none focus:border-blue-500" onchange="toggleFields()">
                            <option value="text_image">Text + Hình ảnh</option>
                            <option value="stats">Thống kê (Số liệu)</option>
                            <option value="cta">Kêu gọi hành động (CTA)</option>
                        </select>
                    </div>

                    {{-- 2. Tiêu đề chung --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tiêu đề khối</label>
                        <input type="text" name="title" class="w-full border p-2 rounded focus:outline-none focus:border-blue-500" placeholder="VD: Tầm nhìn...">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Thứ tự hiển thị</label>
                        <input type="number" name="position" value="0" class="w-full border p-2 rounded focus:outline-none focus:border-blue-500">
                    </div>

                    <hr class="my-4 border-gray-200">

                    {{-- === CÁC FIELD RIÊNG CHO TỪNG LOẠI === --}}

                    {{-- A. Text + Image Fields --}}
                    <div id="fields_text_image" class="section-fields">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Bố cục ảnh</label>
                            <select name="layout" class="w-full border p-2 rounded">
                                <option value="image_right">Ảnh bên Phải - Chữ bên Trái</option>
                                <option value="image_left">Ảnh bên Trái - Chữ bên Phải</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Hình ảnh</label>
                            <input type="file" name="image_file" class="w-full text-sm border p-1 rounded">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Nội dung chữ</label>
                            <textarea name="content_text" rows="4" class="w-full border p-2 rounded focus:outline-none focus:border-blue-500"></textarea>
                        </div>
                    </div>

                    {{-- B. Stats Fields (Nhập 4 số liệu) --}}
                    <div id="fields_stats" class="section-fields hidden">
                        <p class="text-xs text-gray-500 mb-2">Nhập tối đa 4 chỉ số thống kê.</p>
                        @for($i=1; $i<=4; $i++)
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="stat_number[]" placeholder="Số (VD: 50+)" class="w-1/2 border p-2 rounded text-sm focus:outline-none focus:border-blue-500">
                            <input type="text" name="stat_label[]" placeholder="Nhãn (VD: Nhân sự)" class="w-1/2 border p-2 rounded text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        @endfor
                    </div>

                    {{-- C. CTA Fields --}}
                    <div id="fields_cta" class="section-fields hidden">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Mô tả phụ (Subtext)</label>
                            <textarea name="cta_subtext" rows="2" class="w-full border p-2 rounded focus:outline-none focus:border-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Chữ trên nút</label>
                            <input type="text" name="cta_btn_text" class="w-full border p-2 rounded focus:outline-none focus:border-blue-500" placeholder="VD: Liên hệ ngay">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Link nút bấm</label>
                            <input type="text" name="cta_btn_link" class="w-full border p-2 rounded focus:outline-none focus:border-blue-500" placeholder="VD: /lien-he">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition shadow-md">
                        <i class="fas fa-plus mr-1"></i> THÊM KHỐI
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT CHUYỂN ĐỔI FORM --}}
<script>
    function toggleFields() {
        // 1. Ẩn tất cả các vùng nhập liệu riêng
        document.querySelectorAll('.section-fields').forEach(el => el.classList.add('hidden'));
        
        // 2. Lấy giá trị đang chọn
        let type = document.getElementById('section_type').value;
        
        // 3. Hiện vùng tương ứng
        let selectedDiv = document.getElementById('fields_' + type);
        if(selectedDiv) {
            selectedDiv.classList.remove('hidden');
        }
    }
</script>
@endsection