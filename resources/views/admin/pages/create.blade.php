@extends('layouts.admin_layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Thêm trang giới thiệu mới</h2>
            <p class="text-sm text-gray-500 mt-1">Tạo trang mới trước, sau đó bạn có thể thêm các khối nội dung chi tiết.</p>
        </div>
        <a href="{{ route('pages.index') }}" class="text-gray-500 hover:text-blue-600 transition">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-sm">
            <strong class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Có lỗi xảy ra:</strong>
            <ul class="list-disc pl-5 mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pages.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- CỘT TRÁI: NỘI DUNG CHÍNH --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Tiêu đề --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tiêu đề trang <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:border-blue-500 shadow-sm" placeholder="VD: Về chúng tôi" onkeyup="ChangeToSlug()">
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Đường dẫn (Slug) <span class="text-red-500">*</span></label>
                    <div class="flex bg-gray-50 border border-gray-300 rounded overflow-hidden">
                        <span class="text-gray-500 px-3 py-3 bg-gray-100 border-r border-gray-300 text-sm flex items-center">/gioi-thieu/</span>
                        <input type="text" name="slug" id="slug" class="w-full p-3 bg-transparent focus:outline-none text-blue-600 font-medium" placeholder="ve-chung-toi">
                    </div>
                </div>

                {{-- CKEditor --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Nội dung văn bản chính <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content_editor" rows="15" class="w-full border border-gray-300 p-2 rounded"></textarea>
                    <p class="text-xs text-gray-500 mt-2 italic">* Đây là nội dung văn bản cơ bản. Sau khi lưu, bạn có thể thêm các khối nâng cao (Thống kê, Ảnh, CTA...) ở trang chỉnh sửa.</p>
                </div>
            </div>

            {{-- CỘT PHẢI: CẤU HÌNH --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- INFO BOX (Hướng dẫn) --}}
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i> <strong>Lưu ý:</strong>
                    <p class="mt-1">Sau khi bấm <b>"LƯU TRANG"</b>, bạn sẽ được chuyển về danh sách. Tại đó, bấm vào nút <span class="font-bold text-purple-600"><i class="fas fa-layer-group"></i> Sections</span> để cấu hình chi tiết các khối nội dung.</p>
                </div>

                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                    <label class="block text-gray-700 font-bold mb-2">Mô tả ngắn (Sapo)</label>
                    <textarea name="summary" rows="5" class="w-full border border-gray-300 p-3 rounded text-sm focus:border-blue-500 focus:outline-none" placeholder="Mô tả ngắn gọn về trang này (Tốt cho SEO)..."></textarea>
                </div>

                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                    <div class="mb-5">
                        <label class="block text-gray-700 font-bold mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="position" value="0" class="w-full border border-gray-300 p-2 rounded">
                        <p class="text-xs text-gray-400 mt-1">Số nhỏ hiển thị trước.</p>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded border border-gray-200">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="is_active" class="ml-2 text-gray-700 font-bold cursor-pointer select-none">Hiển thị ngay</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> LƯU TRANG
                </button>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    // 1. Kích hoạt CKEditor
    CKEDITOR.replace('content_editor', {
        height: 300
    });

    // 2. Hàm tạo Slug tự động (Full Code)
    function ChangeToSlug() {
        var title, slug;
        title = document.getElementById("title").value;
        slug = title.toLowerCase();
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        slug = slug.replace(/ /gi, "-");
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        document.getElementById('slug').value = slug;
    }
</script>
@endsection