@extends('layouts.admin_layout')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 m-0">Quản lý Tin Tức</h3>
        <p class="text-sm text-gray-500 mt-1">Danh sách bài viết, tin tức sự kiện</p>
    </div>
    
    <a href="{{ route('news.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition text-sm">
        <i class="fas fa-plus mr-2"></i> Viết bài mới
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200">
                    <th class="px-6 py-3 whitespace-nowrap w-16 text-center">#</th>
                    <th class="px-6 py-3 whitespace-nowrap w-24 text-center">Ảnh</th>
                    <th class="px-6 py-3">Tiêu đề & Tóm tắt</th>
                    <th class="px-6 py-3 whitespace-nowrap text-center">Trạng thái</th>
                    <th class="px-6 py-3 whitespace-nowrap">Ngày đăng</th>
                    <th class="px-6 py-3 whitespace-nowrap text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                {{-- KIỂM TRA DỮ LIỆU CÓ TỒN TẠI KHÔNG --}}
                @if($newsList->count() > 0)
                    
                    {{-- VÒNG LẶP FOREACH --}}
                    @foreach($newsList as $news)
                    <tr class="hover:bg-blue-50/50 transition duration-150 cursor-pointer group" 
                        onclick="window.location='{{ route('news.edit', $news->id) }}'" 
                        title="Nhấn để chỉnh sửa">
                        
                        <td class="px-6 py-4 text-center text-gray-400 font-medium">
                            {{ $loop->iteration }}
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <div class="w-16 h-12 rounded bg-gray-100 border border-gray-200 overflow-hidden mx-auto flex items-center justify-center">
                                @if($news->image)
                                    <img src="{{ asset($news->image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">No Img</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <h6 class="font-bold text-gray-900 group-hover:text-blue-600 transition mb-1 text-base line-clamp-1">{{ $news->title }}</h6>
                            <p class="text-gray-500 text-xs line-clamp-2 max-w-md leading-relaxed">
                                {{ $news->summary }}
                            </p>
                        </td>
                        
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($news->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    Ẩn
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-xs">
                            {{ $news->created_at->format('d/m/Y') }}
                        </td>
                        
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <form action="{{ route('news.destroy', $news->id) }}" method="POST" class="inline-block relative z-10">
                                @csrf @method('DELETE')
                                <button onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa bài này?');" 
                                        class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg transition shadow-sm" 
                                        title="Xóa">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    {{-- KẾT THÚC VÒNG LẶP --}}

                @else
                    {{-- TRƯỜNG HỢP KHÔNG CÓ DỮ LIỆU (ELSE) --}}
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="far fa-newspaper text-4xl mb-3 opacity-30"></i>
                                <p class="text-sm italic">Chưa có bài viết nào.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
@if($newsList->hasPages())
<div class="px-6 py-4 mt-4 bg-white rounded-xl shadow-sm border border-gray-100 flex justify-center">
    {{ $newsList->links('pagination::tailwind') }}
</div>
@endif

@endsection