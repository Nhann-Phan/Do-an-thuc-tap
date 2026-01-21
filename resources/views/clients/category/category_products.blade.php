@extends('layouts.client_layout')

@section('content')

{{-- 1. BREADCRUMB (Đường dẫn) --}}
<nav class="bg-gray-50 border-b border-gray-200 py-4 mb-8">
    <div class="container mx-auto px-4">
        <ol class="flex text-sm text-gray-500 items-center gap-2 overflow-hidden whitespace-nowrap font-medium">
            <li>
                <a href="/" class="hover:text-blue-600 transition flex items-center">
                    <i class="fas fa-home mr-1.5"></i> Trang chủ
                </a>
            </li>
            <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            
            @if($currentCategory->parent)
                <li>
                    <a href="{{ route('frontend.category.show', $currentCategory->parent_id) }}" class="hover:text-blue-600 transition">
                        {{ $currentCategory->parent->name }}
                    </a>
                </li>
                <li class="text-gray-300"><i class="fa-solid fa-angle-right"></i></li>
            @endif
            
            <li class="text-gray-900 font-bold truncate">{{ $currentCategory->name }}</li>
        </ol>
    </div>
</nav>

{{-- 2. MAIN CONTENT --}}
<div class="container mx-auto px-4 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- SIDEBAR: DANH MỤC --}}
        <aside class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                <div class="bg-gray-50 px-5 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 uppercase text-sm flex items-center tracking-wide">
                        <i class="fas fa-list-ul mr-2 text-blue-600"></i> Danh mục sản phẩm
                    </h3>
                </div>
                
                <div class="flex flex-col py-2">
                    @foreach($menuCategories as $cat)
                        <a href="{{ route('frontend.category.show', $cat->id) }}" 
                           class="px-5 py-3 text-sm font-medium transition flex items-center justify-between group 
                                  {{ $currentCategory->id == $cat->id ? 'text-blue-600 bg-blue-50/50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                            <span class="flex items-center">
                                <i class="{{ $cat->icon ?? 'fas fa-folder' }} mr-3 text-xs {{ $currentCategory->id == $cat->id ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i> 
                                {{ $cat->name }}
                            </span>
                            @if($cat->children && $cat->children->count() > 0)
                                <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                            @endif
                        </a>
                        
                        @if(($currentCategory->id == $cat->id || $currentCategory->parent_id == $cat->id) && $cat->children && $cat->children->count() > 0)
                            <div class="bg-gray-50/30 border-t border-b border-gray-100 py-1">
                                @foreach($cat->children as $child)
                                    <a href="{{ route('frontend.category.show', $child->id) }}" 
                                       class="pl-12 pr-5 py-2 text-xs transition flex items-center relative
                                              {{ $currentCategory->id == $child->id ? 'text-blue-600 font-bold bg-blue-50' : 'text-gray-500 hover:text-blue-600' }}">
                                        @if($currentCategory->id == $child->id)
                                            <span class="absolute left-9 w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        @endif
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- MAIN PRODUCT LIST --}}
        <div class="col-span-1 lg:col-span-3">
            {{-- Header & Sort --}}
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-200 gap-4">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    {{ $currentCategory->name }} 
                    <span class="ml-3 inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $products->count() }} sản phẩm
                    </span>
                </h1>
                
                <div class="flex items-center text-sm">
                    <label for="sort" class="text-gray-500 mr-2">Sắp xếp:</label>
                    <div class="relative">
                        <select id="sort" class="appearance-none bg-white border border-gray-300 text-gray-700 py-1.5 pl-3 pr-8 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm cursor-pointer hover:border-gray-400 transition">
                            <option>Mới nhất</option>
                            <option>Giá tăng dần</option>
                            <option>Giá giảm dần</option>
                            <option>Tên A-Z</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                @forelse($products as $product)
                <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-xl hover:border-blue-300 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    
                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                        @if($product->is_hot)
                            <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm uppercase tracking-wider animate-pulse">HOT</span>
                        @endif
                        @if($product->sale_price)
                            <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                -{{ round((($product->price - $product->sale_price)/$product->price)*100) }}%
                            </span>
                        @endif
                    </div>

                    {{-- Image --}}
                    <div class="relative pt-[100%] overflow-hidden bg-white border-b border-gray-50 group-hover:border-gray-100 transition-colors">
                        <a href="{{ route('product.detail', $product->id) }}" class="absolute inset-0 flex items-center justify-center p-6">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="max-h-full max-w-full object-contain transform group-hover:scale-110 transition duration-500 ease-out" alt="{{ $product->name }}">
                            @else
                                <div class="text-gray-300 flex flex-col items-center">
                                    <i class="fas fa-image text-4xl mb-1 opacity-50"></i>
                                </div>
                            @endif
                        </a>
                        
                        {{-- Quick Action (Nút Xem & So sánh) --}}
                        <div class="absolute bottom-0 left-0 w-full p-2 translate-y-full group-hover:translate-y-0 transition duration-300 z-30">
                            <div class="flex">
                                <button type="button" onclick="addToCompare({{ $product->id }})" class="flex-1 bg-blue-600/90 hover:bg-blue-700 text-white text-center text-xs font-bold py-2 rounded shadow-md backdrop-blur-sm uppercase tracking-wide transition flex items-center justify-center" title="Thêm vào so sánh">
                                    <i class="fas fa-exchange-alt mr-1"></i> So sánh
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-sm font-bold text-gray-700 mb-2 line-clamp-2 h-10 group-hover:text-blue-600 transition leading-snug">
                            <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="mt-auto pt-3 border-t border-gray-50 flex flex-col justify-end min-h-[3rem]">
                            @if($product->variants && $product->variants->count() > 0)
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                <span class="text-red-600 font-bold text-base block">
                                    @if($minPrice == $maxPrice)
                                        {{ number_format($minPrice, 0, ',', '.') }}đ
                                    @else
                                        {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}đ
                                    @endif
                                </span>
                            @else
                                @if($product->sale_price)
                                    <div>
                                        <span class="text-red-600 font-bold text-base block">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                        <span class="text-gray-400 text-xs line-through block">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    </div>
                                @else
                                    <span class="text-red-600 font-bold text-base block">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-sm mb-4 text-gray-300">
                        <i class="fas fa-box-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Chưa có sản phẩm</h3>
                    <p class="text-gray-500 text-sm mb-6">Danh mục này hiện chưa có sản phẩm nào được cập nhật.</p>
                    <a href="/" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Quay về trang chủ
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- 3. TOAST NOTIFICATION (Thông báo góc phải) --}}
<div id="toast-notification" class="hidden fixed top-24 right-5 z-[100] max-w-xs w-full bg-white border border-gray-100 rounded-xl shadow-2xl transform transition-all duration-500 ease-in-out translate-x-full opacity-0">
    <div class="flex items-center p-4">
        <div id="toast-icon" class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-green-100 text-green-600"></div>
        <div class="flex-1">
            <p id="toast-message" class="text-sm font-semibold text-gray-800 leading-tight">Thông báo</p>
        </div>
        <button onclick="hideToast()" class="ml-3 text-gray-400 hover:text-red-500 transition focus:outline-none"><i class="fas fa-times"></i></button>
    </div>
    <div class="h-1 w-full bg-gray-100 rounded-b-xl overflow-hidden">
        <div id="toast-progress" class="h-full bg-green-500 w-full transition-all duration-[5000ms] ease-linear"></div>
    </div>
</div>

{{-- 4. STICKY COMPARE BAR (Thanh so sánh dưới cùng) --}}
<div id="compare-sticky-bar" class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-blue-600 shadow-[0_-5px_15px_rgba(0,0,0,0.15)] z-[99] transition-transform duration-300 transform translate-y-full">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        
        <div class="flex items-center gap-4">
            {{-- Icon gốc --}}
            <div class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exchange-alt"></i>
            </div>
            
            <div class="flex flex-col">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Danh sách so sánh (<span id="compare-count-display">0</span>)</p>
                
                {{-- Container chứa ảnh sản phẩm --}}
                <div id="compare-items-container" class="flex items-center gap-2">
                    {{-- JS sẽ render ảnh vào đây --}}
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="hideCompareBar()" class="text-gray-500 hover:text-red-500 text-sm font-medium px-3 transition">
                <i class="fas fa-times"></i> Đóng
            </button>
            <a href="{{ route('compare.index') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-5 rounded-lg shadow-md transition flex items-center gap-2 transform active:scale-95 text-sm">
                So sánh ngay <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- 5. JAVASCRIPT XỬ LÝ --}}
<script>
    // --- A. Toast Notification Logic ---
    let toastTimeout;
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast-notification');
        const msgEl = document.getElementById('toast-message');
        const iconEl = document.getElementById('toast-icon');
        const progressEl = document.getElementById('toast-progress');

        msgEl.innerText = message;
        
        // Cấu hình màu sắc icon
        if (type === 'success') {
            iconEl.innerHTML = '<i class="fas fa-check"></i>';
            iconEl.className = 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-green-100 text-green-600';
            progressEl.className = 'h-full bg-green-500 w-full transition-all duration-[5000ms] ease-linear';
        } else if (type === 'warning') {
            iconEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            iconEl.className = 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-yellow-100 text-yellow-600';
            progressEl.className = 'h-full bg-yellow-500 w-full transition-all duration-[5000ms] ease-linear';
        } else {
            iconEl.innerHTML = '<i class="fas fa-info"></i>';
            iconEl.className = 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-blue-100 text-blue-600';
            progressEl.className = 'h-full bg-blue-500 w-full transition-all duration-[5000ms] ease-linear';
        }

        // Hiện Toast
        toast.classList.remove('hidden');
        progressEl.style.width = '100%';
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            progressEl.style.width = '0%';
        }, 10);

        // Tự ẩn
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(hideToast, 5000);
    }
    function hideToast() {
        document.getElementById('toast-notification').classList.add('translate-x-full', 'opacity-0');
    }

    // --- B. AJAX Add To Compare ---
    function addToCompare(productId) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route("compare.add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                showToast(data.message, 'success');
                updateCompareBarUI(data.list); // Cập nhật thanh bar
            } else if (data.status === 'warning') {
                showToast(data.message, 'warning');
            } else {
                showToast(data.message, 'info');
            }
        })
        .catch(err => console.error(err));
    }

    // --- C. AJAX Remove From Bar ---
    function removeCompareItem(productId) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route("compare.remove") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ id: productId })
        })
        .then(res => res.json())
        .then(data => {
            updateCompareBarUI(data.list); // Cập nhật thanh bar sau khi xóa
            showToast('Đã xóa sản phẩm khỏi so sánh', 'success');
        })
        .catch(err => console.error(err));
    }

    // --- D. Update UI Sticky Bar ---
    function updateCompareBarUI(products) {
        const bar = document.getElementById('compare-sticky-bar');
        const container = document.getElementById('compare-items-container');
        const countDisplay = document.getElementById('compare-count-display');

        // Cập nhật số lượng
        countDisplay.innerText = products.length;

        // Render ảnh sản phẩm
        container.innerHTML = '';
        if (products.length > 0) {
            products.forEach(p => {
                const imageUrl = p.image ? `/${p.image}` : 'https://via.placeholder.com/50';
                const itemHtml = `
                    <div class="relative w-12 h-12 border border-gray-200 rounded bg-white p-0.5 group shrink-0">
                        <img src="${imageUrl}" class="w-full h-full object-contain" alt="${p.name}">
                        <button onclick="removeCompareItem(${p.id})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] shadow hover:bg-red-700 transition opacity-0 group-hover:opacity-100 z-10" title="Xóa">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
            });
            bar.classList.remove('translate-y-full'); // Hiện thanh
        } else {
            bar.classList.add('translate-y-full'); // Ẩn thanh nếu rỗng
        }
    }

    function hideCompareBar() {
        document.getElementById('compare-sticky-bar').classList.add('translate-y-full');
    }

    // --- E. Load dữ liệu ban đầu khi F5 trang ---
    document.addEventListener("DOMContentLoaded", function() {
        @php
            $currentCompareList = [];
            if(Session::has('compare_products')) {
                $ids = Session::get('compare_products');
                // Lấy danh sách sản phẩm từ DB để hiển thị ảnh
                $currentCompareList = \App\Models\Product::whereIn('id', $ids)->select('id', 'name', 'image')->get();
            }
        @endphp
        
        // Truyền dữ liệu từ PHP sang JS
        const initialProducts = @json($currentCompareList);
        updateCompareBarUI(initialProducts);
    });
</script>

@endsection