@extends('layouts.client_layout')

@section('content')

<div class="bg-gray-50 py-8 border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 uppercase tracking-wide">Tin Tức Công Nghệ</h1>
                <p class="text-gray-500 text-sm mt-2">Cập nhật xu hướng, giải pháp và thông tin mới nhất từ GPM</p>
            </div>
            <div class="mt-4 md:mt-0 text-sm text-gray-500">
                <a href="/" class="hover:text-blue-600 transition"><i class="fas fa-home"></i> Trang chủ</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-gray-900 font-medium">Tin tức</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        
        @if(isset($newsList) && count($newsList) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @foreach($newsList as $item)
                <article class="flex flex-col h-full bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                    
                    {{-- ẢNH ĐẠI DIỆN --}}
                    <a href="{{ route('news.detail', $item->id) }}" class="relative overflow-hidden aspect-video bg-gray-100 block">
                        @if($item->image)
                            <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300">
                                <i class="fas fa-newspaper text-4xl"></i>
                            </div>
                        @endif
                        
                        {{-- Ngày tháng overlay --}}
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded text-xs font-bold text-gray-700 shadow-sm">
                            {{ $item->created_at->format('d') }} Th{{ $item->created_at->format('m, Y') }}
                        </div>
                    </a>

                    {{-- NỘI DUNG --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                Tin tức
                            </span>
                        </div>

                        <h2 class="text-lg font-bold text-gray-800 mb-3 leading-snug group-hover:text-blue-600 transition line-clamp-2">
                            <a href="{{ route('news.detail', $item->id) }}">{{ $item->title }}</a>
                        </h2>

                        <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-grow">
                            {{ $item->summary ?? strip_tags(Str::limit($item->content, 150)) }}
                        </p>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <span class="text-xs text-gray-400">
                                <i class="far fa-user mr-1"></i> Admin
                            </span>
                            <a href="{{ route('news.detail', $item->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center transition">
                                Xem thêm <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach

            </div>

            {{-- PHÂN TRANG (Pagination) --}}
            <div class="mt-12 flex justify-center">
                {{-- Nếu bạn dùng Tailwind Pagination có sẵn của Laravel --}}
                {{ $newsList->links('pagination::tailwind') }}
                
                {{-- Hoặc nếu chưa publish vendor pagination, dùng simple-tailwind --}}
                {{-- {{ $newsList->links() }} --}}
            </div>

        @else
            <div class="text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <i class="far fa-newspaper text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Chưa có bài viết nào</h3>
                <p class="text-gray-500 mt-1">Vui lòng quay lại sau.</p>
                <a href="/" class="inline-block mt-6 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Về trang chủ</a>
            </div>
        @endif

    </div>
</div>

@endsection