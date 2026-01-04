@extends('layouts.admin_layout')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="font-sans">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Quản lý kho sản phẩm</h3>
            <p class="text-sm text-gray-500">Danh sách tất cả sản phẩm hiện có trong hệ thống</p>
        </div>
        
        <a href="{{ route('categories.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded shadow transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Thêm sản phẩm mới
        </a>
    </div>

    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center text-gray-700 font-medium text-sm">
                <i class="fas fa-filter text-blue-500 mr-2"></i>
                @if(isset($category))
                    <span>Lọc theo: <span class="text-red-600 font-bold">{{ $category->name }}</span></span>
                    <a href="{{ route('product.index_admin') }}" class="ml-3 text-xs bg-gray-200 hover:bg-gray-300 text-gray-600 px-2 py-1 rounded transition">
                        <i class="fas fa-times"></i> Bỏ lọc
                    </a>
                @else
                    <span>Tất cả sản phẩm</span>
                @endif
            </div>

            <div class="w-full md:w-1/3">
                <form action="{{ route('product.index_admin') }}" method="GET" class="relative">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                           class="w-full border border-gray-300 pl-10 pr-4 py-2 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 transition text-sm" 
                           placeholder="Tìm kiếm...">
                    <div class="absolute top-0 left-0 pt-2.5 pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm font-bold border-b">
                        <th class="p-4 text-center w-20">Ảnh</th>
                        <th class="p-4">Tên sản phẩm</th>
                        <th class="p-4">Danh mục</th>
                        <th class="p-4">Thương hiệu</th>
                        <th class="p-4">Giá bán</th>
                        <th class="p-4 text-center">Trạng thái</th> 
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($products as $product)
                    <tr class="hover:bg-blue-50 transition duration-150 cursor-pointer group" 
                        onclick="window.location='{{ route('product.edit', $product->id) }}'"
                        title="Bấm để chỉnh sửa">
                        
                        <td class="p-4 text-center">
                            <div class="w-12 h-12 rounded border border-gray-200 overflow-hidden bg-white mx-auto flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/50?text=Err'">
                                @else
                                    <i class="fas fa-image text-gray-300"></i>
                                @endif
                            </div>
                        </td>

                        <td class="p-4 align-middle">
                            <div class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $product->name }}</div>
                            <div class="flex flex-wrap items-center mt-1 gap-2">
                                <span class="text-xs text-gray-500">ID: {{ $product->id }}</span>
                                
                                @if($product->is_hot)
                                    <span class="text-xs font-bold text-white bg-red-500 px-1 py-0.5 rounded">HOT</span>
                                @endif

                                {{-- CẬP NHẬT: Hiển thị nhãn nếu có Biến thể --}}
                                @if($product->variants && $product->variants->count() > 0)
                                    <span class="text-[10px] font-semibold text-purple-700 bg-purple-100 border border-purple-200 px-1.5 py-0.5 rounded flex items-center">
                                        <i class="fas fa-tags mr-1"></i> {{ $product->variants->count() }} phiên bản
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="p-4 align-middle">
                            @if($product->category)
                                <a href="{{ route('admin.category.products', $product->category->id) }}" 
                                   class="text-blue-600 hover:underline relative z-10" 
                                   onclick="event.stopPropagation()">
                                    {{ $product->category->name }}
                                </a>
                            @else
                                <span class="text-gray-400 italic">Chưa phân loại</span>
                            @endif
                        </td>

                        <td class="p-4 align-middle">
                            @if($product->brand)
                                <span class="text-gray-700 font-medium bg-gray-100 px-2 py-1 rounded text-xs uppercase border border-gray-200">
                                    {{ $product->brand }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">---</span>
                            @endif
                        </td>

                        <td class="p-4 align-middle">
                            @if($product->sale_price)
                                <div class="font-bold text-red-600">{{ number_format($product->sale_price) }}đ</div>
                                <div class="text-xs text-gray-400 line-through">{{ number_format($product->price) }}đ</div>
                            @else
                                <div class="font-bold text-gray-800">{{ number_format($product->price) }}đ</div>
                            @endif
                            
                            {{-- Nếu có biến thể, hiện gợi ý giá nhỏ --}}
                            @if($product->variants->count() > 0)
                                <div class="text-[10px] text-gray-500 italic mt-0.5">
                                    (Có giá tùy chọn)
                                </div>
                            @endif
                        </td>

                        <td class="p-4 align-middle text-center">
                            @if($product->is_active)
                                <span class="bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded">
                                    Hiện
                                </span>
                            @else
                                <span class="bg-gray-500 text-white text-xs font-bold px-3 py-1.5 rounded">
                                    Ẩn
                                </span>
                            @endif
                        </td>

                        <td class="p-4 align-middle text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="w-9 h-9 bg-white border border-red-200 text-red-600 hover:bg-red-600 hover:text-white rounded flex items-center justify-center transition shadow-sm" 
                                            title="Xóa"
                                            onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa không?')">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500">
                            Không tìm thấy sản phẩm nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection