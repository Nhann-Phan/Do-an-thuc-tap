@extends('layouts.client_layout')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-center mb-10 text-gray-800">So sánh sản phẩm</h1>

    @if($products->count() > 0)
        <div class="overflow-x-auto shadow-lg rounded-xl border border-gray-100">
            <table class="w-full text-left border-collapse bg-white">
                
                {{-- Hàng 1: Hình ảnh & Tên --}}
                <tr class="border-b border-gray-100">
                    <td class="p-4 w-48 font-bold bg-gray-50 text-gray-500 uppercase text-xs align-middle">Sản phẩm</td>
                    @foreach($products as $product)
                        <td class="p-6 text-center align-top min-w-[250px] relative group">
                            <button onclick="removeCompare({{ $product->id }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </button>
                            <img src="{{ asset($product->image) }}" class="h-40 mx-auto object-contain mb-4">
                            <h3 class="font-bold text-gray-800 text-lg leading-tight h-12 overflow-hidden">
                                <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                            </h3>
                        </td>
                    @endforeach
                </tr>

                {{-- Hàng 2: Giá --}}
                <tr class="border-b border-gray-100">
                    <td class="p-4 font-bold bg-gray-50 text-gray-500 uppercase text-xs align-middle">Giá bán</td>
                    @foreach($products as $product)
                        <td class="p-4 text-center">
                            <span class="text-red-600 font-bold text-xl">
                                {{ number_format($product->sale_price ?: $product->price, 0, ',', '.') }} đ
                            </span>
                        </td>
                    @endforeach
                </tr>

                {{-- Hàng 3: Thương hiệu --}}
                <tr class="border-b border-gray-100">
                    <td class="p-4 font-bold bg-gray-50 text-gray-500 uppercase text-xs align-middle">Thương hiệu</td>
                    @foreach($products as $product)
                        <td class="p-4 text-center text-gray-700 font-medium">
                            {{ $product->brand ?? '---' }}
                        </td>
                    @endforeach
                </tr>

                {{-- Hàng 4: Hành động --}}
                <tr>
                    <td class="p-4 bg-gray-50"></td>
                    @foreach($products as $product)
                        <td class="p-4 text-center">
                            <a href="{{ route('product.detail', $product->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-sm">
                                Mua ngay
                            </a>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    @else
        <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
            <i class="fas fa-random text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Bạn chưa chọn sản phẩm nào để so sánh.</p>
            <a href="/" class="mt-4 inline-block text-blue-600 font-bold hover:underline">Quay lại cửa hàng</a>
        </div>
    @endif
</div>

<script>
    function removeCompare(id) {
        if(!confirm('Xóa sản phẩm này khỏi bảng so sánh?')) return;
        fetch('{{ route("compare.remove") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id: id })
        }).then(() => location.reload());
    }
</script>
@endsection