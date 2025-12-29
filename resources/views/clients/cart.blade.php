@extends('layouts.client_layout')

@section('content')

<div class="bg-gray-50 py-3 border-b border-gray-200 shadow-sm relative z-0">
    <div class="container mx-auto px-4">
        <nav class="text-xs font-medium text-gray-500 mb-1">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="/" class="text-gray-500 hover:text-blue-600 transition no-underline"><i class="fas fa-home mr-1"></i> Trang chủ</a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                <li class="text-blue-600 font-bold" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>
        <h1 class="text-xl font-extrabold text-gray-800 uppercase tracking-tight m-0 flex items-center">
            <i class="fas fa-shopping-cart mr-2 text-blue-600"></i> Giỏ hàng của bạn
        </h1>
    </div>
</div>

<div class="bg-gray-50 min-h-screen pb-10">
    <div class="container mx-auto px-4 py-6 font-sans">
        
        @if(session('cart_success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 mb-5 rounded shadow-sm flex justify-between items-center text-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-lg"></i>
                    <span>{{ session('cart_success') }}</span>
                </div>
                <button onclick="this.parentElement.remove();" class="text-green-700 hover:text-green-900 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 text-[11px] uppercase font-bold border-b border-gray-200">
                                    <th class="px-4 py-3">Sản phẩm</th>
                                    <th class="px-4 py-3 text-center">Đơn giá</th>
                                    <th class="px-4 py-3 text-center">Số lượng</th>
                                    <th class="px-4 py-3 text-center">Thành tiền</th>
                                    <th class="px-4 py-3 text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php $total = 0 @endphp
                                @if(session('cart'))
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <tr data-id="{{ $id }}" class="hover:bg-blue-50/50 transition duration-150 group">
                                            
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded border border-gray-200 bg-white">
                                                        <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="h-full w-full object-cover object-center">
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-xs font-bold text-gray-800 line-clamp-2 w-48 leading-tight group-hover:text-blue-600 transition">
                                                            <a href="{{ route('product.detail', $id) }}">{{ $details['name'] }}</a>
                                                        </div>
                                                        <div class="text-[10px] text-gray-400 mt-1">Mã: #{{ $id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-center text-xs font-medium text-gray-600">
                                                {{ number_format($details['price']) }}đ
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <input type="number" value="{{ $details['quantity'] }}" min="1"
                                                    class="quantity update-cart w-14 text-center border border-gray-300 rounded py-1 focus:outline-none focus:border-blue-500 text-xs font-bold text-gray-700"
                                                >
                                            </td>

                                            <td class="px-4 py-3 text-center text-xs font-bold text-blue-600">
                                                {{ number_format($details['price'] * $details['quantity']) }}đ
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <button class="remove-from-cart text-gray-400 hover:text-red-500 transition p-1.5 rounded-full hover:bg-red-50" title="Xóa">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-shopping-basket text-6xl text-gray-200 mb-4"></i>
                                                <p class="text-gray-500 text-sm mb-4 font-medium">Giỏ hàng của bạn đang trống!</p>
                                                <a href="/" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-full shadow hover:bg-blue-700 transition text-xs uppercase tracking-wide">
                                                    Mua sắm ngay
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if(session('cart'))
                    <div class="mt-4">
                        <a href="/" class="inline-flex items-center text-xs text-gray-500 hover:text-blue-600 font-bold transition">
                            <i class="fas fa-long-arrow-alt-left mr-2"></i> Tiếp tục xem sản phẩm
                        </a>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-24">
                    <h2 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 uppercase tracking-wide">
                        <i class="fas fa-calculator mr-2 text-blue-600"></i> Cộng giỏ hàng
                    </h2>
                    
                    <div class="space-y-3 mb-6 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span class="font-bold">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Giảm giá:</span>
                            <span class="font-bold text-green-600">0đ</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-200">
                            <span class="text-sm font-bold text-gray-800">Tổng cộng:</span>
                            <span class="text-xl font-bold text-red-600">{{ number_format($total) }}đ</span>
                        </div>
                        <p class="text-[10px] text-gray-400 text-right italic">(Đã bao gồm VAT nếu có)</p>
                    </div>

                    @if(session('cart') && count(session('cart')) > 0)
                        <a href="{{ route('checkout.index') }}" class="block w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-center rounded-lg shadow-md transition transform hover:-translate-y-0.5 mb-3 text-xs uppercase tracking-wide">
                            Tiến hành thanh toán <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @else
                        <button disabled class="block w-full py-3 px-4 bg-gray-300 text-gray-500 font-bold text-center rounded-lg cursor-not-allowed mb-3 text-xs uppercase tracking-wide">
                            Giỏ hàng trống
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    // 1. Cập nhật số lượng (Ajax)
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        var quantity = ele.val();

        if(quantity < 1) {
            alert("Số lượng tối thiểu là 1");
            ele.val(1);
            return;
        }

        $.ajax({
            url: '{{ route('update_cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id"), 
                quantity: quantity
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    // 2. Xóa sản phẩm (Ajax)
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        
        if(confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            $.ajax({
                url: '{{ route('remove_from_cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endsection