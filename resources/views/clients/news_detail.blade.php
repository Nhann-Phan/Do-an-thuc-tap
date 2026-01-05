@extends('layouts.client_layout')

@section('content')

{{-- BREADCRUMB --}}
<nav class="bg-gray-50 border-b border-gray-200 py-4">
    <div class="container mx-auto px-4">
        <ol class="flex text-sm text-gray-500 items-center gap-2 overflow-hidden whitespace-nowrap">
            <li>
                <a href="/" class="hover:text-blue-600 transition flex items-center">
                    <i class="fas fa-home mr-1.5"></i> Trang chủ
                </a>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            <li><a href="{{ route('news.index') }}" class="hover:text-blue-600 transition">Tin tức</a></li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            <li class="text-gray-900 font-medium truncate max-w-[200px] md:max-w-md" title="{{ $news->title }}">
                {{ $news->title }}
            </li>
        </ol>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- CỘT TRÁI: NỘI DUNG BÀI VIẾT (Chiếm 8 phần) --}}
            <div class="lg:col-span-8">
                <article>
                    {{-- Tiêu đề --}}
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-6">
                        {{ $news->title }}
                    </h1>
                    
                    {{-- Meta Info --}}
                    <div class="flex flex-wrap items-center text-sm text-gray-500 mb-8 pb-6 border-b border-gray-100 gap-6">
                        <span class="flex items-center">
                            <i class="far fa-calendar-alt mr-2 text-blue-600"></i> 
                            {{ $news->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span class="flex items-center">
                            <i class="far fa-user mr-2 text-blue-600"></i> 
                            Đăng bởi: <span class="font-medium text-gray-700 ml-1">Admin</span>
                        </span>
                        <span class="flex items-center">
                            <i class="far fa-eye mr-2 text-blue-600"></i> 
                            Lượt xem: 125
                        </span>
                    </div>

                    {{-- Summary (Mô tả ngắn) --}}
                    @if($news->summary)
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-6 mb-10 rounded-r-lg">
                        <p class="text-gray-700 font-medium italic text-lg leading-relaxed">
                            {{ $news->summary }}
                        </p>
                    </div>
                    @endif

                    {{-- Content Body (Nội dung chính từ CKEditor) --}}
                    {{-- Class 'content-body' dùng để CSS riêng cho các thẻ HTML thuần --}}
                    <div class="content-body text-gray-800 text-lg leading-relaxed">
                        {!! $news->content !!}
                    </div>
                </article>

                {{-- Share Buttons --}}
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50 p-4 rounded-xl">
                        <span class="font-bold text-gray-700 uppercase text-sm tracking-wide">
                            <i class="fas fa-share-alt mr-2 text-blue-600"></i> Chia sẻ bài viết:
                        </span>
                        <div class="flex gap-3">
                            <button class="bg-[#1877F2] text-white w-10 h-10 rounded-full flex items-center justify-center hover:opacity-90 transition shadow-sm transform hover:-translate-y-1">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button class="bg-[#1DA1F2] text-white w-10 h-10 rounded-full flex items-center justify-center hover:opacity-90 transition shadow-sm transform hover:-translate-y-1">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="bg-[#0A66C2] text-white w-10 h-10 rounded-full flex items-center justify-center hover:opacity-90 transition shadow-sm transform hover:-translate-y-1">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Đã sao chép liên kết!');" class="bg-gray-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-700 transition shadow-sm transform hover:-translate-y-1" title="Sao chép liên kết">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: SIDEBAR (Chiếm 4 phần) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-8">
                    
                    {{-- Box Tin tức mới nhất --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex items-center">
                            <div class="h-5 w-1 bg-blue-600 rounded mr-3"></div>
                            <h3 class="font-bold text-gray-800 uppercase text-sm tracking-wide">Tin tức mới nhất</h3>
                        </div>
                        
                        <div class="divide-y divide-gray-100">
                            @forelse($relatedNews as $item)
                            <a href="{{ route('news.detail', $item->id) }}" class="flex gap-4 p-5 hover:bg-blue-50/50 transition group items-start">
                                {{-- Thumbnail nhỏ --}}
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 relative">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Info --}}
                                <div class="flex-grow min-w-0">
                                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 line-clamp-2 leading-snug mb-1.5 transition">
                                        {{ $item->title }}
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-400">
                                        <i class="far fa-clock mr-1.5"></i> 
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="p-8 text-center text-gray-500 italic text-sm">
                                Chưa có tin tức liên quan nào.
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Box Quảng cáo / Banner (Nếu có) --}}
                    {{-- 
                    <div class="rounded-xl overflow-hidden shadow-md">
                        <img src="https://via.placeholder.com/400x300?text=Banner+Quang+Cao" class="w-full h-auto">
                    </div> 
                    --}}

                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS TÙY CHỈNH CHO NỘI DUNG BÀI VIẾT (CKEditor Content) --}}
<style>
    /* Reset styles for content-body to ensure WYSIWYG content looks good */
    .content-body { color: #374151; /* gray-700 */ }
    
    /* Headings */
    .content-body h2 { font-size: 1.5rem; line-height: 2rem; font-weight: 700; color: #1e3a8a; margin-top: 2rem; margin-bottom: 1rem; }
    .content-body h3 { font-size: 1.25rem; line-height: 1.75rem; font-weight: 600; color: #1f2937; margin-top: 1.5rem; margin-bottom: 0.75rem; }
    .content-body h4 { font-size: 1.125rem; font-weight: 600; margin-top: 1.25rem; margin-bottom: 0.5rem; }

    /* Paragraphs & Lists */
    .content-body p { margin-bottom: 1.25rem; line-height: 1.8; text-align: justify; }
    .content-body ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .content-body ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .content-body li { margin-bottom: 0.5rem; }

    /* Links */
    .content-body a { color: #2563eb; text-decoration: underline; text-underline-offset: 2px; }
    .content-body a:hover { color: #1d4ed8; }

    /* Images inside content */
    .content-body img { 
        max-width: 100%; 
        height: auto !important; 
        border-radius: 0.5rem; 
        margin: 2rem auto; 
        display: block; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
    }
    
    /* Captions */
    .content-body figcaption { text-align: center; font-size: 0.875rem; color: #6b7280; margin-top: -1.5rem; margin-bottom: 2rem; font-style: italic; }

    /* Blockquotes */
    .content-body blockquote { 
        border-left: 4px solid #3b82f6; 
        padding-left: 1.25rem; 
        font-style: italic; 
        color: #4b5563; 
        background-color: #f3f4f6; 
        padding: 1rem; 
        margin: 2rem 0; 
        border-radius: 0 0.5rem 0.5rem 0; 
    }

    /* Tables inside content */
    .content-body table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; border: 1px solid #e5e7eb; }
    .content-body th, .content-body td { border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; }
    .content-body th { background-color: #f9fafb; font-weight: 600; }
</style>

@endsection