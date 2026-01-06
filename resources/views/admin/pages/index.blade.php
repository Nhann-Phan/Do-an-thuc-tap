@extends('layouts.admin_layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Trang Giới Thiệu</h2>
        <a href="{{ route('pages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center transition">
            <i class="fas fa-plus mr-2"></i> Thêm trang mới
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">STT</th>
                    <th class="py-3 px-6 text-left">Tiêu đề</th>
                    <th class="py-3 px-6 text-left">Đường dẫn (Slug)</th>
                    <th class="py-3 px-6 text-center">Thứ tự</th>
                    <th class="py-3 px-6 text-center">Trạng thái</th>
                    <th class="py-3 px-6 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($pages as $key => $page)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6 text-left whitespace-nowrap font-bold">{{ $key + 1 }}</td>
                    <td class="py-3 px-6 text-left font-medium text-gray-800">{{ $page->title }}</td>
                    <td class="py-3 px-6 text-left italic text-gray-500">{{ $page->slug }}</td>
                    <td class="py-3 px-6 text-center">{{ $page->position }}</td>
                    <td class="py-3 px-6 text-center">
                        @if($page->is_active)
                            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs font-bold">Hiển thị</span>
                        @else
                            <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs font-bold">Ẩn</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center space-x-2">
                            
                            {{-- 1. Sửa thông tin chung --}}
                            <a href="{{ route('pages.edit', $page->id) }}" class="w-8 h-8 rounded bg-yellow-100 text-yellow-600 flex items-center justify-center hover:bg-yellow-200 transition" title="Sửa thông tin">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- 2. NÚT MỚI: Cấu hình Sections (Nội dung chi tiết) --}}
                            <a href="{{ route('page_sections.index', $page->id) }}" class="w-8 h-8 rounded bg-purple-100 text-purple-600 flex items-center justify-center hover:bg-purple-200 transition" title="Quản lý nội dung chi tiết (Sections)">
                                <i class="fas fa-layer-group"></i>
                            </a>

                            {{-- 3. Xóa trang --}}
                            <form action="{{ route('pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection