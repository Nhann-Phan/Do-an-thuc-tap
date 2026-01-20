@extends('layouts.client_layout')

@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Tùy chỉnh CSS cho nội dung bài viết (CKEditor) */
        .product-description h2 { font-size: 1.5rem; font-weight: 700; margin: 1.5rem 0 1rem; color: #1e3a8a; }
        .product-description h3 { font-size: 1.25rem; font-weight: 600; margin: 1.25rem 0 0.75rem; color: #1f2937; }
        .product-description p { margin-bottom: 1rem; line-height: 1.7; color: #374151; }
        .product-description ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
        .product-description img { border-radius: 0.5rem; margin: 1.5rem auto; max-width: 100%; height: auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        
        /* Style cho nút biến thể khi Active */
        .variant-btn.active {
            border-color: #2563eb;
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
            box-shadow: 0 0 0 1px #2563eb;
        }
    </style>
@endpush

{{-- BREADCRUMB --}}
<nav class="bg-gray-50 border-b border-gray-200 py-4 mb-8">
    <div class="container mx-auto px-4">
        <ol class="flex text-sm text-gray-500 items-center gap-2 overflow-hidden whitespace-nowrap font-medium">
            <li>
                <a href="/" class="hover:text-blue-600 transition flex items-center">
                    <i class="fas fa-home mr-1.5"></i> Trang chủ
                </a>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            @if($product->category)
                <li>
                    <a href="{{ route('frontend.category.show', $product->category_id) }}" class="hover:text-blue-600 transition">
                        {{ $product->category->name }}
                    </a>
                </li>
                <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            @endif
            <li class="text-gray-900 truncate">{{ $product->name }}</li>
        </ol>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div class="container mx-auto px-4 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        {{-- CỘT TRÁI (LỚN): ẢNH + THÔNG TIN + MÔ TẢ --}}
        <div class="lg:col-span-9">
            
            {{-- PRODUCT TOP SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">
                {{-- 1. Ảnh sản phẩm (Chiếm 5 phần) --}}
                <div class="md:col-span-5">
                    <div class="border border-gray-200 rounded-xl p-4 bg-white relative group overflow-hidden shadow-sm">
                        @if($product->sale_price)
                            <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md z-10">
                                -{{ round((($product->price - $product->sale_price)/$product->price)*100) }}%
                            </div>
                        @endif
                        
                        <div class="aspect-square flex items-center justify-center overflow-hidden bg-white">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="max-w-full max-h-full object-contain cursor-zoom-in transition duration-500 hover:scale-110" alt="{{ $product->name }}">
                            @else
                                <div class="text-gray-300 flex flex-col items-center">
                                    <i class="fas fa-image text-5xl mb-2"></i>
                                    <span class="text-sm">No Image</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 2. Thông tin chi tiết --}}
                <div class="md:col-span-7 flex flex-col h-full">
                    <h1 class="text-lg md:text-xl font-bold text-gray-900 leading-snug mb-3">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-5 text-sm">
                        {{-- <div class="w-16 h-1 bg-blue-600 rounded-full"></div> --}}
                        <span class="text-gray-500">Mã SP: <span class="font-mono text-gray-700 font-bold">{{ $product->id }}</span></span>
                    </div>

                    {{-- Khu vực giá --}}
                    <div class="mb-6 bg-gray-50 rounded-xl border border-gray-100 flex items-end gap-3">
                        @php
                            $currentPrice = $product->variants->count() > 0 ? $product->variants->first()->price : ($product->sale_price ?? $product->price);
                            $originalPrice = $product->sale_price ? $product->price : null;
                            if($product->variants->count() > 0) $originalPrice = null; 
                        @endphp

                        <span id="price-display" class="text-2xl font-bold text-red-600 leading-none">
                            Đơn giá: {{ number_format($currentPrice, 0, ',', '.') }} ₫
                        </span>
                        
                        @if($originalPrice)
                            <span class="text-sm text-gray-400 line-through mb-1">{{ number_format($originalPrice) }} ₫</span>
                        @endif
                    </div>

                    {{-- Chọn biến thể (Variants) --}}
                    @if($product->variants && $product->variants->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wide">
                            Chọn phiên bản: <span id="variant-name-display" class="font-normal text-blue-600 normal-case ml-1">{{ $product->variants->first()->name }}</span>
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->variants->sortBy('price') as $index => $variant)
                                <button type="button" 
                                        onclick="selectVariant(this, '{{ $variant->id }}', {{ $variant->price }}, '{{ $variant->name }}')"
                                        class="variant-btn px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 bg-white hover:border-blue-400 hover:text-blue-600 transition shadow-sm {{ $index === 0 ? 'active' : '' }}"
                                        data-id="{{ $variant->id }}">
                                    {{ $variant->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Thông tin bổ sung --}}
                    <div class="text-gray-600 text-sm leading-relaxed mb-8 bg-white rounded-lg">
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Thương hiệu: <strong class="text-gray-900">{{ $product->brand ?? 'Đang cập nhật' }}</strong></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Tình trạng: <span class="text-green-600 font-medium">Còn hàng</span></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Bảo hành chính hãng</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Nút mua hàng --}}
                    <div class="flex gap-4 mt-auto">
                        @php
                            $defaultVariantId = $product->variants->count() > 0 ? $product->variants->first()->id : '';
                            $addToCartUrl = route('add_to_cart', ['id' => $product->id, 'variant_id' => $defaultVariantId]);
                            $buyNowUrl = route('buy_now', ['id' => $product->id, 'variant_id' => $defaultVariantId]);
                        @endphp

                        <a href="{{ $addToCartUrl }}" id="btn-add-to-cart" class="flex-1 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3.5 rounded-xl text-sm uppercase tracking-wide transition flex items-center justify-center shadow-sm">
                            <i class="fas fa-cart-plus mr-2 text-lg"></i> Thêm vào giỏ
                        </a>
                        <a href="{{ $buyNowUrl }}" id="btn-buy-now" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3.5 rounded-xl text-sm uppercase tracking-wide transition flex items-center justify-center shadow-lg shadow-red-200 transform hover:-translate-y-0.5">
                            Mua ngay
                        </a>
                    </div>
                </div>
            </div>

            {{-- PRODUCT DESCRIPTION SECTION --}}
            <div class="mb-16">
                <div class="border-b border-gray-200 mb-6">
                    <h2 class="inline-block py-3 px-1 border-b-2 border-blue-600 text-blue-800 font-bold text-lg uppercase tracking-wide">
                        Chi tiết sản phẩm
                    </h2>
                </div>
                {{-- Nội dung CKEditor --}}
                <div class="product-description text-gray-700 leading-7 text-[15px]">
                    {!! $product->description ?? '<div class="p-8 text-center text-gray-400 bg-gray-50 rounded-lg italic">Đang cập nhật nội dung chi tiết...</div>' !!}
                </div>
            </div>

            {{-- RELATED PRODUCTS SLIDER --}}
            @if(isset($relatedProducts) && count($relatedProducts) > 0)
            <div class="select-none"> 
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 uppercase border-l-4 border-red-600 pl-3">Sản phẩm tương tự</h3>
                </div>
                
                <div class="relative w-full"> 
                    <div class="swiper mySwiper w-full overflow-hidden rounded-xl p-1 cursor-grab active:cursor-grabbing"> 
                        <div class="swiper-wrapper">
                            {{-- Loop giả lập slide để test hiển thị --}}
                            @for ($i = 0; $i < 4; $i++) 
                                @foreach($relatedProducts as $related)
                                <div class="swiper-slide h-auto">
                                    <div class="bg-white border border-gray-200 rounded-xl hover:shadow-xl hover:border-blue-300 transition-all duration-300 h-full flex flex-col group relative overflow-hidden">
                                        
                                        {{-- Badge Sale --}}
                                        @if($related->sale_price)
                                            <span class="absolute top-2 right-2 z-10 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                                -{{ round((($related->price - $related->sale_price)/$related->price)*100) }}%
                                            </span>
                                        @endif

                                        {{-- Image --}}
                                        <div class="relative pt-[100%] overflow-hidden bg-white p-6 border-b border-gray-50">
                                            <a href="{{ route('product.detail', $related->id) }}" class="absolute inset-0 flex items-center justify-center">
                                                @if($related->image)
                                                    <img src="{{ asset($related->image) }}" class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-110">
                                                @else
                                                    <i class="fas fa-image text-4xl text-gray-300"></i>
                                                @endif
                                            </a>
                                        </div>

                                        {{-- Content --}}
                                        <div class="p-4 flex-grow flex flex-col">
                                            <h4 class="text-sm font-bold text-gray-700 mb-2 line-clamp-2 hover:text-blue-600 transition h-10">
                                                <a href="{{ route('product.detail', $related->id) }}">{{ $related->name }}</a>
                                            </h4>
                                            
                                            <div class="mt-auto flex items-center justify-between">
                                                <span class="text-red-600 font-bold text-base">
                                                    {{ number_format($related->sale_price ?: $related->price, 0, ',', '.') }} ₫
                                                </span>
                                                <a href="{{ route('add_to_cart', $related->id) }}" class="w-8 h-8 rounded-full bg-gray-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm" title="Thêm vào giỏ">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- CỘT PHẢI (NHỎ): SIDEBAR --}}
        <div class="lg:col-span-3">
            <div class="sticky top-24">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h4 class="font-bold text-gray-800 uppercase text-sm border-l-4 border-blue-600 pl-3">Sản phẩm nổi bật</h4>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @if(isset($relatedProducts) && count($relatedProducts) > 0)
                            @foreach($relatedProducts->take(5) as $hotItem)
                            <a href="{{ route('product.detail', $hotItem->id) }}" class="flex gap-3 p-4 hover:bg-blue-50/50 transition group items-center">
                                <div class="w-16 h-16 border border-gray-200 rounded-lg bg-white p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset($hotItem->image) }}" class="max-w-full max-h-full object-contain" onerror="this.src='https://via.placeholder.com/100'">
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-gray-700 group-hover:text-blue-600 leading-snug mb-1 line-clamp-2">
                                        {{ $hotItem->name }}
                                    </h5>
                                    <span class="text-red-600 font-bold text-sm">
                                        {{ number_format($hotItem->sale_price ?: $hotItem->price, 0, ',', '.') }} ₫
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                {{-- Banner Quảng cáo (Nếu có) --}}
                {{-- <div class="mt-6 rounded-xl overflow-hidden shadow-md">
                    <img src="https://via.placeholder.com/300x400" class="w-full">
                </div> --}}
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // === 1. XỬ LÝ CHỌN BIẾN THỂ (VARIANTS) ===
    const baseUrlAddToCart = "{{ route('add_to_cart', $product->id) }}";
    const baseUrlBuyNow = "{{ route('buy_now', $product->id) }}";

    function selectVariant(btn, variantId, price, name) {
        // A. Đổi màu nút active
        document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // B. Cập nhật giá hiển thị
        const formattedPrice = 'Đơn giá: ' + new Intl.NumberFormat('vi-VN').format(price) + '₫';
        document.getElementById('price-display').innerText = formattedPrice;
        
        // C. Cập nhật tên hiển thị
        const nameDisplay = document.getElementById('variant-name-display');
        if(nameDisplay) nameDisplay.innerText = name;

        // D. Cập nhật link nút Mua hàng & Thêm giỏ
        // Nối thêm param ?variant_id=... vào URL
        document.getElementById('btn-add-to-cart').href = `${baseUrlAddToCart}?variant_id=${variantId}`;
        document.getElementById('btn-buy-now').href = `${baseUrlBuyNow}?variant_id=${variantId}`;
    }

    // === 2. SWIPER SLIDER ===
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const swiperEl = document.querySelector('.mySwiper');
            if (swiperEl) {
                new Swiper('.mySwiper', {
                    loop: true, 
                    observer: true,
                    observeParents: true,
                    grabCursor: true,      
                    simulateTouch: true,    
                    touchRatio: 1.5,        
                    resistance: true,       
                    resistanceRatio: 0.65,
                    speed: 800, 
                    autoplay: {
                        delay: 3000, 
                        disableOnInteraction: false, 
                    },
                    slidesPerView: 2, 
                    spaceBetween: 15, 
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