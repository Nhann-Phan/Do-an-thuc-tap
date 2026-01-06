@extends('layouts.client_layout')

@section('content')

{{-- 1. HEADER / BREADCRUMB --}}
<div class="bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4 py-12">
        <div class="text-center max-w-4xl mx-auto">
            <nav class="flex justify-center text-sm text-gray-500 mb-4 space-x-2 font-medium">
                <a href="/" class="hover:text-blue-600 transition">Trang chủ</a>
                <span>/</span>
                <span class="text-gray-400">Giới thiệu</span>
                <span>/</span>
                <span class="text-blue-600 font-bold">{{ $page->title }}</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight uppercase">
                {{ $page->title }}
            </h1>
            
            @if($page->summary)
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto italic">
                    "{{ $page->summary }}"
                </p>
            @endif
        </div>
    </div>
</div>

{{-- 2. NỘI DUNG CHÍNH (CONTENT GỐC TỪ EDITOR) --}}
@if($page->content)
<div class="container mx-auto px-4 py-12">
    <div class="prose prose-lg prose-blue max-w-4xl mx-auto text-gray-700 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        {!! $page->content !!}
    </div>
</div>
@endif

{{-- 3. RENDER CÁC SECTIONS ĐỘNG (Kiểm tra xem có sections không trước khi loop) --}}
@if(isset($page->sections) && $page->sections->count() > 0)
    <div class="flex flex-col gap-0">
        @foreach($page->sections as $section)
            
            {{-- === TYPE: TEXT + IMAGE (Ảnh trái/phải) === --}}
            @if($section->type == 'text_image')
                <section class="py-16 md:py-24 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                    <div class="container mx-auto px-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                            {{-- Logic đảo chiều ảnh --}}
                            @php $isImageRight = ($section->data['layout'] ?? 'image_right') == 'image_right'; @endphp
                            
                            <div class="{{ $isImageRight ? 'lg:order-1' : 'lg:order-2' }}">
                                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 relative inline-block">
                                    {{ $section->title }}
                                    <span class="absolute bottom-0 left-0 w-1/3 h-1 bg-blue-600 rounded"></span>
                                </h2>
                                <div class="prose prose-lg text-gray-600 text-justify leading-relaxed">
                                    {!! $section->data['content'] ?? '' !!}
                                </div>
                            </div>
                            
                            <div class="{{ $isImageRight ? 'lg:order-2' : 'lg:order-1' }}">
                                <div class="relative rounded-2xl overflow-hidden shadow-2xl transform transition hover:-translate-y-2 duration-500 group">
                                    <img src="{{ $section->data['image'] ?? 'https://via.placeholder.com/800x600' }}" 
                                         alt="{{ $section->title }}" 
                                         class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            {{-- === TYPE: STATS (Thống kê) === --}}
            @elseif($section->type == 'stats')
                <section class="py-16 bg-blue-900 text-white relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    
                    <div class="container mx-auto px-4 relative z-10">
                        @if($section->title)
                            <h2 class="text-center text-3xl font-bold mb-12 uppercase tracking-widest text-blue-200">{{ $section->title }}</h2>
                        @endif
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-blue-800/50">
                            @foreach($section->data['stats'] ?? [] as $stat)
                                <div class="p-4 transform hover:scale-105 transition duration-300">
                                    <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">{{ $stat['number'] }}</div>
                                    <div class="text-sm md:text-base text-blue-200 uppercase font-medium tracking-wide">{{ $stat['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

            {{-- === TYPE: CTA (Kêu gọi hành động) === --}}
            @elseif($section->type == 'cta')
                <section class="py-20 bg-gradient-to-r from-red-600 to-red-800 text-white text-center">
                    <div class="container mx-auto px-4 max-w-4xl">
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">{{ $section->title }}</h2>
                        <p class="text-lg text-red-100 mb-10 leading-relaxed">{{ $section->data['subtext'] ?? '' }}</p>
                        <a href="{{ $section->data['button_link'] ?? '#' }}" class="inline-block px-10 py-4 bg-white text-red-700 font-bold rounded-full shadow-xl hover:bg-gray-100 hover:shadow-2xl hover:-translate-y-1 transition transform duration-300 uppercase tracking-wide text-sm">
                            {{ $section->data['button_text'] ?? 'Liên hệ ngay' }} <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </section>

            @endif

        @endforeach
    </div>
@endif

{{-- CSS Typography giả lập cho nội dung HTML --}}
<style>
    .prose p { margin-bottom: 1.5em; }
    .prose ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.5em; }
    .prose li { margin-bottom: 0.5em; }
    .prose strong { color: #111827; font-weight: 700; }
    .prose a { color: #2563eb; text-decoration: underline; }
    .prose img { border-radius: 0.5rem; margin: 1.5rem auto; display: block; max-width: 100%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
</style>

@endsection