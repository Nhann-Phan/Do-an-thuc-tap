@extends('layouts.admin_layout')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Quản lý: {{ $category->name }}</h3>
            <p class="text-sm text-gray-500 mt-1">Danh sách sản phẩm thuộc danh mục này</p>
        </div>
        
        <a href="{{ route('product.create', ['category_id' => $category->id]) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition transform active:scale-95">
            <i class="fas fa-plus mr-2"></i> Thêm Sản Phẩm Mới
        </a>
    </div>

    {{-- TABLE CONTENT --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200">
                        <th class="px-6 py-3 whitespace-nowrap">#</th>
                        <th class="px-6 py-3 whitespace-nowrap">Ảnh</th>
                        <th class="px-6 py-3 whitespace-nowrap">Tên sản phẩm</th>
                        <th class="px-6 py-3 whitespace-nowrap">Thương hiệu</th>
                        <th class="px-6 py-3 whitespace-nowrap">Giá bán</th>
                        <th class="px-6 py-3 whitespace-nowrap">Trạng thái</th>
                        <th class="px-6 py-3 whitespace-nowrap text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($category->products as $product)
                    <tr class="hover:bg-gray-50 transition duration-150 cursor-pointer group"
                        onclick="window.location='{{ route('product.edit', $product->id) }}'"
                        title="Bấm để chỉnh sửa">
                        
                        <td class="px-6 py-4 text-gray-400 font-medium">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" 
                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200 bg-gray-50" 
                                     onerror="this.src='https://via.placeholder.com/150?text=No+Img'">
                            @else
                                <div class="w-16 h-16 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs font-medium">
                                    No Img
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $product->sku }}</div>
                        </td>

                        <td class="px-6 py-4">
                            @if($product->brand)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                                    {{ $product->brand }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">---</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($product->sale_price)
                                <div class="font-bold text-red-600">{{ number_format($product->sale_price) }} đ</div>
                                <div class="text-xs text-gray-400 line-through">{{ number_format($product->price) }} đ</div>
                            @else
                                <div class="font-bold text-gray-900">{{ number_format($product->price) }} đ</div>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 items-start">
                                @if($product->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        Hiện
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        Ẩn
                                    </span>
                                @endif

                                @if($product->is_hot)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        HOT
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="inline-block">
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
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                                <p class="text-sm italic">Chưa có sản phẩm nào trong danh mục này.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection