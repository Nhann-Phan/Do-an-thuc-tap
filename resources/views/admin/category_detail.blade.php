@extends('layouts.admin_layout')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="font-sans">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Quản lý: {{ $category->name }}</h3>
            <p class="text-sm text-gray-500">Danh sách sản phẩm thuộc danh mục này</p>
        </div>
        
        <a href="{{ route('product.create', ['category_id' => $category->id]) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center transition">
            <i class="fas fa-plus mr-2"></i> Thêm Sản Phẩm Mới
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá bán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($category->products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" 
                                     class="h-16 w-16 object-cover rounded border border-gray-200" 
                                     onerror="this.src='https://via.placeholder.com/150?text=No+Img'">
                            @else
                                <span class="inline-block h-16 w-16 rounded bg-gray-100 border border-gray-200 flex items-center justify-center text-xs text-gray-400">
                                    No Img
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $product->sku }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->sale_price)
                                <div class="text-sm font-bold text-red-600">{{ number_format($product->sale_price) }} đ</div>
                                <div class="text-xs text-gray-400 line-through">{{ number_format($product->price) }} đ</div>
                            @else
                                <div class="text-sm font-bold text-gray-900">{{ number_format($product->price) }} đ</div>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hiện</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Ẩn</span>
                            @endif

                            @if($product->is_hot)
                                <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">HOT</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('product.edit', $product->id) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 p-2 rounded hover:bg-yellow-100 transition" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded hover:bg-red-100 transition" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300 block"></i>
                            Chưa có sản phẩm nào trong danh mục này.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection