@extends('layouts.client_layout')

@section('content')

    <div class="relative bg-gray-900 h-[400px] md:h-[500px]">
        <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 flex items-center">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl text-white">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 uppercase leading-tight">Giải pháp quản lý <br>cho doanh nghiệp 4.0</h2>
                    <a href="#products" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-bold uppercase text-sm transition inline-block">Xem giải pháp</a>
                </div>
            </div>
        </div>
    </div>  

    <section class="py-16 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-10 pointer-events-none">
            <i class="fas fa-network-wired text-[300px] absolute -top-10 -left-20 text-blue-300"></i>
            <i class="fas fa-share-alt text-[200px] absolute bottom-10 right-10 text-blue-300"></i>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-blue-600 uppercase tracking-wide">Giới thiệu về công ty</h2>
                <div class="w-24 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="border-l-4 border-blue-600 pl-6 space-y-5 text-gray-600 text-justify leading-relaxed">
                        <p>Công ty TNHH MTV Thiết bị và phần mềm <span class="font-bold text-blue-800">GPM Việt Nam</span> với định hướng là công ty hoạt động trong lĩnh vực công nghệ thông tin, cung cấp các giải pháp, sản phẩm/dịch vụ chuyên sâu dành cho các doanh nghiệp tại địa phương.</p>
                        <p>Với đội ngũ kỹ sư giàu kinh nghiệm và nhiệt huyết, chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất lượng cao cùng dịch vụ chuyên nghiệp và chi phí tối ưu.</p>
                        <div class="pt-4 mt-4 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-500">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                Địa chỉ văn phòng: 38 đường số 9, KĐT Tây Sông Hậu, Long Xuyên, An Giang
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-center items-center">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center mb-2">
                                <i class="fas fa-chart-pie text-6xl text-blue-500 mr-3"></i> 
                                <span class="text-6xl font-extrabold text-blue-500 tracking-tighter">GPM</span>
                            </div>
                            <div class="text-xl font-bold text-black tracking-[0.3em] uppercase mt-2">Giải pháp mới</div>
                            <a href="#" class="inline-block mt-8 px-8 py-3 border border-blue-600 text-blue-600 font-bold rounded-full hover:bg-blue-600 hover:text-white transition duration-300">XEM HỒ SƠ NĂNG LỰC</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white" id="gallery">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-blue-600 uppercase border-l-4 border-blue-600 pl-4">Hình ảnh thực tế</h2>
                    <p class="text-gray-500 mt-2 ml-5">Kéo sang trái/phải để xem thêm các dự án.</p>
                </div>
                <div class="hidden md:flex gap-2">
                    <div class="swiper-button-prev-custom w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-blue-600 hover:text-white transition cursor-pointer"><i class="fas fa-chevron-left"></i></div>
                    <div class="swiper-button-next-custom w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-blue-600 hover:text-white transition cursor-pointer"><i class="fas fa-chevron-right"></i></div>
                </div>
            </div>

            <div class="swiper myProjectSwiper !pb-12 px-2"> 
                <div class="swiper-wrapper">
                    @if(isset($projectImages) && count($projectImages) > 0)
                        @foreach($projectImages as $img)
                        <div class="swiper-slide select-none">
                            <div class="overflow-hidden rounded-xl shadow-lg relative group h-64">
                                <img src="{{ asset($img->image_path) }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700" 
                                     draggable="false" 
                                     onmousedown="return false">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-4">
                                    <p class="text-white font-bold text-sm transform translate-y-4 group-hover:translate-y-0 transition duration-300 line-clamp-2">
                                        {{ $img->caption ?? 'Dự án GPM' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="swiper-slide">
                            <div class="h-64 flex items-center justify-center bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 text-gray-400">
                                Chưa có hình ảnh nào.
                            </div>
                        </div>
                    @endif
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <div id="products" class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-end mb-6 border-b pb-2">
            <h3 class="text-2xl font-bold text-gray-800 uppercase border-l-4 border-blue-600 pl-3">Sản phẩm mới nhất</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if(isset($currentCategory))
                <div class="mb-6 border-l-4 border-blue-600 pl-4 col-span-full">
                    <h2 class="text-2xl font-bold text-gray-800 uppercase">{{ $currentCategory->name }}</h2>
                    <p class="text-gray-500 text-sm">Hiển thị {{ $products->count() }} sản phẩm</p>
                </div>
            @endif

            @foreach($products as $product)
            <div class="bg-white border rounded hover:shadow-xl transition duration-300 relative group flex flex-col">
                @if($product->sale_price)
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded">-HOT</span>
                @endif

                <div class="h-48 p-4 flex items-center justify-center relative">
                    <a href="{{ route('product.detail', $product->id) }}" class="block w-full h-full flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="max-h-full max-w-full object-contain">
                        @else
                            <div class="text-6xl text-gray-300"><i class="fas fa-laptop"></i></div> 
                        @endif
                    </a>
                </div>

                <div class="p-4 flex-grow flex flex-col">
                    <h4 class="text-sm font-bold text-gray-700 mb-1 hover:text-blue-600 cursor-pointer line-clamp-2">
                        <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                    </h4>
                    <div class="mt-auto">
                        @if($product->sale_price)
                            <span class="text-red-600 font-bold">{{ number_format($product->sale_price) }} đ</span>
                            <span class="text-gray-400 text-xs line-through ml-2">{{ number_format($product->price) }} đ</span>
                        @else
                            <span class="text-red-600 font-bold">{{ number_format($product->price) }} đ</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Đợi trang tải xong mới chạy Slide
    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper(".myProjectSwiper", {
            // Cấu hình cơ bản
            slidesPerView: 1,      
            spaceBetween: 20,      
            loop: true,            
            
            // --- CẤU HÌNH KÉO THẢ ---
            grabCursor: true,      // Con trỏ bàn tay
            simulateTouch: true,   // Giả lập cảm ứng
            allowTouchMove: true,  // Cho phép vuốt
            
            // --- CẤU HÌNH TỰ ĐỘNG CHẠY ---
            autoplay: {
                delay: 5000,        // 5 giây
                disableOnInteraction: false, // Kéo xong vẫn tự chạy tiếp
                pauseOnMouseEnter: true,     // Di chuột vào thì dừng
            },

            // --- RESPONSIVE ---
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 25 },
                1024: { slidesPerView: 4, spaceBetween: 30 }, // PC hiện 4 ảnh
            },

            navigation: {
                nextEl: ".swiper-button-next-custom",
                prevEl: ".swiper-button-prev-custom",
            },

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },
        });
    });

    function sendMessage() {
            const input = document.getElementById('chat-input');
            const content = document.getElementById('chat-content');
            const userMsg = input.value.trim();

            if(userMsg === '') return;

            // 1. Hiện tin nhắn khách
            content.innerHTML += `<div class="bg-gray-200 p-2 rounded-lg self-end max-w-[80%] ml-auto mt-2 text-right">${userMsg}</div>`;
            input.value = '';
            content.scrollTop = content.scrollHeight;

            // 2. Hiện "Đang nhập..."
            const loadingId = 'loading-' + Date.now();
            content.innerHTML += `
                <div id="${loadingId}" class="bg-blue-50 text-gray-500 p-2 rounded-lg self-start max-w-[80%] mt-2 flex items-center">
                    <i class="fas fa-robot mr-2 text-blue-600"></i>
                    <span class="text-xs italic">GPM AI đang trả lời<span class="animate-pulse">...</span></span>
                </div>`;
            content.scrollTop = content.scrollHeight;

            // 3. Gọi về Server cũ
            fetch('{{ route('chatbot.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: userMsg })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(loadingId).remove();
                content.innerHTML += `<div class="bg-blue-100 text-blue-900 p-3 rounded-lg self-start max-w-[85%] mt-2 shadow-sm leading-relaxed">
                                        <i class="fas fa-robot mr-2 text-lg text-blue-600"></i>
                                        <span>${data.reply}</span>
                                      </div>`;
                content.scrollTop = content.scrollHeight;
            })
            .catch(error => {
                document.getElementById(loadingId).remove();
            });
        }
</script>

<style>
    .swiper-pagination-bullet-active { background-color: #2563eb !important; width: 24px !important; border-radius: 4px !important; }
    /* Giúp con trỏ chuột hiển thị đúng kiểu khi kéo */
    .myProjectSwiper { cursor: grab; }
    .myProjectSwiper:active { cursor: grabbing; }
</style>
@endpush