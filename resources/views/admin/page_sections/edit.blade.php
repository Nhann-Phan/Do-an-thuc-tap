@extends('layouts.admin_layout')

@section('content')
// Hide CKEditor warning notifications
<style>
    .cke_notification_warning {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }
</style>
<div class="bg-white p-6 rounded-lg shadow-md max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Chỉnh sửa khối: {{ $section->title }}</h2>
            <span class="text-xs font-bold uppercase bg-blue-100 text-blue-600 px-2 py-1 rounded">{{ $section->type }}</span>
        </div>
        <a href="{{ route('page_sections.index', $section->page_id) }}" class="text-gray-500 hover:text-blue-600">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('page_sections.update', $section->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Thông tin chung --}}
            <div class="md:col-span-1 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tiêu đề khối</label>
                    <input type="text" name="title" value="{{ $section->title }}" class="w-full border p-2 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Thứ tự hiển thị</label>
                    <input type="number" name="position" value="{{ $section->position }}" class="w-full border p-2 rounded focus:border-blue-500 outline-none">
                </div>
                <div class="bg-yellow-50 p-3 rounded text-sm text-yellow-800 border border-yellow-200">
                    <i class="fas fa-info-circle"></i> Loại khối là cố định.
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded shadow mt-4">
                    <i class="fas fa-save mr-2"></i> LƯU THAY ĐỔI
                </button>
            </div>

            {{-- Dữ liệu chi tiết --}}
            <div class="md:col-span-2 border-l pl-6">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Nội dung chi tiết</h3>

                {{-- 1. TEXT + IMAGE --}}
                @if($section->type == 'text_image')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Bố cục</label>
                            <select name="layout" class="w-full border p-2 rounded">
                                <option value="image_right" {{ ($section->data['layout'] ?? '') == 'image_right' ? 'selected' : '' }}>Ảnh bên Phải - Chữ bên Trái</option>
                                <option value="image_left" {{ ($section->data['layout'] ?? '') == 'image_left' ? 'selected' : '' }}>Ảnh bên Trái - Chữ bên Phải</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Hình ảnh hiện tại</label>
                            @if(!empty($section->data['image']))
                                <img src="{{ asset($section->data['image']) }}" class="h-32 object-cover rounded border mb-2">
                            @endif
                            <input type="file" name="image_file" class="w-full text-sm border p-1 rounded bg-gray-50 mt-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Nội dung chữ</label>
                            <textarea name="content_text" rows="6" class="w-full border p-2 rounded">{{ $section->data['content'] ?? '' }}</textarea>
                        </div>
                    </div>

                {{-- 2. STATS --}}
                @elseif($section->type == 'stats')
                    <div class="space-y-3">
                        @php $stats = $section->data['stats'] ?? []; @endphp
                        @for($i=0; $i<4; $i++)
                            <div class="flex gap-3">
                                <div class="w-1/2">
                                    <label class="text-xs text-gray-400">Con số {{ $i+1 }}</label>
                                    <input type="text" name="stat_number[]" value="{{ $stats[$i]['number'] ?? '' }}" class="w-full border p-2 rounded">
                                </div>
                                <div class="w-1/2">
                                    <label class="text-xs text-gray-400">Nhãn {{ $i+1 }}</label>
                                    <input type="text" name="stat_label[]" value="{{ $stats[$i]['label'] ?? '' }}" class="w-full border p-2 rounded">
                                </div>
                            </div>
                        @endfor
                    </div>

                {{-- 3. CTA --}}
                @elseif($section->type == 'cta')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Mô tả phụ</label>
                            <textarea name="cta_subtext" rows="3" class="w-full border p-2 rounded">{{ $section->data['subtext'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Chữ trên nút</label>
                            <input type="text" name="cta_btn_text" value="{{ $section->data['button_text'] ?? '' }}" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Link nút bấm</label>
                            <input type="text" name="cta_btn_link" value="{{ $section->data['button_link'] ?? '' }}" class="w-full border p-2 rounded">
                        </div>
                    </div>

                {{-- 4. INTRO (GIỚI THIỆU CÔNG TY) - ĐÃ THÊM MỚI --}}
                @elseif($section->type == 'intro')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nội dung giới thiệu (Cột trái)</label>
                            <textarea name="intro_content" rows="6" class="w-full border p-2 rounded">{{ $section->data['content'] ?? '' }}</textarea>
                        </div>
                        <div class="bg-blue-50 p-4 rounded border border-blue-100">
                            <h4 class="font-bold text-blue-800 mb-3 border-b pb-2">Cấu hình Card</h4>
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Logo</label>
                                @if(!empty($section->data['image']))
                                    <img src="{{ asset($section->data['image']) }}" class="h-12 mb-2 object-contain bg-white border p-1 rounded">
                                @endif
                                <input type="file" name="intro_logo" class="w-full text-sm border p-1 rounded bg-white">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Slogan</label>
                                <input type="text" name="intro_slogan" value="{{ $section->data['slogan'] ?? '' }}" class="w-full border p-2 rounded">
                            </div>
                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium mb-1">Chữ nút</label>
                                    <input type="text" name="intro_btn_text" value="{{ $section->data['button_text'] ?? '' }}" class="w-full border p-2 rounded">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium mb-1">Link nút</label>
                                    <input type="text" name="intro_btn_link" value="{{ $section->data['button_link'] ?? '' }}" class="w-full border p-2 rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection