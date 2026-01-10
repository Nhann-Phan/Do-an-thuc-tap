@extends('layouts.client_layout')

@section('content')

    {{-- 1. HERO BANNER --}}
    <div class="relative bg-gray-900 h-[400px] md:h-[500px] overflow-hidden group">
        {{-- Ảnh nền --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <img src="https://getwallpapers.com/wallpaper/full/b/e/8/165641.jpg" 
                 class="w-full h-full object-cover opacity-60 transform group-hover:scale-105 transition duration-[2s]">
        </div>
        
        {{-- Nội dung đè lên --}}
        <div class="absolute inset-0 z-10 flex items-center">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl text-white animate-fade-in">
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-6 uppercase leading-tight tracking-tight drop-shadow-lg">
                        Giải pháp quản lý <br>
                        <span class="text-blue-400">cho doanh nghiệp 4.0</span>
                    </h2>
                    <p class="text-lg text-gray-200 mb-8 max-w-lg leading-relaxed shadow-black drop-shadow-md">
                        Tối ưu hóa quy trình, nâng cao hiệu suất và bứt phá doanh thu với hệ sinh thái công nghệ toàn diện.
                    </p>
                    <a href="#products" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-full font-bold uppercase text-sm transition transform hover:scale-105 hover:shadow-lg inline-flex items-center ring-2 ring-blue-500 ring-offset-2 ring-offset-gray-900">
                        Xem giải pháp <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Overlay Gradient nhẹ ở đáy để khớp với phần dưới --}}
        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent z-10"></div>
    </div>  

    {{-- 2. GIỚI THIỆU (INTRO) - DỮ LIỆU ĐỘNG TỪ ADMIN --}}
    <section class="py-16 md:py-24 bg-slate-50 relative overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <i class="fas fa-network-wired text-[300px] absolute -top-20 -left-20 text-gray-200 opacity-30 rotate-12"></i>
            <i class="fas fa-share-alt text-[200px] absolute -bottom-10 -right-10 text-gray-200 opacity-30 -rotate-12"></i>
        </div>

        {{-- Logic lấy dữ liệu section intro --}}
        @php
            $introSection = $page?->sections->where('type', 'intro')->first();
            $data = $introSection?->data ?? [];
        @endphp

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2 uppercase tracking-wide">
                    {{ $introSection->title ?? 'Giới thiệu công ty' }}
                </h2>
                <div class="w-24 h-1.5 bg-blue-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-10 -mt-10 z-0"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
                    {{-- Text Content --}}
                    <div class="space-y-6 text-gray-600 text-justify leading-relaxed">
                        <div class="prose prose-lg text-gray-600 max-w-none">
                            @if(!empty($data['content']))
                                {{-- Hiển thị nội dung từ Admin --}}
                                {!! nl2br(e($data['content'])) !!}
                            @else
                                {{-- Nội dung mặc định nếu chưa nhập --}}
                                <p class="text-lg">
                                    Công ty TNHH MTV Thiết bị và phần mềm <span class="font-bold text-blue-700">GPM Việt Nam</span> tự hào là đơn vị tiên phong trong lĩnh vực công nghệ thông tin tại khu vực, chuyên cung cấp các giải pháp chuyển đổi số toàn diện.
                                </p>
                                <p>
                                    Với đội ngũ kỹ sư giàu kinh nghiệm, nhiệt huyết và am hiểu thị trường địa phương, chúng tôi cam kết mang đến những sản phẩm chất lượng cao, vận hành ổn định với chi phí tối ưu nhất cho doanh nghiệp của bạn.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Logo / Branding Box --}}
                    <div class="flex justify-center items-center">
                        <div class="text-center p-8 rounded-2xl bg-slate-50 border border-slate-100 shadow-inner transform hover:scale-105 transition duration-500 w-full max-w-sm">
                            <div class="inline-flex items-center justify-center">
                                @if(!empty($data['image']))
                                    <img src="{{ asset($data['image']) }}" alt="Logo" class="h-20 object-contain" style="height: 50%;">
                                @else
                                    <i class="fas fa-chart-pie text-6xl text-blue-600 mr-3"></i> 
                                    <div class="flex flex-col items-start">
                                        <span class="text-5xl font-black text-gray-800 tracking-tighter leading-none">GPM</span>
                                        <span class="text-xs font-bold text-blue-600 tracking-[0.3em] uppercase">Technology</span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-lg font-bold text-gray-700 mb-6 uppercase">
                                {{ $data['slogan'] ?? 'GIẢI PHÁP - CÔNG NGHỆ - TƯƠNG LAI' }}
                            </div>
                            <a href="{{ $data['button_link'] ?? '#' }}" class="inline-block px-8 py-3 bg-white border-2 border-blue-600 text-blue-600 font-bold rounded-full hover:bg-blue-600 hover:text-white transition duration-300 shadow-sm hover:shadow-md">
                                {{ $data['button_text'] ?? 'XEM CHI TIẾT' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. SẢN PHẨM (SWIPER SLIDER) --}}
    <section id="products" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 pb-4 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-r mr-4"></div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 uppercase">Sản phẩm nổi bật</h3>
                        @if(isset($currentCategory))
                            <p class="text-blue-500 text-sm font-medium mt-1">{{ $currentCategory->name }}</p>
                        @endif
                    </div>
                </div>
                {{-- Nav Buttons --}}
                <div class="flex gap-2 mt-4 md:mt-0">
                    <button class="swiper-button-prev-product w-10 h-10 rounded-full border border-gray-300 text-gray-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 flex items-center justify-center transition focus:outline-none">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="swiper-button-next-product w-10 h-10 rounded-full border border-gray-300 text-gray-500 hover:bg-blue-600 hover:text-white hover:border-blue-600 flex items-center justify-center transition focus:outline-none">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- Slider --}}
            <div class="swiper myProductSwiper !pb-12 px-1">
                <div class="swiper-wrapper">
                    @for ($i = 0; $i < 4; $i++) {{-- Loop demo để test slider nếu ít sản phẩm --}}
                        @foreach($products as $product)
                            @if($product->is_hot || (isset($product->status) && $product->status == 'hot')) 
                            <div class="swiper-slide h-auto">
                                <div class="bg-white border border-gray-200 rounded-xl hover:shadow-xl transition duration-300 relative group flex flex-col h-full overflow-hidden">
                                    {{-- Badges --}}
                                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
                                        <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">HOT</span>
                                        @if($product->sale_price)
                                            <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SALE</span>
                                        @endif
                                    </div>

                                    {{-- Image --}}
                                    <div class="h-56 p-6 flex items-center justify-center relative bg-gray-50 group-hover:bg-white transition duration-300">
                                        <a href="{{ route('product.detail', $product->id) }}" class="block w-full h-full flex items-center justify-center">
                                            @if($product->image)
                                                <img src="{{ asset($product->image) }}" class="max-h-full max-w-full object-contain transform group-hover:scale-110 transition duration-500">
                                            @else
                                                <i class="fas fa-laptop text-6xl text-gray-200"></i> 
                                            @endif
                                        </a>
                                    </div>

                                    {{-- Info --}}
                                    <div class="p-5 flex-grow flex flex-col border-t border-gray-100">
                                        @if($product->brand)
                                            <div class="mb-2">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider border border-gray-200 px-2 py-0.5 rounded bg-gray-50">
                                                    {{ $product->brand }}
                                                </span>
                                            </div>
                                        @endif

                                        <h4 class="text-sm font-bold text-gray-800 mb-2 hover:text-blue-600 transition line-clamp-2 min-h-[2.5rem]">
                                            <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                                        </h4>

                                        <div class="mt-auto pt-2">
                                            <div class="flex items-baseline gap-2 mb-3">
                                                @if($product->sale_price)
                                                    <span class="text-red-600 font-bold text-lg">{{ number_format($product->sale_price) }} đ</span>
                                                    <span class="text-gray-400 text-xs line-through">{{ number_format($product->price) }} đ</span>
                                                @else
                                                    <span class="text-red-600 font-bold text-lg">{{ number_format($product->price) }} đ</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endfor
                </div>
                <div class="swiper-pagination-product swiper-pagination !bottom-0"></div>
            </div>
        </div>
    </section>

    {{-- 4. DỰ ÁN (GALLERY) --}}
    <section class="py-16 bg-slate-50" id="gallery">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-gray-200 pb-4">
                <div class="flex items-center">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-r mr-4"></div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 uppercase">Ảnh công trình thực tế</h2>
                        <p class="text-sm text-gray-500 mt-1">Các dự án GPM đã triển khai thành công</p>
                    </div>
                </div>
            </div>

            <div class="swiper myProjectSwiper !pb-12 px-1"> 
                <div class="swiper-wrapper">
                    @if(isset($projectImages) && count($projectImages) > 0)
                        {{-- Loop demo để slider chạy đẹp nếu ít ảnh --}}
                        @for($i = 0; $i < (count($projectImages) < 4 ? 4 : 1); $i++)
                            @foreach($projectImages as $img)
                            <div class="swiper-slide">
                                <div class="overflow-hidden rounded-xl shadow-md relative group h-72 cursor-pointer border border-gray-200">
                                    <img src="{{ asset($img->image_path) }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" 
                                         loading="lazy">
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500 flex items-end p-6">
                                        <div class="transform translate-y-4 group-hover:translate-y-0 transition duration-500 w-full">
                                            <p class="text-white font-bold text-lg line-clamp-2 leading-tight">
                                                {{ $img->caption ?? 'Dự án GPM' }}
                                            </p>
                                            <div class="h-1 w-12 bg-blue-500 mt-3 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endfor
                    @else
                        <div class="swiper-slide">
                            <div class="h-64 flex items-center justify-center bg-white rounded-xl border-2 border-dashed border-gray-300 text-gray-400">
                                Chưa có hình ảnh dự án nào.
                            </div>
                        </div>
                    @endif
                </div>
                <div class="swiper-pagination-project swiper-pagination !bottom-0"></div>
            </div>
        </div>
    </section>

    {{-- 5. TIN TỨC (NEWS) --}}
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="container mx-auto px-4">
            
            <div class="flex justify-between items-end mb-10 border-b border-gray-100 pb-4">
                <div class="flex items-center">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-r mr-4"></div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase">Tin tức & Sự kiện</h2>
                </div>
                <a href="{{ route('client.news.index') }}" class="group flex items-center text-sm font-semibold text-gray-500 hover:text-blue-600 transition bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-blue-200">
                    Xem tất cả <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition"></i>
                </a>
            </div>

            @if(isset($latestNews) && count($latestNews) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @foreach($latestNews->take(3) as $news)
                <article class="flex flex-col h-full bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 overflow-hidden group">
                    <a href="{{ route('client.news.detail', $news->id) }}" class="overflow-hidden relative aspect-video bg-gray-100 block">
                        @if($news->image)
                            <img src="{{ asset($news->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300"><i class="fas fa-newspaper text-5xl opacity-50"></i></div>
                        @endif
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1 rounded shadow-sm border border-gray-100">
                            {{ $news->created_at->format('d/m/Y') }}
                        </div>
                    </a>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">Tin tức</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 leading-snug mb-3 line-clamp-2 transition">
                            <a href="{{ route('client.news.detail', $news->id) }}">{{ $news->title }}</a>
                        </h3>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-3 flex-grow leading-relaxed">
                            {{ $news->summary }}
                        </p>
                        
                        <div class="pt-4 border-t border-gray-100 mt-auto">
                            <a href="{{ route('client.news.detail', $news->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center group/link">
                                Đọc tiếp <i class="fas fa-arrow-right ml-2 text-xs transform group-hover/link:translate-x-1 transition"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach

            </div>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                        <i class="far fa-newspaper text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Chưa có tin tức nào được đăng tải.</p>
                </div>
            @endif

        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            
            // Slider Dự án
            if (document.querySelector(".myProjectSwiper")) {
                new Swiper(".myProjectSwiper", {
                    observer: true, 
                    observeParents: true,
                    slidesPerView: 1,      
                    spaceBetween: 20,      
                    loop: true,            
                    grabCursor: true,      
                    autoplay: { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true },
                    breakpoints: {
                        640: { slidesPerView: 2, spaceBetween: 20 },
                        768: { slidesPerView: 3, spaceBetween: 25 },
                        1024: { slidesPerView: 4, spaceBetween: 30 }, 
                    },
                    pagination: { el: ".swiper-pagination-project", clickable: true },
                });
            }

            // Slider Sản phẩm
            if (document.querySelector(".myProductSwiper")) {
                new Swiper(".myProductSwiper", {
                    observer: true, 
                    observeParents: true,
                    slidesPerView: 2,       
                    spaceBetween: 15,      
                    loop: true,  
                    grabCursor: true,      
                    autoplay: { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true },
                    breakpoints: {
                        640: { slidesPerView: 2, spaceBetween: 20 },
                        768: { slidesPerView: 3, spaceBetween: 20 },
                        1024: { slidesPerView: 4, spaceBetween: 25 }, 
                        1280: { slidesPerView: 5, spaceBetween: 25 }, 
                    },
                    navigation: { nextEl: ".swiper-button-next-product", prevEl: ".swiper-button-prev-product" },
                    pagination: { el: ".swiper-pagination-product", clickable: true },
                });
            }

        }, 300); 
    });
</script>

<style>
    .swiper-pagination-bullet-active { background-color: #2563eb !important; width: 24px !important; border-radius: 4px !important; transition: width 0.3s; }
</style>
@endpush