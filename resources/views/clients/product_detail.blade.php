@extends('layouts.client_layout')

@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Tối ưu hóa chuyển động (Hardware Acceleration) */
        .swiper-wrapper {
            transition-timing-function: ease-out; /* Hiệu ứng lướt nhẹ nhàng */
        }
        .swiper-slide {
            /* Giúp browser render mượt hơn, không bị giật hình */
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden; 
        }
        /* Con trỏ khi kéo */
        .cursor-grab { cursor: -webkit-grab; cursor: grab; }
        .cursor-grabbing { cursor: -webkit-grabbing; cursor: grabbing; }
    </style>
@endpush

<div class="bg-white py-3 border-b border-gray-200 mb-6">
    <div class="container mx-auto px-4 text-xs font-bold text-gray-500 uppercase tracking-wide flex items-center gap-2">
        <a href="/" class="hover:text-blue-600 transition">TRANG CHỦ</a>
        <i class="fas fa-angle-right text-gray-300 text-[10px]"></i>
        @if($product->category)
            <a href="{{ route('frontend.category.show', $product->category_id) }}" class="hover:text-blue-600 transition">{{ $product->category->name }}</a>
            <i class="fas fa-angle-right text-gray-300 text-[10px]"></i>
        @endif
        <span class="text-gray-900">{{ $product->name }}</span>
    </div>
</div>

<div class="container mx-auto px-4 pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-9">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-10">
                <div class="md:col-span-5">
                    <div class="border border-gray-200 rounded p-4 bg-white relative group overflow-hidden">
                        @if($product->sale_price)
                            <span class="absolute top-3 left-3 bg-red-600 text-white text-[11px] font-bold px-2 py-1 rounded shadow-sm z-10">
                                -{{ round((($product->price - $product->sale_price)/$product->price)*100) }}%
                            </span>
                        @endif
                        <div class="aspect-[1/1] flex items-center justify-center">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="max-w-full max-h-full object-contain cursor-zoom-in" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/500x500?text=No+Image" class="opacity-50">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-7 flex flex-col">
                    <h1 class="text-2xl font-bold text-gray-900 leading-snug mb-2">{{ $product->name }}</h1>
                    <div class="w-12 h-1 bg-blue-600 mb-4 rounded-full"></div>

                    {{-- <div class="flex items-center text-sm mb-4 text-gray-600">
                        <div class="flex text-yellow-400 text-xs mr-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <span class="border-l border-gray-300 pl-2 ml-2 text-green-600 font-bold"><i class="fas fa-check-circle mr-1"></i>Còn hàng</span>
                    </div> --}}

                    <div class="mb-5 bg-gray-50 p-3 rounded border border-gray-100">
                        @if($product->sale_price)
                            <span class="text-3xl font-bold text-red-600">{{ number_format($product->sale_price) }} ₫</span>
                            <span class="text-sm text-gray-400 line-through ml-2">{{ number_format($product->price) }} ₫</span>
                        @else
                            <span class="text-3xl font-bold text-red-600">{{ number_format($product->price) }} ₫</span>
                        @endif
                    </div>

                    {{-- === PHẦN THÊM THƯƠNG HIỆU === --}}
                    <div class="text-gray-600 text-sm leading-relaxed mb-6">
                        <ul class="space-y-1.5 list-disc pl-4 marker:text-blue-500">
                            <li>
                                <strong>Thương hiệu:</strong> 
                                <span class="font-bold text-dark-700 ">
                                    {{ $product->brand ?? 'Đang cập nhật' }}
                                </span>
                            </li>
                            <li>
                                
                        </ul>
                    </div>
                    {{-- === KẾT THÚC === --}}

                    <div class="flex gap-3 mt-auto">
                        <a href="{{ route('add_to_cart', $product->id) }}" class="flex-1 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3 rounded text-sm uppercase tracking-wide transition flex items-center justify-center">
                            <i class="fas fa-cart-plus mr-2"></i> Thêm giỏ
                        </a>
                        <a href="{{ route('buy_now', $product->id) }}" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded text-sm uppercase tracking-wide transition flex items-center justify-center shadow-lg shadow-red-200">
                            Mua ngay
                        </a>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <div class="border-b border-gray-200 mb-6">
                    <button class="inline-block py-2 px-4 border-b-2 border-blue-600 text-blue-600 font-bold text-sm uppercase">Chi tiết sản phẩm</button>
                </div>
                <div class="text-gray-700 leading-7 text-[15px] description-content">
                    {!! $product->description ?? '<p class="text-gray-400 italic">Đang cập nhật nội dung...</p>' !!}
                </div>
            </div>

            @if(isset($relatedProducts) && count($relatedProducts) > 0)
            <div class="mt-12 select-none"> 
                <h3 class="text-lg font-bold text-gray-800 uppercase mb-6 border-l-4 border-red-600 pl-3">Sản phẩm tương tự</h3>
                
                <div class="relative w-full"> 
                    <div class="swiper mySwiper w-full overflow-hidden rounded-lg p-2 cursor-grab active:cursor-grabbing"> 
                        <div class="swiper-wrapper">
                            
                            {{-- NHÂN BẢN SLIDE ĐỂ KÉO ĐƯỢC (Đảm bảo số lượng > hiển thị) --}}
                            @for ($i = 0; $i < 4; $i++) 
                                @foreach($relatedProducts as $related)
                                <div class="swiper-slide h-auto">
                                    <div class="bg-white border border-gray-200 rounded-lg hover:shadow-lg hover:border-blue-400 transition-all duration-300 h-full flex flex-col group relative">
                                        
                                        @if($related->sale_price)
                                            <span class="absolute top-2 right-2 z-10 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                                -{{ round((($related->price - $related->sale_price)/$related->price)*100) }}%
                                            </span>
                                        @endif

                                        <div class="relative pt-[100%] overflow-hidden bg-white p-4 border-b border-gray-50">
                                            <a href="{{ route('product.detail', $related->id) }}" class="absolute inset-0 flex items-center justify-center pointer-events-none md:pointer-events-auto">
                                                @if($related->image)
                                                    <img src="{{ asset($related->image) }}" class="max-h-full max-w-full object-contain pointer-events-none">
                                                @else
                                                    <img src="https://via.placeholder.com/300" class="opacity-50 pointer-events-none">
                                                @endif
                                            </a>
                                        </div>

                                        <div class="p-3 text-center flex-grow flex flex-col justify-between">
                                            <h4 class="text-[13px] font-medium text-gray-700 mb-2 line-clamp-2 h-10 hover:text-blue-600">
                                                <a href="{{ route('product.detail', $related->id) }}">{{ $related->name }}</a>
                                            </h4>
                                            <div class="text-sm font-bold text-red-600">
                                                {{ number_format($related->sale_price ?: $related->price) }} ₫
                                            </div>
                                        </div>
                                        
                                        <div class="absolute bottom-20 left-0 w-full flex justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <a href="{{ route('add_to_cart', $related->id) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-bold shadow-md hover:bg-blue-700">
                                                Thêm vào giỏ
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            @endfor
                            {{-- KẾT THÚC NHÂN BẢN --}}

                        </div>
                        {{-- <div class="swiper-pagination !bottom-0"></div> --}}
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="lg:col-span-3">
            <h4 class="font-bold text-gray-800 uppercase border-b-2 border-blue-600 inline-block mb-4 pb-1 text-sm">Sản phẩm nổi bật</h4>
            <div class="space-y-4">
                @if(isset($relatedProducts) && count($relatedProducts) > 0)
                    @foreach($relatedProducts->take(5) as $hotItem)
                    <a href="{{ route('product.detail', $hotItem->id) }}" class="flex gap-3 group bg-white border border-transparent hover:border-gray-200 p-2 rounded transition">
                        <div class="w-14 h-14 border border-gray-100 rounded bg-white p-1 flex-shrink-0 flex items-center justify-center">
                            <img src="{{ asset($hotItem->image) }}" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/100'">
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-gray-700 group-hover:text-blue-600 leading-tight mb-1 line-clamp-2">{{ $hotItem->name }}</h5>
                            <span class="text-red-600 font-bold text-xs">{{ number_format($hotItem->sale_price ?: $hotItem->price) }} ₫</span>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chờ 0.3s để Tailwind vẽ xong layout
        setTimeout(() => {
            const swiperEl = document.querySelector('.mySwiper');
            if (swiperEl) {
                new Swiper('.mySwiper', {
                    // Cấu hình Loop
                    loop: true, 
                    observer: true,
                    observeParents: true,
                    
                    // --- CẤU HÌNH ĐỘ MƯỢT (QUAN TRỌNG) ---
                    grabCursor: true,       // Bàn tay nắm
                    simulateTouch: true,    // Kéo được bằng chuột
                    touchRatio: 1.5,        // Tăng độ nhạy khi kéo (Kéo 1 đi 1.5) -> Cảm giác nhanh hơn
                    resistance: true,       // Kháng lực kéo ở biên
                    resistanceRatio: 0.65,
                    
                    // --- TỐC ĐỘ CHUYỂN SLIDE ---
                    speed: 800, // 800ms = lướt êm hơn
                    
                    // --- TỰ ĐỘNG CHẠY (AUTOPLAY) ---
                    autoplay: {
                        delay: 3000, // Đợi 3s thôi (Thay vì 5s) -> Cảm giác liên tục hơn
                        disableOnInteraction: false, // Thả tay ra là tính giờ chạy lại ngay
                        // pauseOnMouseEnter: false, // Bỏ dòng này để chuột đè lên nó vẫn chạy (nếu muốn)
                    },
                    
                    slidesPerView: 2, 
                    spaceBetween: 15, 
                    
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },

                    breakpoints: {
                        0: { slidesPerView: 2, spaceBetween: 10 },
                        640: { slidesPerView: 2, spaceBetween: 15 },
                        768: { slidesPerView: 3, spaceBetween: 15 },
                        1024: { slidesPerView: 4, spaceBetween: 20 },
                        1280: { slidesPerView: 5, spaceBetween: 20 }, 
                    }
                });
            }
        }, 300);
    });
</script>
@endpush

@endsection