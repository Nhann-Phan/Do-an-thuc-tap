@extends('layouts.client_layout')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12 max-w-7xl"
style="padding-top: 1rem">
    
    {{-- Breadcrumb hiện đại --}}
    <nav class="flex text-sm text-gray-500 mb-8 font-medium items-center bg-gray-50 border-b border-gray-200 pb-4 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition flex items-center ml-10">
            <i class="fas fa-home mr-2"></i> Trang chủ
        </a>
        <span class="mx-3 text-gray-300 text-xs"><i class="fas fa-chevron-right"></i></span>
        <span class="text-gray-900 font-semibold">Tìm kiếm</span>
    </nav>

    {{-- Tiêu đề trang --}}
    <div class="mb-10">
        <h1 class="text-xl md:text-xl font-extrabold text-gray-900 tracking-tight mb-3">
            Kết quả tìm kiếm cho: <span class="text-blue-600">"{{ $keyword }}"</span>
        </h1>
        <p class="text-gray-500">Tìm thấy <span class="font-bold text-gray-900">{{ $products->total() }}</span> sản phẩm phù hợp.</p>
    </div>

    {{-- Khu vực hiển thị sản phẩm --}}
    @if($products->count() > 0)
        {{-- Lưới Grid: 2 cột mobile, 3 cột tablet, 4 cột PC --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8"
        style="gap: 5rem">
            @foreach($products as $item)
                {{-- Khung Product Card --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 overflow-hidden transition-all duration-300 flex flex-col group relative">
                    
                    {{-- Badge Giảm giá (Nếu có) --}}
                    @if($item->sale_price && $item->sale_price < $item->price)
                        <div class="absolute top-3 left-3 z-10 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                            -{{ round((($item->price - $item->sale_price)/$item->price)*100) }}%
                        </div>
                    @endif

                    {{-- Khung Ảnh (Ép tỉ lệ vuông 1:1) --}}
                    <a href="{{ route('product.detail', $item->id) }}" class="relative block pt-[100%] bg-gray-50/30 overflow-hidden">
                        <img src="{{ $item->image ? asset($item->image) : 'https://placehold.co/400x400?text=No+Image' }}" 
                             alt="{{ $item->name }}" 
                             class="absolute inset-0 w-full h-full object-contain p-4">
                    </a>
                    
                    {{-- Nội dung thông tin --}}
                    <div class="p-4 md:p-5 flex flex-col flex-grow border-t border-gray-50">
                        {{-- Tên sản phẩm --}}
                        <a href="{{ route('product.detail', $item->id) }}" class="flex-grow mb-3">
                            <h3 class="text-sm md:text-base font-bold text-gray-800 leading-snug line-clamp-2 hover:text-blue-600 transition-colors" title="{{ $item->name }}">
                                {{ $item->name }}
                            </h3>
                        </a>
                        
                        {{-- Hàng Giá + Nút Giỏ Hàng --}}
                        <div class="mt-auto flex items-end justify-between">
                            <div>
                                @if($item->sale_price && $item->sale_price < $item->price)
                                    <div class="text-xs text-gray-400 line-through mb-0.5">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                                    <div class="text-lg font-extrabold text-red-600">{{ number_format($item->sale_price, 0, ',', '.') }}đ</div>
                                @else
                                    <div class="text-lg font-extrabold text-red-600">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                                @endif
                            </div>
                            
                            {{-- Nút Add to cart --}}
                            <a href="{{ route('add_to_cart', $item->id) }}" 
                               class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-colors duration-300 shadow-sm active:scale-95"
                               title="Thêm vào giỏ hàng">
                                <i class="fas fa-cart-plus text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Thanh phân trang --}}
        <div class="mt-12 flex justify-center">
            {{ $products->appends(['q' => $keyword])->links() }}
        </div>
        
    @else
        {{-- Giao diện khi trống --}}
        <div class="text-center py-24 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm mt-4">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-box-open text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Không tìm thấy kết quả</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Chúng tôi không tìm thấy sản phẩm nào khớp với từ khóa <span class="font-bold text-gray-800">"{{ $keyword }}"</span>. Vui lòng thử lại bằng từ khóa khác nhé.</p>
            <a href="{{ route('product.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 active:scale-95">
                <i class="fas fa-store mr-2"></i> Quay lại cửa hàng
            </a>
        </div>
    @endif

</div>
@endsection