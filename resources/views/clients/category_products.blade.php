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
                @if($currentCategory->parent)
                    <li class="flex items-center">
                        <a href="{{ route('frontend.category.show', $currentCategory->parent_id) }}" class="text-gray-500 hover:text-blue-600 transition">{{ $currentCategory->parent->name }}</a>
                        <span class="mx-2 text-gray-400">/</span>
                    </li>
                @endif
                <li class="text-blue-600 font-bold" aria-current="page">{{ $currentCategory->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="bg-white p-4 border-b font-bold uppercase text-gray-700 flex items-center">
                    <i class="fas fa-list-ul mr-2 text-blue-600"></i> Danh mục liên quan
                </div>
                <div class="flex flex-col">
                    @foreach($menuCategories as $cat)
                        <a href="{{ route('frontend.category.show', $cat->id) }}" class="px-4 py-3 border-b border-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center {{ $currentCategory->id == $cat->id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600' }}">
                            <i class="{{ $cat->icon ?? 'fas fa-caret-right' }} mr-2 text-gray-400 text-xs"></i> {{ $cat->name }}
                        </a>
                        
                        {{-- Hiển thị con nếu đang ở danh mục cha hoặc chính nó --}}
                        @if($currentCategory->id == $cat->id || $currentCategory->parent_id == $cat->id)
                            @foreach($cat->children as $child)
                                <a href="{{ route('frontend.category.show', $child->id) }}" class="pl-10 pr-4 py-2 border-b border-gray-50 text-sm hover:text-blue-600 flex items-center {{ $currentCategory->id == $child->id ? 'text-blue-600 font-bold' : 'text-gray-500' }}">
                                    <span class="mr-2">•</span> {{ $child->name }}
                                </a>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-span-1 lg:col-span-3">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 pb-2 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                    {{ $currentCategory->name }} 
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-gray-100 bg-gray-500 rounded-full">{{ $products->count() }}</span>
                </h1>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Sắp xếp:</span>
                    <select class="border border-gray-300 rounded text-sm px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option>Mới nhất</option>
                        <option>Giá tăng dần</option>
                        <option>Giá giảm dần</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($products as $product)
                <div class="group bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-xl hover:border-blue-400 transition duration-300 flex flex-col h-full relative overflow-hidden">
                    
                    @if($product->is_hot)
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow z-10">-HOT</span>
                    @endif

                    <div class="relative overflow-hidden p-4 bg-white h-48 flex items-center justify-center">
                        <a href="{{ route('product.detail', $product->id) }}" class="block w-full h-48 flex items-center justify-center bg-white rounded-t-lg overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" 
                                     class="w-full h-full object-contain hover:scale-105 transition duration-500" 
                                     alt="{{ $product->name }}"
                                     onerror="this.onerror=null;this.src='https://via.placeholder.com/300x300?text=No+Image'">
                            @else
                                <img src="https://via.placeholder.com/300x300?text=No+Image" class="w-full h-full object-contain opacity-50">
                            @endif
                        </a>
                    </div>

                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-sm font-bold text-gray-700 mb-2 line-clamp-2 h-10 group-hover:text-blue-600 transition">
                            <a href="{{ route('product.detail', $product->id) }}" title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <div class="mt-auto pt-2 border-t border-gray-50">
                            @if($product->sale_price)
                                <div class="flex flex-col">
                                    <span class="text-red-600 font-bold text-lg">{{ number_format($product->sale_price) }}đ</span>
                                    <span class="text-gray-400 text-xs line-through">{{ number_format($product->price) }}đ</span>
                                </div>
                            @else
                                <span class="text-red-600 font-bold text-lg">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>
                    </div>

                </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <div class="text-gray-300 mb-4 text-6xl"><i class="fas fa-box-open"></i></div>
                        <h5 class="text-gray-500 font-medium mb-4">Chưa có sản phẩm nào trong danh mục này</h5>
                        <a href="/" class="inline-block px-6 py-2 border border-blue-600 text-blue-600 font-bold rounded-full hover:bg-blue-600 hover:text-white transition">
                            Quay về trang chủ
                        </a>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</div>

@endsection