@extends('layouts.client_layout')

@section('content')

<div class="bg-gray-50 py-2.5 border-b shadow-sm font-sans">
    <div class="container mx-auto px-4">
        <nav class="text-xs font-medium text-gray-500">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="/" class="text-gray-500 hover:text-blue-600 transition"><i class="fas fa-home mr-1"></i> Trang chủ</a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                @if($product->category)
                    <li class="flex items-center">
                        <a href="{{ route('frontend.category.show', $product->category_id) }}" class="text-gray-500 hover:text-blue-600 transition">
                            {{ $product->category->name }}
                        </a>
                        <span class="mx-2 text-gray-400">/</span>
                    </li>
                @endif
                <li class="text-blue-600 font-bold truncate max-w-xs" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-6 font-sans">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 md:p-5">
            
            <div class="relative group">
                <div class="border border-gray-100 rounded-lg p-2 bg-white flex items-center justify-center h-full min-h-[300px] shadow-inner">
                    @if($product->is_hot)
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow z-10 uppercase tracking-wider">HOT</span>
                    @endif

                    @if($product->image)
                        <img src="{{ asset($product->image) }}" 
                             class="w-full h-auto max-h-[350px] object-contain hover:scale-105 transition duration-500 cursor-pointer" 
                             alt="{{ $product->name }}"
                             onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                    @else
                        <img src="https://via.placeholder.com/500x500?text=No+Image" class="w-full h-auto object-contain opacity-50">
                    @endif
                </div>
            </div>

            <div class="flex flex-col justify-center">
                <h1 class="text-xl font-bold text-gray-800 mb-2 leading-tight tracking-tight">{{ $product->name }}</h1>
                
                <div class="flex items-center space-x-3 mb-4 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <span class="bg-gray-100 px-2 py-0.5 rounded">Mã SP: <span class="text-gray-700">{{ $product->id }}</span></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-green-600 flex items-center"><i class="fas fa-check-circle mr-1"></i>Còn hàng</span>
                </div>

                <div class="mb-4 p-2.5 bg-gray-50 rounded-lg border border-gray-100 inline-block w-full md:w-auto">
                    @if($product->sale_price)
                        <div class="flex items-baseline space-x-2">
                            <span class="text-2xl font-bold text-red-600">{{ number_format($product->sale_price) }}đ</span>
                            <span class="text-xs text-gray-400 line-through">{{ number_format($product->price) }}đ</span>
                        </div>
                    @else
                        <span class="text-2xl font-bold text-red-600">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>

                <div class="text-gray-600 leading-relaxed mb-5 text-sm">
                    <p class="font-bold mb-1 text-gray-800 text-sm">Thông tin cơ bản:</p>
                    <div class="line-clamp-4 text-[13px]">
                        {{ Str::limit(html_entity_decode(strip_tags($product->description)), 250) ?? 'Đang cập nhật...' }}
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 mt-auto">
                    <a href="{{ route('add_to_cart', $product->id) }}" 
                       class="flex-1 py-2 px-4 border-2 border-blue-600 text-blue-600 font-bold text-xs rounded-lg hover:bg-blue-50 transition text-center uppercase tracking-wide flex items-center justify-center">
                        <i class="fas fa-cart-plus mr-1.5"></i> Thêm giỏ hàng
                    </a>

                    <a href="{{ route('buy_now', $product->id) }}" 
                       class="flex-1 py-2 px-4 bg-red-600 border-2 border-red-600 text-white font-bold text-xs rounded-lg hover:bg-red-700 hover:shadow transition text-center uppercase tracking-wide flex items-center justify-center">
                        <i class="fas fa-check-circle mr-1.5"></i> Mua ngay
                    </a>
                </div>
                
                <div class="grid grid-cols-2 gap-x-2 gap-y-1 mt-4 pt-4 border-t border-gray-100 text-[11px] text-gray-500 font-medium">
                    <div class="flex items-center"><i class="fas fa-shield-alt text-blue-500 mr-1.5"></i> Bảo hành chính hãng</div>
                    <div class="flex items-center"><i class="fas fa-truck text-blue-500 mr-1.5"></i> Giao hàng toàn quốc</div>
                    <div class="flex items-center"><i class="fas fa-undo text-blue-500 mr-1.5"></i> Đổi trả dễ dàng</div>
                    <div class="flex items-center"><i class="fas fa-headset text-blue-500 mr-1.5"></i> Hỗ trợ 24/7</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-200">
            <h3 class="text-sm font-bold text-gray-800 uppercase flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i> Chi tiết sản phẩm
            </h3>
        </div>
        
        <div class="p-4 md:p-5 text-gray-700 leading-relaxed description-content text-sm">
            @if($product->description)
                {!! $product->description !!}
            @else
                <div class="flex flex-col items-center justify-center py-6 text-gray-400">
                    <i class="fas fa-file-alt text-2xl mb-2"></i>
                    <p class="text-xs">Nội dung đang cập nhật.</p>
                </div>
            @endif
        </div>
    </div>

    @if(isset($relatedProducts) && count($relatedProducts) > 0)
    <div>
        <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase border-l-4 border-blue-600 pl-2">Sản phẩm tương tự</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @foreach($relatedProducts as $related)
            <div class="bg-white border rounded shadow-sm hover:shadow transition-shadow duration-300 overflow-hidden group">
                <div class="relative pt-[100%] overflow-hidden bg-gray-50">
                    <a href="{{ route('product.detail', $related->id) }}" class="absolute inset-0 flex items-center justify-center p-2">
                        @if($related->image)
                            <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300">
                        @else
                            <img src="https://via.placeholder.com/300x300?text=No+Image" class="max-h-full max-w-full opacity-50">
                        @endif
                    </a>
                    @if($related->is_hot)
                        <span class="absolute top-1 left-1 bg-red-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded">HOT</span>
                    @endif
                </div>

                <div class="p-2">
                    <h4 class="text-[11px] font-bold text-gray-800 mb-1 line-clamp-2 h-7 leading-tight">
                        <a href="{{ route('product.detail', $related->id) }}" class="hover:text-blue-600 transition">{{ $related->name }}</a>
                    </h4>
                    
                    <div class="flex justify-between items-end mt-1">
                        <span class="text-red-600 font-bold text-xs">{{ number_format($related->sale_price ?: $related->price) }}đ</span>
                        
                        <a href="{{ route('add_to_cart', $related->id) }}" class="text-blue-600 hover:bg-blue-50 p-1 rounded transition" title="Thêm vào giỏ">
                            <i class="fas fa-cart-plus text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<style>
    .description-content img { max-width: 100%; height: auto !important; border-radius: 4px; margin: 10px 0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
    .description-content h2 { font-size: 1.1rem; font-weight: 700; margin-top: 1.2em; margin-bottom: 0.4em; color: #1e3a8a; }
    .description-content h3 { font-size: 1rem; font-weight: 700; margin-top: 1em; margin-bottom: 0.4em; color: #1e40af; }
    .description-content p { margin-bottom: 0.6em; line-height: 1.5; font-size: 13px; }
    .description-content ul, .description-content ol { padding-left: 1.2em; margin-bottom: 0.6em; }
    .description-content li { margin-bottom: 0.3em; }
</style>

@endsection