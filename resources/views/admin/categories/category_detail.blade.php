@extends('layouts.admin_layout')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Quản lý: <span class="text-blue-600">{{ $category->name }}</span></h3>
            <p class="text-sm text-gray-500 mt-1">
                Danh sách sản phẩm thuộc danh mục này
                @if($products->total() > 0)
                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs ml-2 font-bold">{{ $products->total() }} sản phẩm</span>
                @endif
            </p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg shadow-sm transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>

            <a href="{{ route('product.create', ['category_id' => $category->id]) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition transform active:scale-95">
                <i class="fas fa-plus mr-2"></i> Thêm Sản Phẩm
            </a>
        </div>
    </div>

    {{-- TABLE CONTENT --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200">
                        <th class="px-6 py-3 whitespace-nowrap text-center">Ảnh</th>
                        <th class="px-6 py-3 whitespace-nowrap">Tên sản phẩm</th>
                        <th class="px-6 py-3 whitespace-nowrap">Thương hiệu</th>
                        <th class="px-6 py-3 whitespace-nowrap">Giá bán</th>
                        {{-- MỚI: Cột kho hàng --}}
                        <th class="px-6 py-3 whitespace-nowrap text-center">Kho hàng</th>
                        <th class="px-6 py-3 whitespace-nowrap text-center">Trạng thái</th>
                        <th class="px-6 py-3 whitespace-nowrap text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    {{-- QUAN TRỌNG: Dùng $products thay vì $category->products để hỗ trợ phân trang --}}
                    @forelse($products as $product)
                    <tr class="hover:bg-blue-50/50 transition duration-150 cursor-pointer group"
                        onclick="window.location='{{ route('product.edit', $product->id) }}'"
                        title="Bấm để chỉnh sửa">
                        
                        {{-- Ảnh --}}
                        <td class="px-6 py-4 text-center">
                            <div class="w-12 h-12 rounded-lg border border-gray-200 overflow-hidden bg-white mx-auto flex items-center justify-center p-0.5">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="w-full h-full object-cover rounded-md" onerror="this.src='https://via.placeholder.com/50?text=Err'">
                                @else
                                    <i class="fas fa-image text-gray-300 text-xl"></i>
                                @endif
                            </div>
                        </td>

                        {{-- Tên & Mã --}}
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition line-clamp-2">{{ $product->name }}</div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-400 font-mono">ID: {{ $product->id }}</span>
                                @if($product->is_hot)
                                    <span class="text-[10px] font-bold text-red-600 bg-red-100 border border-red-200 px-1.5 py-0.5 rounded uppercase">HOT</span>
                                @endif
                            </div>
                        </td>

                        {{-- Thương hiệu --}}
                        <td class="px-6 py-4">
                            @if($product->brand)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $product->brand }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">---</span>
                            @endif
                        </td>

                        {{-- Giá bán --}}
                        <td class="px-6 py-4">
                            @if($product->variants && $product->variants->count() > 0)
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                <div class="font-bold text-indigo-600">
                                    @if($minPrice == $maxPrice)
                                        {{ number_format($minPrice, 0, ',', '.') }}đ
                                    @else
                                        {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}đ
                                    @endif
                                </div>
                            @else
                                <div class="font-bold text-gray-900">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                            @endif
                        </td>

                        {{-- MỚI: KHO HÀNG --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $totalStock = 0;
                                if($product->variants->count() > 0) {
                                    $totalStock = $product->variants->sum('quantity');
                                }
                            @endphp

                            @if($product->variants->count() > 0)
                                @if($totalStock == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        Hết hàng
                                    </span>
                                @elseif($totalStock < 5)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        Sắp hết ({{ $totalStock }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $totalStock }} cái
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400 text-xs italic">---</span>
                            @endif
                        </td>

                        {{-- Trạng thái --}}
                        <td class="px-6 py-4 text-center">
                            @if($product->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Hiện
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    Ẩn
                                </span>
                            @endif
                        </td>

                        {{-- Hành động --}}
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="inline-block relative z-10">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                                        title="Xóa"
                                        onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                                <p class="text-sm italic">Chưa có sản phẩm nào trong danh mục này.</p>
                                <a href="{{ route('product.create', ['category_id' => $category->id]) }}" class="mt-3 text-blue-600 hover:underline text-sm font-medium">Thêm ngay</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-center">
                {{ $products->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

@endsection