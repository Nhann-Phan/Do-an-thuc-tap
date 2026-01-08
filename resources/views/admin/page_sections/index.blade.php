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
        
        {{-- CỘT TRÁI: DANH SÁCH KHỐI --}}
        <div class="lg:col-span-2 space-y-4">
            <h3 class="font-bold text-gray-700 uppercase text-sm border-b pb-2">Danh sách các khối hiển thị</h3>
            
            @if(isset($sections) && $sections->count() > 0)
                @foreach($sections as $section)
                    <div class="relative group">
                        {{-- THẺ LINK BAO TRỌN KHỐI (CLICK ĐỂ SỬA) --}}
                        <a href="{{ route('page_sections.edit', $section->id) }}" class="block bg-white p-4 rounded-lg shadow border border-gray-200 hover:border-blue-500 hover:shadow-md transition cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs text-gray-400 font-bold uppercase mb-1">{{ $section->type }}</div>
                                    <h4 class="font-bold text-lg text-gray-800">{{ $section->title ?? '(Không tiêu đề)' }}</h4>
                                    <div class="text-sm text-gray-500 mt-1">Thứ tự hiển thị: <span class="font-bold">{{ $section->position }}</span></div>
                                </div>
                            </div>
                        </a>

                        {{-- NÚT XÓA (Căn giữa theo chiều dọc) --}}
                        <div class="absolute top-1/2 right-4 z-10 -translate-y-1/2">
                            <form action="{{ route('page_sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Xóa khối này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition" title="Xóa">
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
                            <option value="intro">Giới thiệu công ty (Intro)</option>
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

                    {{-- === CÁC FIELD NHẬP LIỆU === --}}
                    {{-- A. Text + Image --}}
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

                    {{-- B. Stats --}}
                    <div id="fields_stats" class="section-fields hidden">
                        <p class="text-xs text-gray-500 mb-2">Nhập tối đa 4 chỉ số thống kê.</p>
                        @for($i=1; $i<=4; $i++)
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="stat_number[]" placeholder="Số (VD: 50+)" class="w-1/2 border p-2 rounded text-sm">
                            <input type="text" name="stat_label[]" placeholder="Nhãn (VD: Nhân sự)" class="w-1/2 border p-2 rounded text-sm">
                        </div>
                        @endfor
                    </div>

                    {{-- C. CTA --}}
                    <div id="fields_cta" class="section-fields hidden">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Mô tả phụ</label>
                            <textarea name="cta_subtext" rows="2" class="w-full border p-2 rounded"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Chữ trên nút</label>
                            <input type="text" name="cta_btn_text" class="w-full border p-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Link nút</label>
                            <input type="text" name="cta_btn_link" class="w-full border p-2 rounded">
                        </div>
                    </div>

                    {{-- D. Intro (Mới) --}}
                    <div id="fields_intro" class="section-fields hidden">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Nội dung giới thiệu</label>
                            <textarea name="intro_content" rows="5" class="w-full border p-2 rounded"></textarea>
                        </div>
                        <div class="bg-blue-50 p-3 rounded border border-blue-100">
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Logo</label>
                                <input type="file" name="intro_logo" class="w-full text-sm border p-1 rounded bg-white">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Slogan</label>
                                <input type="text" name="intro_slogan" class="w-full border p-2 rounded">
                            </div>
                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium mb-1">Nút</label>
                                    <input type="text" name="intro_btn_text" class="w-full border p-2 rounded">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium mb-1">Link</label>
                                    <input type="text" name="intro_btn_link" class="w-full border p-2 rounded">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition shadow-md mt-4">
                        <i class="fas fa-plus mr-1"></i> THÊM KHỐI
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFields() {
        document.querySelectorAll('.section-fields').forEach(el => el.classList.add('hidden'));
        let type = document.getElementById('section_type').value;
        let selectedDiv = document.getElementById('fields_' + type);
        if(selectedDiv) selectedDiv.classList.remove('hidden');
    }
</script>
@endsection