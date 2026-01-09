@extends('layouts.client_layout')

@section('content')

{{-- 1. HEADER / BREADCRUMB --}}
<nav class="bg-gray-50 border-b border-gray-200 py-4">
    <div class="container mx-auto px-4">
        <ol class="flex text-sm text-gray-500 items-center gap-2 overflow-hidden whitespace-nowrap">
            <li>
                <a href="/" class="hover:text-blue-600 transition flex items-center">
                    <i class="fas fa-home mr-1.5"></i> Trang chủ
                </a>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right text-xs"></i></li>
            <li>
                <span class="hover:text-blue-600 transition cursor-default">Giới thiệu</span>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right text-xs"></i></li>
            <li class="text-blue-600 font-bold truncate max-w-[200px] md:max-w-md" title="{{ $page->title }}">
                {{ $page->title }}
            </li>
        </ol>
    </div>
</nav>

{{-- 2. NỘI DUNG CHÍNH (CONTENT GỐC TỪ EDITOR) --}}
@if(!empty($page->content) && trim(strip_tags($page->content)) != '')
    
    <div class="container mx-auto px-4 py-12">
        <div class="prose prose-lg prose-blue max-w-4xl mx-auto text-gray-700 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            {!! $page->content !!}
        </div>
    </div>

@endif

{{-- 3. RENDER CÁC SECTIONS ĐỘNG --}}
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

            {{-- === TYPE: INTRO (Giới thiệu công ty - Card bên phải) === --}}
            @elseif($section->type == 'intro')
                <section class="py-16 bg-white">
                    <div class="container mx-auto px-4">
                        <div class="flex flex-col lg:flex-row items-center gap-12">
                            
                            {{-- CỘT TRÁI: Nội dung văn bản --}}
                            <div class="lg:w-1/2">
                                <div class="prose prose-lg text-gray-600 leading-relaxed text-justify">
                                    {!! nl2br(e($section->data['content'] ?? '')) !!}
                                </div>
                                
                                {{-- Ví dụ fix cứng icon địa chỉ nếu muốn đẹp như hình --}}
                                <div class="mt-8 flex items-start gap-3 bg-blue-50 p-4 rounded-lg">
                                    <div class="text-blue-600 mt-1"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="text-sm text-gray-700 font-medium">
                                        38 đường số 9, KĐT Tây Sông Hậu, Long Xuyên, An Giang
                                    </div>
                                </div>
                            </div>

                            {{-- CỘT PHẢI: Card Logo & Button --}}
                            <div class="lg:w-1/2 w-full">
                                <div class="bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] p-8 md:p-12 text-center border border-gray-100 max-w-md mx-auto lg:ml-auto">
                                    
                                    {{-- Logo --}}
                                    @if(!empty($section->data['image']))
                                        <img src="{{ asset($section->data['image']) }}" alt="Logo" class="h-16 mx-auto mb-6 object-contain">
                                    @endif

                                    {{-- Slogan --}}
                                    <h3 class="text-gray-800 font-bold uppercase tracking-wider mb-8 text-sm md:text-base">
                                        {{ $section->data['slogan'] ?? 'GIẢI PHÁP - CÔNG NGHỆ - TƯƠNG LAI' }}
                                    </h3>

                                    {{-- Button --}}
                                    <a href="{{ $section->data['button_link'] ?? '#' }}" class="inline-block px-8 py-3 border-2 border-blue-600 text-blue-600 font-bold rounded-full hover:bg-blue-600 hover:text-white transition duration-300">
                                        {{ $section->data['button_text'] ?? 'XEM CHI TIẾT' }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            @endif {{-- QUAN TRỌNG: Đã thêm thẻ đóng logic IF ở đây --}}
        
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