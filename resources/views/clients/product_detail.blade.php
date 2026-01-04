@extends('layouts.client_layout')

@section('content')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Tối ưu hóa chuyển động */
        .swiper-wrapper { transition-timing-function: ease-out; }
        .swiper-slide { transform: translate3d(0, 0, 0); backface-visibility: hidden; }
        .cursor-grab { cursor: grab; }
        .cursor-grabbing { cursor: grabbing; }
        
        /* Style cho nút biến thể */
        .variant-btn.active {
            border-color: #2563eb; /* Blue-600 */
            background-color: #eff6ff; /* Blue-50 */
            color: #1d4ed8; /* Blue-700 */
            font-weight: bold;
        }
    </style>
@endpush

<div class="bg-white py-3 border-b border-gray-200 mb-6">
    <div class="container mx-auto px-4 text-xs font-bold text-gray-500 tracking-wide flex items-center gap-2">
        <a href="/" class="hover:text-blue-600 transition"><i class="fas fa-home mr-1"></i> {{ __('messages.home') }}</a>
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

                    {{-- HIỂN THỊ GIÁ --}}
                    <div class="mb-5 bg-gray-50 p-3 rounded border border-gray-100">
                        {{-- Logic: Nếu có biến thể thì lấy giá biến thể đầu tiên, không thì lấy giá thường --}}
                        @php
                            $currentPrice = $product->variants->count() > 0 ? $product->variants->first()->price : ($product->sale_price ?? $product->price);
                            $originalPrice = $product->sale_price ? $product->price : null;
                            
                            // Nếu đang hiện giá variant thì không hiện giá gạch ngang (vì logic phức tạp)
                            if($product->variants->count() > 0) $originalPrice = null; 
                        @endphp

                        <span id="price-display" class="text-3xl font-bold text-red-600">
                            {{ number_format($currentPrice) }} ₫
                        </span>
                        
                        @if($originalPrice)
                            <span class="text-sm text-gray-400 line-through ml-2">{{ number_format($originalPrice) }} ₫</span>
                        @endif
                    </div>

                    {{-- === KHU VỰC CHỌN BIẾN THỂ (VARIANTS) === --}}
                    @if($product->variants && $product->variants->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-2">Chọn phiên bản: <span id="variant-name-display" class="font-normal text-blue-600">{{ $product->variants->first()->name }}</span></h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->variants->sortBy('price') as $index => $variant)
                                <button type="button" 
                                        onclick="selectVariant(this, '{{ $variant->id }}', {{ $variant->price }}, '{{ $variant->name }}')"
                                        class="variant-btn border border-gray-300 px-4 py-2 rounded text-sm text-gray-700 hover:border-blue-400 transition {{ $index === 0 ? 'active' : '' }}"
                                        data-id="{{ $variant->id }}">
                                    {{ $variant->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    {{-- === KẾT THÚC === --}}

                    <div class="text-gray-600 text-sm leading-relaxed mb-6">
                        <ul class="space-y-1.5 list-disc pl-4 marker:text-blue-500">
                            <li>
                                <strong>Thương hiệu:</strong> 
                                <span class="font-bold text-dark-700 ">
                                    {{ $product->brand ?? 'Đang cập nhật' }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex gap-3 mt-auto">
                        {{-- Logic Add to Cart: Mặc định lấy ID variant đầu tiên nếu có --}}
                        @php
                            $defaultVariantId = $product->variants->count() > 0 ? $product->variants->first()->id : '';
                            $addToCartUrl = route('add_to_cart', ['id' => $product->id, 'variant_id' => $defaultVariantId]);
                            $buyNowUrl = route('buy_now', ['id' => $product->id, 'variant_id' => $defaultVariantId]);
                        @endphp

                        <a href="{{ $addToCartUrl }}" id="btn-add-to-cart" class="flex-1 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3 rounded text-sm uppercase tracking-wide transition flex items-center justify-center">
                            <i class="fas fa-cart-plus mr-2"></i> Thêm giỏ
                        </a>
                        <a href="{{ $buyNowUrl }}" id="btn-buy-now" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded text-sm uppercase tracking-wide transition flex items-center justify-center shadow-lg shadow-red-200">
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
                        </div>
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
    // === 1. XỬ LÝ CHỌN BIẾN THỂ (VARIANTS) ===
    const baseUrlAddToCart = "{{ route('add_to_cart', $product->id) }}";
    const baseUrlBuyNow = "{{ route('buy_now', $product->id) }}";

    function selectVariant(btn, variantId, price, name) {
        // A. Đổi màu nút active
        document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // B. Cập nhật giá hiển thị
        const formattedPrice = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
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