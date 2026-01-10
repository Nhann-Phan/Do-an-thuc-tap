@extends('layouts.client_layout')

@section('content')

{{-- BREADCRUMB --}}
<div class="bg-gray-50 border-b border-gray-200 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex text-sm text-gray-500 font-medium">
            <a href="/" class="hover:text-blue-600 transition flex items-center">
                <i class="fas fa-home mr-2"></i> Trang chủ
            </a>
            <span class="mx-2 text-gray-300"><i class="fa-solid fa-angle-right"></i></span>
            <span class="text-gray-900 font-semibold">Tin tức</span>
        </nav>
    </div>
</div>

{{-- NEWS LIST SECTION --}}
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        
        {{-- Header Section --}}
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 uppercase tracking-tight">Tin tức & Sự kiện</h2>
            <div class="w-16 h-1 bg-blue-600 mx-auto mt-3 rounded-full"></div>
        </div>

        @if(isset($newsList) && count($newsList) > 0)
            {{-- CONTAINER GIỚI HẠN CHIỀU RỘNG (max-w-4xl) ĐỂ KHÔNG QUÁ LỚN --}}
            <div class="max-w-5xl mx-auto space-y-6">
                
                @foreach($newsList as $item)
                <article class="flex flex-col sm:flex-row bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg hover:border-blue-300 transition-all duration-300 group h-full sm:h-48">
                    
                    {{-- ẢNH ĐẠI DIỆN (Bên trái trên Desktop, Bên trên ở Mobile) --}}
                    <a href="{{ route('client.news.detail', $item->id) }}" class="relative w-full sm:w-72 shrink-0 overflow-hidden bg-gray-100 block">
                        @if($item->image)
                            <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" 
                                 class="w-full h-48 sm:h-full object-cover transform group-hover:scale-105 transition duration-700 ease-out">
                        @else
                            <div class="flex items-center justify-center h-48 sm:h-full text-gray-300 bg-gray-50">
                                <i class="fas fa-newspaper text-4xl opacity-30"></i>
                            </div>
                        @endif
                        
                        {{-- Ngày tháng (Góc nhỏ) --}}
                        <div class="absolute top-2 left-2 bg-white/95 backdrop-blur shadow-sm px-2 py-1 rounded text-[10px] font-bold text-gray-700 border border-gray-100 z-10 text-center leading-tight">
                            <span class="block text-sm text-blue-600 font-extrabold">{{ $item->created_at->format('d') }}</span>
                            <span class="block uppercase text-gray-400">Th{{ $item->created_at->format('m') }}</span>
                        </div>
                    </a>

                    {{-- NỘI DUNG (Bên phải) --}}
                    <div class="p-5 flex flex-col flex-grow justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2 text-xs text-gray-400">
                                <span class="flex items-center"><i class="far fa-user mr-1"></i> Admin</span>
                                <span>•</span>
                                <span class="text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded">Tin tức</span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2 leading-snug group-hover:text-blue-600 transition-colors line-clamp-2">
                                <a href="{{ route('client.news.detail', $item->id) }}" title="{{ $item->title }}">
                                    {{ $item->title }}
                                </a>
                            </h3>

                            <div class="text-gray-500 text-sm line-clamp-2 leading-relaxed">
                                {!! $item->short_content ?? strip_tags(Str::limit($item->content, 180)) !!}
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach

            </div>

            {{-- PHÂN TRANG --}}
            @if($newsList->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $newsList->links('pagination::tailwind') }} 
            </div>
            @endif

        @else
            {{-- TRẠNG THÁI TRỐNG --}}
            <div class="max-w-4xl mx-auto text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-sm mb-4 text-gray-300">
                    <i class="far fa-newspaper text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Chưa có tin tức nào</h3>
                <p class="text-gray-500 text-sm mb-6">Vui lòng quay lại sau.</p>
                <a href="/" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Về trang chủ
                </a>
            </div>
        @endif

    </div>
</div>

@endsection