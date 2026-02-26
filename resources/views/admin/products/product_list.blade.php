@extends('layouts.admin_layout')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 m-0">Quản lý kho sản phẩm</h3>
        <p class="text-sm text-gray-500 mt-1">Danh sách tất cả sản phẩm hiện có trong hệ thống</p>
    </div>
    
    {{-- Sửa link này trỏ về trang tạo mới (nếu bạn muốn chọn danh mục trước thì giữ nguyên link cũ) --}}
    <a href="{{ route('product.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition flex items-center transform active:scale-95 text-sm uppercase">
        <i class="fas fa-plus mr-2"></i> Thêm sản phẩm mới
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    {{-- TOOLBAR (FILTER & SEARCH) --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
        
        {{-- Filter Label --}}
        <div class="flex items-center text-gray-700 font-medium text-sm">
            <i class="fas fa-filter text-blue-500 mr-2"></i>
            @if(isset($category))
                <span>Lọc theo: <span class="text-blue-600 font-bold ml-1">{{ $category->name }}</span></span>
                <a href="{{ route('product.index_admin') }}" class="ml-3 text-xs bg-white border border-gray-300 hover:bg-gray-100 text-gray-600 px-2 py-1 rounded transition flex items-center shadow-sm">
                    <i class="fas fa-times mr-1"></i> Bỏ lọc
                </a>
            @else
                <span>Tất cả sản phẩm</span>
            @endif
        </div>

        {{-- Search Form --}}
        <div class="w-full md:w-1/3">
            <form action="{{ route('product.index_admin') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition"></i>
                </div>
                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                       class="w-full border border-gray-300 pl-10 pr-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm bg-white" 
                       placeholder="Tìm kiếm tên sản phẩm...">
            </form>
        </div>
    </div>
    
    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200">
                    <th class="px-6 py-3 w-24 text-center">Ảnh</th>
                    <th class="px-6 py-3">Tên sản phẩm</th>
                    <th class="px-6 py-3 whitespace-nowrap">Danh mục</th>
                    <th class="px-6 py-3 whitespace-nowrap">Giá bán</th>
                    {{-- THÊM CỘT KHO HÀNG --}}
                    <th class="px-6 py-3 whitespace-nowrap text-center">Kho hàng</th> 
                    <th class="px-6 py-3 text-center whitespace-nowrap">Trạng thái</th> 
                    <th class="px-6 py-3 text-right whitespace-nowrap">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
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

                    {{-- Tên & Badges --}}
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 group-hover:text-blue-600 transition mb-1 line-clamp-2">{{ $product->name }}</div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] text-gray-400 font-mono">ID: {{ $product->id }}</span>
                            
                            @if($product->is_hot)
                                <span class="text-[10px] font-bold text-red-600 bg-red-100 border border-red-200 px-1.5 py-0.5 rounded uppercase tracking-wider">HOT</span>
                            @endif

                            @if($product->brand)
                                <span class="text-[10px] text-gray-600 bg-gray-100 border border-gray-200 px-1.5 py-0.5 rounded uppercase">{{ $product->brand }}</span>
                            @endif
                        </div>
                    </td>

                    {{-- Danh mục --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->category)
                            <a href="{{ route('admin.category.products', $product->category->id) }}" 
                               class="text-blue-600 hover:text-blue-800 hover:underline relative z-10 font-medium" 
                               onclick="event.stopPropagation()">
                                {{ $product->category->name }}
                            </a>
                        @else
                            <span class="text-gray-400 italic">Chưa phân loại</span>
                        @endif
                    </td>

                    {{-- Giá bán --}}
                    <td class="px-6 py-4 whitespace-nowrap">
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
                            <div class="text-xs text-gray-400">{{ $product->variants->count() }} phiên bản</div>
                        @else
                            <div class="font-bold text-gray-900">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                        @endif
                    </td>

                    {{-- MỚI: KHO HÀNG (Tính tổng quantity của các variants) --}}
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
                            @elseif($totalStock < 10)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    Sắp hết ({{ $totalStock }})
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $totalStock }} sp
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400 text-xs italic">---</span>
                        @endif
                    </td>

                    {{-- Trạng thái --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                Hiện
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                Ẩn
                            </span>
                        @endif
                    </td>

                    {{-- Hành động --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="inline-block relative z-10">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-white border border-gray-200 text-gray-400 hover:border-red-200 hover:text-red-600 hover:bg-red-50 rounded-lg flex items-center justify-center transition shadow-sm" 
                                    title="Xóa"
                                    onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa không? Dữ liệu tồn kho cũng sẽ bị xóa!')">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p>Không tìm thấy sản phẩm nào.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- PAGINATION --}}
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-center">
        {{ $products->links('pagination::tailwind') }}
    </div>
</div>
@endsection