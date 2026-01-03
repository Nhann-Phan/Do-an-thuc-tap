@extends('layouts.client_layout')

@section('content')

<div class="bg-gray-100 py-3 border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="text-sm text-gray-500 flex items-center gap-2 overflow-hidden whitespace-nowrap">
            <a href="/" class="hover:text-blue-600 transition"><i class="fas fa-home"></i> Trang chủ</a>
            <i class="fas fa-angle-right text-xs text-gray-400"></i>
            <span class="text-gray-700">Tin tức</span>
            <i class="fas fa-angle-right text-xs text-gray-400"></i>
            <span class="text-gray-900 font-medium truncate">{{ $news->title }}</span>
        </div>
    </div>
</div>

<div class="bg-white py-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-8">
                <article>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                        {{ $news->title }}
                    </h1>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-6 pb-4 border-b border-gray-100">
                        <span class="flex items-center mr-6">
                            <i class="far fa-calendar-alt mr-2 text-blue-600"></i> 
                            {{ $news->created_at->format('d/m/Y i:H') }}
                        </span>
                        <span class="flex items-center">
                            <i class="far fa-user mr-2 text-blue-600"></i> 
                            Đăng bởi: Admin
                        </span>
                    </div>

                    @if($news->summary)
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-8 text-gray-700 font-medium italic text-lg leading-relaxed rounded-r-md">
                        {{ $news->summary }}
                    </div>
                    @endif

                    <div class="content-body text-gray-800 text-lg leading-relaxed space-y-4">
                        {!! $news->content !!}
                    </div>
                </article>

                <div class="mt-10 pt-6 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm font-bold text-gray-700 uppercase">
                        Chia sẻ bài viết:
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-blue-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-700 transition shadow-sm"><i class="fab fa-facebook-f"></i></button>
                        <button class="bg-sky-500 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-sky-600 transition shadow-sm"><i class="fab fa-twitter"></i></button>
                        <button class="bg-red-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-red-700 transition shadow-sm"><i class="fab fa-youtube"></i></button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-24">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-bold text-gray-800 uppercase text-sm border-l-4 border-blue-600 pl-3">Tin tức mới nhất</h3>
                        </div>
                        
                        <div class="divide-y divide-gray-100">
                            @forelse($relatedNews as $item)
                            <a href="{{ route('news.detail', $item->id) }}" class="flex gap-3 p-4 hover:bg-gray-50 transition group">
                                <div class="w-20 h-16 flex-shrink-0 rounded overflow-hidden bg-gray-200 border border-gray-200 relative">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="w-full h-full object-cover absolute inset-0 group-hover:scale-110 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 absolute inset-0">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 line-clamp-2 leading-snug mb-1">
                                        {{ $item->title }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500">
                                        <i class="far fa-clock mr-1"></i> {{ $item->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </a>
                            @empty
                            <div class="p-6 text-center text-gray-500 italic text-sm">
                                Chưa có tin tức liên quan nào.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* FORMAT NỘI DUNG TỪ CKEDITOR */
    .content-body h2 { font-size: 1.5rem; font-weight: bold; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1e40af; }
    .content-body h3 { font-size: 1.25rem; font-weight: bold; margin-top: 1.25rem; margin-bottom: 0.5rem; color: #374151; }
    .content-body p { margin-bottom: 1rem; line-height: 1.8; text-align: justify; }
    .content-body ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
    .content-body ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
    
    /* Ảnh trong bài viết tự động scale và căn giữa */
    .content-body img { 
        max-width: 100%; 
        height: auto !important; 
        border-radius: 8px; 
        margin: 1.5rem auto; 
        display: block; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
    }
    
    /* Caption ảnh (nếu có) */
    .content-body figcaption {
        text-align: center;
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: -1rem;
        margin-bottom: 1.5rem;
        font-style: italic;
    }

    /* Trích dẫn */
    .content-body blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
        font-style: italic;
        color: #4b5563;
        background-color: #f9fafb;
        padding: 1rem;
        margin: 1.5rem 0;
        border-radius: 0 4px 4px 0;
    }
</style>

@endsection