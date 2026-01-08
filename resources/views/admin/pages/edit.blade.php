@extends('layouts.admin_layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Chỉnh sửa trang: {{ $page->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin chung và nội dung văn bản.</p>
        </div>
        <a href="{{ route('pages.index') }}" class="text-gray-500 hover:text-blue-600 transition">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tiêu đề trang <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ $page->title }}" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-blue-500 shadow-sm" onkeyup="ChangeToSlug()">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Đường dẫn (Slug) <span class="text-red-500">*</span></label>
                    <div class="flex bg-gray-50 border border-gray-300 rounded overflow-hidden">
                        <span class="text-gray-500 px-3 py-3 bg-gray-100 border-r border-gray-300 text-sm flex items-center">/gioi-thieu/</span>
                        <input type="text" name="slug" id="slug" value="{{ $page->slug }}" class="w-full p-3 bg-transparent focus:outline-none text-blue-600 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Nội dung văn bản chính</label>
                    <textarea name="content" id="content_editor" rows="15" class="w-full border border-gray-300 p-2 rounded">{{ $page->content }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Đây là nội dung văn bản cơ bản. Để thêm các khối phức tạp (Ảnh/Thống kê), hãy dùng menu "Quản lý Sections" bên phải.</p>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                {{-- SECTION LINK --}}
                <div class="bg-purple-50 p-5 rounded-lg border border-purple-200 shadow-sm">
                    <h4 class="font-bold text-purple-800 flex items-center mb-2">
                        <i class="fas fa-cubes mr-2"></i> Nội dung nâng cao
                    </h4>
                    <p class="text-sm text-purple-600 mb-4 leading-relaxed">
                        Quản lý các khối động như: Intro, Thống kê, Ảnh trái/phải, CTA...
                    </p>
                    <a href="{{ route('page_sections.index', $page->id) }}" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded transition shadow">
                        <i class="fas fa-layer-group mr-2"></i> Cấu hình Sections
                    </a>
                </div>

                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                    <label class="block text-gray-700 font-bold mb-2">Mô tả ngắn (Sapo)</label>
                    <textarea name="summary" rows="5" class="w-full border border-gray-300 p-3 rounded text-sm focus:border-blue-500 focus:outline-none">{{ $page->summary }}</textarea>
                </div>

                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                    <div class="mb-5">
                        <label class="block text-gray-700 font-bold mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="position" value="{{ $page->position }}" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded border border-gray-200">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="is_active" class="ml-2 text-gray-700 font-bold cursor-pointer select-none">Hiển thị trang này</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3.5 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> LƯU THAY ĐỔI
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content_editor', { height: 400 });
    // Copy function ChangeToSlug from create file here...
    function ChangeToSlug() { /* ... */ } 
</script>
@endsection