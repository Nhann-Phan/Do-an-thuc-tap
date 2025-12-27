@extends('layouts.client_layout')

@section('content')

<div class="bg-gray-50 py-3 border-b shadow-sm">
    <div class="container mx-auto px-4">
        <nav class="text-sm font-medium text-gray-500">
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

<div class="container mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
            
            <div class="relative group">
                <div class="border border-gray-100 rounded-lg p-4 bg-white flex items-center justify-center h-full min-h-[400px] shadow-inner">
                    @if($product->is_hot)
                        <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded shadow z-10">HOT</span>
                    @endif

                    @if($product->image)
                        <img src="{{ asset($product->image) }}" 
                             class="w-full h-auto max-h-[450px] object-contain hover:scale-105 transition duration-500 cursor-pointer" 
                             alt="{{ $product->name }}"
                             onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                    @else
                        <img src="https://via.placeholder.com/500x500?text=No+Image" class="w-full h-auto object-contain opacity-50">
                    @endif
                </div>
            </div>

            <div class="flex flex-col justify-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2 leading-tight">{{ $product->name }}</h1>
                
                <div class="flex items-center space-x-4 mb-6 text-sm text-gray-500">
                    <span class="bg-gray-100 px-2 py-1 rounded">Mã SP: <span class="font-bold text-gray-700">SP-{{ $product->id }}</span></span>
                    <span class="text-gray-300">|</span>
                    <span class="text-green-600 font-bold flex items-center"><i class="fas fa-check-circle mr-1"></i>Còn hàng</span>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                    @if($product->sale_price)
                        <div class="flex items-end space-x-3">
                            <span class="text-4xl font-bold text-red-600">{{ number_format($product->sale_price) }}đ</span>
                            <span class="text-lg text-gray-400 line-through mb-1">{{ number_format($product->price) }}đ</span>
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded mb-2">Giảm giá</span>
                        </div>
                    @else
                        <span class="text-4xl font-bold text-red-600">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>

                <div class="text-gray-600 leading-relaxed mb-8">
                    <p class="font-bold mb-2 text-gray-800">Thông tin cơ bản:</p>
                    <div class="text-sm line-clamp-4">
                        {{ Str::limit(html_entity_decode(strip_tags($product->description)), 250) ?? 'Đang cập nhật...' }}
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-auto">
                    <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 flex items-center justify-center group">
                        <i class="fas fa-shopping-cart mr-2 group-hover:animate-bounce"></i> THÊM VÀO GIỎ
                    </button>
                    <button class="flex-1 bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 font-bold py-4 px-6 rounded-lg shadow hover:shadow-md transition flex items-center justify-center">
                        <i class="fas fa-phone-alt mr-2"></i> TƯ VẤN NGAY
                    </button>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-gray-100 text-xs text-gray-500 font-medium">
                    <div class="flex items-center"><i class="fas fa-shield-alt text-blue-500 mr-2 text-lg"></i> Bảo hành chính hãng</div>
                    <div class="flex items-center"><i class="fas fa-truck text-blue-500 mr-2 text-lg"></i> Giao hàng toàn quốc</div>
                    <div class="flex items-center"><i class="fas fa-undo text-blue-500 mr-2 text-lg"></i> Đổi trả dễ dàng</div>
                    <div class="flex items-center"><i class="fas fa-headset text-blue-500 mr-2 text-lg"></i> Hỗ trợ kỹ thuật 24/7</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 uppercase flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i> Chi tiết sản phẩm
            </h3>
        </div>
        
        <div class="p-6 md:p-8 text-gray-700 leading-relaxed description-content">
            @if($product->description)
                {!! $product->description !!}
            @else
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <i class="fas fa-file-alt text-4xl mb-2"></i>
                    <p>Nội dung chi tiết đang được cập nhật.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Bắt buộc ảnh trong bài viết không được to hơn màn hình */
    .description-content img {
        max-width: 100%;
        height: auto !important;
        border-radius: 8px;
        margin: 15px 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Định dạng các thẻ cơ bản nếu CKEditor chưa style */
    .description-content h2 { font-size: 1.5rem; font-weight: bold; margin-top: 1.5em; margin-bottom: 0.5em; color: #1e3a8a; }
    .description-content h3 { font-size: 1.25rem; font-weight: bold; margin-top: 1.2em; margin-bottom: 0.5em; color: #1e40af; }
    .description-content p { margin-bottom: 1em; line-height: 1.7; }
    .description-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1em; }
    .description-content ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1em; }
</style>

@endsection