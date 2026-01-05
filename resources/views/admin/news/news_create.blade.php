@extends('layouts.admin_layout')

@section('content')

{{-- CSS FIX LỖI HIỂN THỊ CKEDITOR NOT SECURE --}}
<style>
    .cke_notification_warning { display: none !important; }
    /* Custom Toggle Switch for Tailwind */
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
    <h3 class="text-2xl font-bold text-gray-800">Đăng Tin Tức Mới</h3>
    
    <a href="{{ route('news.index_admin') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6">
        
        {{-- ALERTS --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <div><span class="font-bold">Thành công!</span> {{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- CỘT TRÁI (Nội dung chính) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tiêu đề tin <span class="text-red-500">*</span></label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required placeholder="Nhập tiêu đề bài viết...">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả ngắn (Summary)</label>
                        <textarea name="summary" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" rows="3" placeholder="Đoạn văn ngắn hiển thị dưới ảnh..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nội dung chi tiết</label>
                        <textarea name="content" id="content" class="w-full border border-gray-300 rounded-lg"></textarea>
                    </div>
                </div>
                
                {{-- CỘT PHẢI (Thông tin phụ) --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 mb-6">
                        
                        {{-- Ảnh đại diện --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Hình ảnh đại diện</label>
                            <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2 cursor-pointer" accept="image/*" onchange="previewImage(this)">
                            
                            <div class="mt-2 relative rounded-lg border border-gray-300 bg-white h-48 flex items-center justify-center overflow-hidden">
                                <img id="preview" src="#" class="max-h-full max-w-full object-contain hidden">
                                <div id="placeholder-text" class="text-gray-400 flex flex-col items-center">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <span class="text-xs">Chưa chọn ảnh</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="border-gray-200 my-4">

                        {{-- Toggle Switch --}}
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-gray-700 cursor-pointer" for="activeSwitch">Hiển thị ngay</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="activeSwitch" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95 uppercase flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i> Đăng bài viết
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', { height: 400 });
    
    function previewImage(input) {
        var preview = document.getElementById('preview');
        var placeholder = document.getElementById('placeholder-text');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { 
                preview.src = e.target.result; 
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection