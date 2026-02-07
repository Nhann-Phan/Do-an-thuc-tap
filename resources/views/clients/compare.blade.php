@extends('layouts.client_layout')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-center mb-10 text-gray-800 uppercase tracking-wide">So sánh sản phẩm</h1>

    @if($products->count() > 0)
        @php
            // Lấy danh sách tất cả các thông số kỹ thuật
            $allSpecKeys = collect();
            foreach($products as $product) {
                if (!empty($product->specs) && is_array($product->specs)) {
                    $allSpecKeys = $allSpecKeys->merge(array_keys($product->specs));
                }
            }
            $allSpecKeys = $allSpecKeys->unique();
        @endphp

        <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-200">
            {{-- Thêm class 'table-fixed' để cố định độ rộng các cột --}}
            <table class="w-full text-left border-collapse bg-white table-fixed">
                {{-- Hàng 1: Hình ảnh & Tên & Nút Xóa --}}
                <tr class="border-b border-gray-200">
                    {{-- Cột tiêu đề: Cố định độ rộng 256px (w-64) --}}
                    <td class="p-4 text-center w-48 md:w-64 font-bold bg-gray-50 text-gray-600 uppercase text-xs align-middle border-r border-gray-100">
                        Sản phẩm
                    </td>
                    @foreach($products as $product)
                        <td class="p-6 text-center align-top w-80 relative group border-r border-gray-100 last:border-0">
                            <button onclick="removeCompare({{ $product->id }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition" title="Xóa khỏi so sánh">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </button>
                            <div class="h-40 flex items-center justify-center mb-4">
                                <img src="{{ asset($product->image) }}" class="max-h-full max-w-full object-contain hover:scale-105 transition duration-300">
                            </div>
                            <h3 class="font-bold text-gray-800 text-base leading-tight h-10 overflow-hidden line-clamp-2 mb-2">
                                <a href="{{ route('product.detail', $product->id) }}" class="hover:text-blue-600">{{ $product->name }}</a>
                            </h3>
                        </td>
                    @endforeach
                </tr>

                {{-- Hàng 2: Giá --}}
                <tr class="border-b border-gray-100">
                    <td class="p-4 text-center font-bold bg-gray-50 text-gray-600 uppercase text-xs align-middle border-r border-gray-100">Giá bán</td>
                    @foreach($products as $product)
                        <td class="p-4 text-center border-r border-gray-100 last:border-0">
                            <span class="text-red-600 font-bold text-xl block">
                                {{ number_format($product->sale_price ?: $product->price, 0, ',', '.') }} ₫
                            </span>
                            @if($product->sale_price)
                                <span class="text-gray-400 text-sm line-through">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- Hàng 3: Thương hiệu --}}
                <tr class="border-b border-gray-100">
                    <td class="p-4 text-center font-bold bg-gray-50 text-gray-600 uppercase text-xs align-middle border-r border-gray-100">Thương hiệu</td>
                    @foreach($products as $product)
                        <td class="p-4 text-center text-blue-600 font-semibold border-r border-gray-100 last:border-0">
                            {{ $product->brand ?? '---' }}
                        </td>
                    @endforeach
                </tr>

                @if($allSpecKeys->count() > 0)
                    @foreach($allSpecKeys as $key)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition duration-150">
                            {{-- Cột Tên thông số --}}
                            <td class="text-center p-4 font-bold bg-gray-50 text-gray-600 uppercase text-xs align-middle border-r border-gray-100">
                                {{ $key }}
                            </td>

                            {{-- Cột Giá trị của từng sản phẩm --}}
                            @foreach($products as $product)
                                <td class="p-4 text-center text-gray-600 text-sm align-middle border-r border-gray-100 last:border-0 leading-relaxed break-words">
                                    @if(isset($product->specs[$key]))
                                        {{ $product->specs[$key] }}
                                    @else
                                        <span class="text-gray-300">---</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ $products->count() + 1 }}" class="p-8 text-center text-gray-400 italic">
                            Chưa có dữ liệu thông số kỹ thuật để so sánh.
                        </td>
                    </tr>
                @endif

                {{-- === PHẦN 3: HÀNH ĐỘNG === --}}
                <tr class="bg-gray-50">
                    <td class="p-4 border-r border-gray-100"></td>
                    @foreach($products as $product)
                        <td class="p-6 text-center border-r border-gray-100 last:border-0">
                            <a href="{{ route('product.detail', $product->id) }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-200 transform hover:-translate-y-0.5">
                                Mua ngay
                            </a>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200 shadow-sm">
            <div class="bg-gray-100 p-6 rounded-full mb-4">
                <i class="fas fa-balance-scale text-5xl text-gray-400"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Chưa có sản phẩm nào để so sánh</h2>
            <p class="text-gray-500 mb-6">Vui lòng chọn sản phẩm từ trang cửa hàng để bắt đầu so sánh.</p>
            <a href="/" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                Quay lại cửa hàng
            </a>
        </div>
    @endif
</div>

<script>
    function removeCompare(id) {
        if(!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi bảng so sánh?')) return;
        
        fetch('{{ route("compare.remove") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => {
            if(response.ok) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection