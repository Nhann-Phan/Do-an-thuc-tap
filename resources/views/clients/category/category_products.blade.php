@extends('layouts.client_layout')

@section('content')

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
            
            @if($currentCategory->parent)
                <li>
                    <a href="{{ route('frontend.category.show', $currentCategory->parent_id) }}" class="hover:text-blue-600 transition">
                        {{ $currentCategory->parent->name }}
                    </a>
                </li>
                <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            @endif
            
            <li class="text-gray-900 font-bold truncate">{{ $currentCategory->name }}</li>
        </ol>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div class="container mx-auto px-4 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- SIDEBAR: DANH MỤC (Ẩn trên Mobile, Hiện trên Large screen) --}}
        <aside class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                <div class="bg-gray-50 px-5 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 uppercase text-sm flex items-center tracking-wide">
                        <i class="fas fa-list-ul mr-2 text-blue-600"></i> Danh mục sản phẩm
                    </h3>
                </div>
                
                <div class="flex flex-col py-2">
                    @foreach($menuCategories as $cat)
                        {{-- Danh mục cha --}}
                        <a href="{{ route('frontend.category.show', $cat->id) }}" 
                           class="px-5 py-3 text-sm font-medium transition flex items-center justify-between group 
                                  {{ $currentCategory->id == $cat->id ? 'text-blue-600 bg-blue-50/50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                            <span class="flex items-center">
                                <i class="{{ $cat->icon ?? 'fas fa-folder' }} mr-3 text-xs {{ $currentCategory->id == $cat->id ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i> 
                                {{ $cat->name }}
                            </span>
                            @if($cat->children && $cat->children->count() > 0)
                                <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                            @endif
                        </a>
                        
                        {{-- Danh mục con (Chỉ hiện khi đang ở trong nhóm cha này) --}}
                        @if(($currentCategory->id == $cat->id || $currentCategory->parent_id == $cat->id) && $cat->children && $cat->children->count() > 0)
                            <div class="bg-gray-50/30 border-t border-b border-gray-100 py-1">
                                @foreach($cat->children as $child)
                                    <a href="{{ route('frontend.category.show', $child->id) }}" 
                                       class="pl-12 pr-5 py-2 text-xs transition flex items-center relative
                                              {{ $currentCategory->id == $child->id ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-500 hover:text-blue-600' }}">
                                        @if($currentCategory->id == $child->id)
                                            <span class="absolute left-9 w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        @endif
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- MAIN PRODUCT LIST --}}
        <div class="col-span-1 lg:col-span-3">
            
            {{-- Header & Sort --}}
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-200 gap-4">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    {{ $currentCategory->name }} 
                    <span class="ml-3 inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $products->count() }} sản phẩm
                    </span>
                </h1>
                
                <div class="flex items-center text-sm">
                    <label for="sort" class="text-gray-500 mr-2">Sắp xếp:</label>
                    <div class="relative">
                        <select id="sort" class="appearance-none bg-white border border-gray-300 text-gray-700 py-1.5 pl-3 pr-8 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm cursor-pointer hover:border-gray-400 transition">
                            <option>Mới nhất</option>
                            <option>Giá tăng dần</option>
                            <option>Giá giảm dần</option>
                            <option>Tên A-Z</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                @forelse($products as $product)
                <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-xl hover:border-blue-300 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    
                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                        @if($product->is_hot)
                            <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm uppercase tracking-wider animate-pulse">HOT</span>
                        @endif
                        @if($product->sale_price)
                            <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                -{{ round((($product->price - $product->sale_price)/$product->price)*100) }}%
                            </span>
                        @endif
                    </div>

                    {{-- Image --}}
                    <div class="relative pt-[100%] overflow-hidden bg-white border-b border-gray-50 group-hover:border-gray-100 transition-colors">
                        <a href="{{ route('product.detail', $product->id) }}" class="absolute inset-0 flex items-center justify-center p-6">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" 
                                     class="max-h-full max-w-full object-contain transform group-hover:scale-110 transition duration-500 ease-out" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="text-gray-300 flex flex-col items-center">
                                    <i class="fas fa-image text-4xl mb-1 opacity-50"></i>
                                </div>
                            @endif
                        </a>
                        
                        {{-- Quick Action (Optional) --}}
                        <div class="absolute bottom-0 left-0 w-full p-2 translate-y-full group-hover:translate-y-0 transition duration-300">
                            <a href="{{ route('product.detail', $product->id) }}" class="block w-full bg-blue-600/90 hover:bg-blue-700 text-white text-center text-xs font-bold py-2 rounded shadow-md backdrop-blur-sm uppercase tracking-wide">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex flex-col flex-grow">
                        {{-- Name --}}
                        <h3 class="text-sm font-bold text-gray-700 mb-2 line-clamp-2 h-10 group-hover:text-blue-600 transition leading-snug">
                            <a href="{{ route('product.detail', $product->id) }}" title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        {{-- Price Logic --}}
                        <div class="mt-auto pt-3 border-t border-gray-50 flex flex-col justify-end min-h-[3rem]">
                            @if($product->variants && $product->variants->count() > 0)
                                {{-- Có biến thể --}}
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                <span class="text-red-600 font-bold text-base block">
                                    @if($minPrice == $maxPrice)
                                        {{ number_format($minPrice, 0, ',', '.') }}đ
                                    @else
                                        {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}đ
                                    @endif
                                </span>
                            @else
                                {{-- Không biến thể --}}
                                @if($product->sale_price)
                                    <div>
                                        <span class="text-red-600 font-bold text-base block">{{ number_format($product->sale_price) }}đ</span>
                                        <span class="text-gray-400 text-xs line-through block">{{ number_format($product->price) }}đ</span>
                                    </div>
                                @else
                                    <span class="text-red-600 font-bold text-base block">{{ number_format($product->price) }}đ</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-sm mb-4 text-gray-300">
                        <i class="fas fa-box-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Chưa có sản phẩm</h3>
                    <p class="text-gray-500 text-sm mb-6">Danh mục này hiện chưa có sản phẩm nào được cập nhật.</p>
                    <a href="/" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Quay về trang chủ
                    </a>
                </div>
                @endforelse
            </div>
            
            {{-- Pagination (Nếu có) --}}
            {{-- <div class="mt-12 flex justify-center">
                {{ $products->links('pagination::tailwind') }}
            </div> --}}
            
        </div>
    </div>
</div>

@endsection