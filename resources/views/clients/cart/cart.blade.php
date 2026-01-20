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
            <li class="text-gray-900 font-bold">Giỏ hàng</li>
        </ol>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div class="bg-white min-h-[60vh] pb-16 mb-20">
    <div class="container mx-auto px-4 font-sans">
        
        {{-- Tiêu đề --}}
        <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-tight mb-6 flex items-center">
            <i class="fas fa-shopping-cart mr-3 text-blue-600"></i> Giỏ hàng của bạn
        </h1>

        {{-- Thông báo thành công --}}
        @if(session('cart_success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-lg"></i>
                    <span class="font-medium">{{ session('cart_success') }}</span>
                </div>
                <button onclick="this.parentElement.remove();" class="text-green-600 hover:text-green-800 transition focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- CỘT TRÁI: DANH SÁCH SẢN PHẨM --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    {{-- Table Wrapper cho Mobile --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-200 tracking-wider">
                                    <th class="px-6 py-4">Sản phẩm</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">Đơn giá</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">Số lượng</th>
                                    <th class="px-6 py-4 text-center whitespace-nowrap">Thành tiền</th>
                                    <th class="px-6 py-4 text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php $total = 0 @endphp
                                @if(session('cart'))
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <tr data-id="{{ $id }}" class="hover:bg-blue-50/30 transition duration-150 group">
                                            
                                            {{-- Tên & Ảnh --}}
                                            <td class="px-6 py-4 min-w-[250px]">
                                                <div class="flex items-start">
                                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-1">
                                                        <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="h-full w-full object-contain mix-blend-multiply">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-800 line-clamp-2 hover:text-blue-600 transition mb-1">
                                                            <a href="{{ route('product.detail', $id) }}">{{ $details['name'] }}</a>
                                                        </div>
                                                        @if(isset($details['variant_name']))
                                                            <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                                {{ $details['variant_name'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            {{-- Đơn giá --}}
                                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 whitespace-nowrap">
                                                {{ number_format($details['price'], 0, ',', '.') }}đ
                                            </td>

                                            {{-- Số lượng --}}
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" value="{{ $details['quantity'] }}" min="1"
                                                    class="quantity update-cart w-16 text-center border border-gray-300 rounded-lg py-1.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                                                >
                                            </td>

                                            {{-- Thành tiền --}}
                                            <td class="px-6 py-4 text-center text-sm font-bold text-blue-600 whitespace-nowrap">
                                                {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ
                                            </td>

                                            {{-- Xóa --}}
                                            <td class="px-6 py-4 text-center">
                                                <button class="remove-from-cart w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition mx-auto" title="Xóa sản phẩm">
                                                    <i class="fas fa-trash-alt text-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    {{-- GIỎ HÀNG TRỐNG --}}
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-4">
                                                    <i class="fas fa-shopping-basket text-4xl"></i>
                                                </div>
                                                <p class="text-gray-800 text-lg font-bold mb-2">Giỏ hàng của bạn đang trống!</p>
                                                <p class="text-gray-500 text-sm mb-6">Hãy chọn thêm sản phẩm để mua sắm nhé.</p>
                                                <a href="/" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wide">
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
                    <div class="mt-6">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition group">
                            <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition"></i> Tiếp tục xem sản phẩm
                        </a>
                    </div>
                @endif
            </div>

            {{-- CỘT PHẢI: TỔNG TIỀN (SIDEBAR) --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-lg border border-blue-100 p-6 sticky top-24">
                    <h2 class="text-sm font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100 uppercase tracking-wide flex items-center">
                        <i class="fas fa-receipt mr-2 text-blue-600"></i> Cộng giỏ hàng
                    </h2>
                    
                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span class="font-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Giảm giá:</span>
                            <span class="font-bold text-green-600">0đ</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-dashed border-gray-200 mt-2">
                            <span class="text-base font-bold text-gray-800">Tổng cộng:</span>
                            <span class="text-2xl font-extrabold text-red-600">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <p class="text-[10px] text-gray-400 text-right italic">(Đã bao gồm VAT nếu có)</p>
                    </div>

                    @if(session('cart') && count(session('cart')) > 0)
                        <a href="{{ route('checkout.index') }}" class="block w-full py-3.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-center rounded-xl shadow-lg transition transform hover:-translate-y-0.5 mb-3 text-sm uppercase tracking-wide">
                            Tiến hành thanh toán <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @else
                        <button disabled class="block w-full py-3.5 px-4 bg-gray-200 text-gray-400 font-bold text-center rounded-xl cursor-not-allowed mb-3 text-sm uppercase tracking-wide">
                            Giỏ hàng trống
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
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
@endpush

@endsection