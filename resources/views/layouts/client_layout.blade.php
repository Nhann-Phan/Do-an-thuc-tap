<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GPM Technology</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Libraries --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Hiệu ứng gạch chân menu (Giữ lại CSS thuần vì nó mượt hơn utility class) */
        .hover-underline-animation { display: inline-block; position: relative; }
        .hover-underline-animation::after { content: ''; position: absolute; width: 100%; transform: scaleX(0); height: 2px; bottom: 0; left: 0; background-color: #1e40af; transform-origin: bottom right; transition: transform 0.25s ease-out; }
        .hover-underline-animation:hover::after { transform: scaleX(1); transform-origin: bottom left; }
        
        /* Custom Scrollbar cho khung chat */
        #chat-content::-webkit-scrollbar { width: 6px; }
        #chat-content::-webkit-scrollbar-track { background: #f1f1f1; }
        #chat-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        #chat-content::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Animation fade in */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }

        /* SweetAlert2 Custom Size */
        .small-popup-text .swal2-title { font-size: 18px !important; }
        .small-popup-text .swal2-html-container { font-size: 14px !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen relative">

    {{-- TOP BAR --}}
    <div class="bg-blue-900 text-white text-xs py-2">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex space-x-4">
                <span><i class="fas fa-envelope mr-1 text-blue-300"></i> contact@gpm.vn</span>
                <span><i class="fas fa-phone-alt mr-1 text-blue-300"></i> 0902 777 186</span>
            </div>
            <div class="flex space-x-3">
                <a href="/login" class="hover:text-blue-300 transition md:border-l md:pl-3 border-blue-700 flex items-center">
                    <i class="fas fa-user-lock mr-1"></i> Đăng nhập Admin
                </a>
            </div>
        </div>
    </div>

    {{-- HEADER --}}
    {{-- HEADER --}}
    <header class="bg-white shadow-sm sticky top-0 z-40">
        {{-- THÊM class 'relative' VÀO DÒNG DƯỚI ĐÂY --}}
        <div class="container mx-auto px-4 relative">
            <div class="flex justify-between items-center h-20">
                {{-- Logo --}}
                <a href="/" class="flex items-center z-20 flex-shrink-0 group">
                    <div class="text-3xl font-extrabold text-blue-800 tracking-tighter group-hover:text-blue-900 transition">GPM</div>
                    <div class="ml-2 flex flex-col leading-none">
                        <span class="text-[10px] font-bold text-gray-500 tracking-[0.2em]">GIẢI PHÁP</span>
                        <span class="text-[10px] font-bold text-blue-600 tracking-[0.2em]">CÔNG NGHỆ</span>
                    </div>
                </a>

                {{-- Desktop Menu --}}
                <div class="flex items-center space-x-8 ml-auto">
                    <nav class="hidden md:flex space-x-8 font-medium text-sm uppercase text-gray-600 items-center h-full">
                        {{-- ... --}}
            <a href="/" class="hover:text-blue-700 transition hover-underline-animation py-1">Trang chủ</a>

            {{-- MENU GIỚI THIỆU (DROPDOWN ĐỘNG) --}}
            <div class="group relative h-full flex items-center">
                <a href="#" class="hover:text-blue-700 transition hover-underline-animation py-1 flex items-center cursor-pointer">
                    Giới thiệu <i class="fas fa-chevron-down ml-1 text-[10px] transition-transform group-hover:rotate-180"></i>
                </a>
                
                {{-- Dropdown Content --}}
                <div class="absolute left-0 w-64 invisible opacity-0 translate-y-3 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out z-50 top-full pt-5">
                    <div class="bg-white shadow-xl rounded-b-lg overflow-hidden">
                        <ul class="py-1 text-left">
                            @if(isset($introPages) && $introPages->count() > 0)
                                @foreach($introPages as $page)
                                    <li>
                                        <a href="{{ route('client.page.detail', $page->slug) }}" class="block px-5 py-3 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition border-b border-gray-50 last:border-0">
                                            <i class="fas fa-angle-right mr-2 text-xs text-gray-300"></i> {{ $page->title }}
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li><span class="block px-5 py-3 text-sm text-gray-400 italic">Đang cập nhật...</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

                        {{-- Mega Menu Sản Phẩm --}}
                        {{-- Class 'static' ở đây rất quan trọng để nó căn theo container cha --}}
                        <div class="group static h-full flex items-center py-6"> 
                            <a href="#products" class="hover:text-blue-700 transition hover-underline-animation py-1 flex items-center cursor-pointer">
                                SẢN PHẨM <i class="fas fa-chevron-down ml-1 text-[10px] transition-transform group-hover:rotate-180"></i>
                            </a>
                            
                            {{-- Dropdown Content --}}
                            {{-- Đã thêm rounded-b-xl và overflow-hidden để bo góc dưới --}}
                            <div class="absolute top-full left-0 w-full invisible opacity-0 translate-y-2 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out z-50 shadow-2xl border-blue-800 bg-white rounded-b-xl overflow-hidden">
                                {{-- Tính toán cột grid --}}
                                @php
                                    $cats = isset($menuCategories) ? $menuCategories : collect([]);
                                    $gridCols = $cats->count() > 0 ? $cats->count() : 1;
                                    $gridCols = $gridCols > 6 ? 6 : $gridCols;
                                @endphp
                                
                                {{-- Dòng tiêu đề danh mục cha (Màu xanh đậm) --}}
                                <div class="bg-blue-900 text-white">
                                    <div class="grid text-[13px] font-bold uppercase tracking-wide divide-x divide-blue-800" style="grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));">
                                        @foreach($cats as $parent)
                                        <a href="{{ route('frontend.category.show', $parent->id) }}" 
                                        class="py-3 px-2 flex items-center justify-center text-center h-full hover:bg-blue-800 transition cursor-pointer">
                                            <i class="{{ $parent->icon ?? 'fas fa-folder' }} mr-2 {{ $loop->first ? 'text-yellow-400' : 'text-blue-300' }}"></i> 
                                            <span>{{ $parent->name }}</span>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Danh sách menu con (Màu trắng) --}}
                                <div class="bg-white text-gray-700 pb-2">
                                    <div class="py-5">
                                        <div class="grid gap-0 divide-x divide-gray-100" style="grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));">
                                            @foreach($cats as $parent)
                                            <div class="px-4 h-full">
                                                <ul class="space-y-2 text-[13px] font-medium text-gray-600">
                                                    @foreach($parent->children as $child)
                                                    <li>
                                                        <a href="{{ route('frontend.category.show', $child->id) }}" class="hover:text-blue-700 hover:font-bold transition block py-1 transform hover:translate-x-1 flex items-center">
                                                            <i class="fas fa-caret-right text-gray-300 mr-2 text-[10px]"></i> {{ $child->name }}
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="/#gallery" class="hover:text-blue-700 transition hover-underline-animation py-1">Dự án</a>
                        <a href="{{ route('client.news.index') }}" class="hover:text-blue-700 transition hover-underline-animation py-1">Tin tức</a>
                        <a href="#footer" class="hover:text-blue-700 transition hover-underline-animation py-1">Liên hệ</a>
                    </nav>

                    {{-- ... Phần giỏ hàng và tìm kiếm giữ nguyên ... --}}
                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>
                    <a href="{{ route('cart.index') }}" class="relative group flex items-center text-gray-600 hover:text-blue-700 transition">
                        <div class="relative p-2">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            @if(session('cart'))
                                <span class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full ring-2 ring-white">
                                    {{ count((array) session('cart')) }}
                                </span>
                            @endif
                        </div>
                    </a>
                    {{-- Search Icon & Dropdown --}}
                    <div class="relative" id="searchContainer">
                         {{-- ... code search ... --}}
                         <button type="button" onclick="toggleSearchDropdown()" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-800 hover:bg-gray-50 rounded-full transition focus:outline-none">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                        <div id="searchDropdown" class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 p-4 z-50 invisible opacity-0 scale-95 transform transition-all duration-200 origin-top-right">
                             {{-- ... code form search ... --}}
                             <div class="absolute -top-2 right-3 w-4 h-4 bg-white transform rotate-45 border-l border-t border-gray-100"></div>
                             <form action="#" method="GET" class="relative">
                                <input type="text" name="q" id="searchInput" class="w-full border border-gray-300 text-gray-700 text-sm rounded-lg px-4 py-3 pr-16 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm transition" placeholder="Tìm kiếm sản phẩm...">
                                <button type="submit" class="absolute right-1.5 top-1.5 text-white bg-blue-800 hover:bg-blue-700 rounded-md px-3 py-1.5 text-xs font-bold transition shadow-sm mt-0.5">
                                    TÌM KIẾM
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer id="footer" class="mt-auto bg-[#1a1a1a] text-gray-400 border-t border-gray-800">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Cột 1: Thông tin công ty --}}
                <div class="lg:col-span-5 space-y-5">
                    <div>
                        <h3 class="text-white font-bold text-lg uppercase leading-snug tracking-wide">Công ty TNHH MTV Thiết bị và phần mềm GPM Việt Nam</h3>
                        <div class="h-1 w-12 bg-red-600 mt-3 rounded"></div>
                    </div>
                    <div class="space-y-3 text-sm leading-relaxed">
                        <p class="flex items-start"><i class="fas fa-map-marker-alt mt-1 mr-3 text-red-500"></i><span>38 đường số 9, KĐT Tây Sông Hậu, Long Xuyên, An Giang</span></p>
                        <p class="flex items-center"><i class="fas fa-phone-alt mr-3 text-red-500"></i><span>Điện thoại: <span class="text-white font-semibold">0902 777 186</span></span></p>
                        <p class="flex items-center"><i class="fas fa-envelope mr-3 text-red-500"></i><span>Email: contact@gpm.vn</span></p>
                        <p class="flex items-center"><i class="fas fa-globe mr-3 text-red-500"></i><span>Website: www.gpm.vn</span></p>
                    </div>
                    <div class="flex space-x-3 pt-2">
                        <a href="https://www.facebook.com/gpm.vn" class="w-9 h-9 bg-gray-800 hover:bg-blue-600 text-white flex items-center justify-center rounded transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-red-600 text-white flex items-center justify-center rounded transition"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-blue-400 text-white flex items-center justify-center rounded transition"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                {{-- Cột 2: Bản đồ --}}
                <div class="lg:col-span-3">
                    <h3 class="text-white font-bold text-sm uppercase mb-4">Bản đồ chỉ đường</h3>
                    <div class="w-full h-44 bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-lg hover:border-gray-500 transition">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3924.627295380687!2d105.43232637486822!3d10.371655789753856!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310a731e7543e577%3A0xc7507300c35471d8!2zMzggxJAuIFPhu5EgOSwgS2h1IMSRw7QgdGjhu4sgVMOieSBTw7RuZyBI4bqtdSwgVGjDoG5oIHBo4buRIExvbmcgWHV5w6puLCBBbiBHaWFuZyA5MDEwMCwgVmlldG5hbQ!5e0!3m2!1sen!2s!4v1709222400000!5m2!1sen!2s" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                {{-- Cột 3: Đăng ký nhận tin --}}
                <div class="lg:col-span-4">
                    <h3 class="text-white font-bold text-lg uppercase mb-3">ĐĂNG KÝ NHẬN THÔNG TIN</h3>
                    <p class="text-xs mb-5 text-gray-500 leading-relaxed">Xin vui lòng để lại địa chỉ email, chúng tôi sẽ cập nhật những tin tức quan trọng của GPM tới quý khách.</p>
                    <form class="space-y-3">
                        <div class="flex gap-2">
                            {{-- Input style Tailwind hoàn toàn --}}
                            <input type="text" placeholder="Họ và tên" class="w-1/2 text-sm px-4 py-2.5 rounded bg-gray-800 border border-gray-700 text-white focus:bg-gray-700 focus:border-red-600 focus:outline-none transition-all duration-300 placeholder-gray-500">
                            <input type="email" placeholder="Email" class="w-1/2 text-sm px-4 py-2.5 rounded bg-gray-800 border border-gray-700 text-white focus:bg-gray-700 focus:border-red-600 focus:outline-none transition-all duration-300 placeholder-gray-500">
                        </div>
                        <button type="button" onclick="Swal.fire({title: 'Đã đăng ký!', text: 'Cảm ơn bạn đã quan tâm.', icon: 'success', confirmButtonColor: '#dc2626'})" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 text-sm uppercase transition w-full rounded shadow-lg transform active:scale-95">Đăng ký ngay</button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Copyright Bar --}}
        <div class="bg-red-700 text-white text-[11px] md:text-xs py-3 relative border-t border-red-800">
            <div class="container mx-auto px-4 flex flex-col md:flex-row justify-center items-center">
                <div class="opacity-90 font-medium">
                    © 2025 Bản quyền thuộc về CÔNG TY GPM VIỆT NAM.
                </div>
            </div>
            
            {{-- Scroll Top Button --}}
            <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="hidden md:flex absolute bottom-4 right-6 bg-gray-800 hover:bg-gray-700 text-white w-10 h-10 items-center justify-center rounded shadow-lg transition border border-gray-600 z-30 group">
                <i class="fas fa-angle-double-up group-hover:-translate-y-1 transition-transform duration-300"></i>
            </a>
        </div>
    </footer>

    {{-- CHATBOT & BOOKING FLOATING BUTTONS --}}
    @if(!request()->is('admin*') && !request()->is('login') && !request()->is('register'))
        
        {{-- Chat Button --}}
        <div class="fixed bottom-8 right-6 z-40 print:hidden">
            <button id="chat-btn" onclick="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] flex items-center justify-center w-14 h-14 transition transform hover:scale-110 active:scale-95    ">
                <i class="fas fa-comment-dots text-2xl"></i>
            </button>
        </div>

        {{-- Chat Window --}}
        <div id="chat-window" class="fixed bottom-24 right-6 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 flex flex-col overflow-hidden h-[450px] transition-all duration-300 ease-in-out transform origin-bottom-right scale-0 opacity-0 invisible print:hidden">
            {{-- Header Chat --}}
            <div class="bg-blue-700 text-white p-4 flex justify-between items-center shadow-md">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-white text-blue-700 flex items-center justify-center mr-2 text-sm font-bold border border-blue-200">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <span class="font-bold text-sm block">Nhân viên hỗ trợ</span>
                        <span class="text-[10px] opacity-90 flex items-center"><span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1 animate-pulse"></span> Đang hoạt động</span>
                    </div>
                </div>
                <button onclick="toggleChat()" class="text-white hover:text-gray-200 focus:outline-none bg-blue-800/50 hover:bg-blue-800 w-7 h-7 rounded flex items-center justify-center transition"><i class="fas fa-times"></i></button>
            </div>
            
            {{-- Body Chat --}}
            <div id="chat-content" class="flex-1 p-4 overflow-y-auto bg-gray-50 text-sm space-y-3">
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600 text-xs mr-2">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="bg-white border border-gray-100 text-gray-700 p-3 rounded-2xl rounded-tl-none shadow-sm text-xs leading-relaxed max-w-[85%]">
                        Chào bạn! 👋<br>GPM Technology có thể giúp gì cho bạn hôm nay?
                    </div>
                </div>
                
                {{-- Gợi ý --}}
                <div class="flex flex-wrap gap-2 ml-10">
                    <button type="button" onclick="window.sendMessage('😎 Sản phẩm đang hot')" class="ignore-click bg-white border border-blue-200 text-blue-600 text-[11px] font-medium px-3 py-1.5 rounded-full hover:bg-blue-50 hover:border-blue-300 transition shadow-sm cursor-pointer transform hover:-translate-y-0.5">🔥 Sản phẩm hot</button>
                    <button type="button" onclick="window.sendMessage('⏰ Giờ làm việc của công ty')" class="ignore-click bg-white border border-blue-200 text-blue-600 text-[11px] font-medium px-3 py-1.5 rounded-full hover:bg-blue-50 hover:border-blue-300 transition shadow-sm cursor-pointer transform hover:-translate-y-0.5">⏰ Giờ làm việc</button>
                </div>
            </div>
            
            {{-- Footer Chat --}}
            <div class="p-3 border-t bg-white flex items-center gap-2">
                <input type="text" id="chat-input" class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm bg-gray-50" placeholder="Nhập câu hỏi...">
                <button onclick="window.sendMessage()" class="bg-blue-600 text-white w-9 h-9 rounded-full hover:bg-blue-700 transition flex items-center justify-center shadow-sm transform active:scale-95"><i class="fas fa-paper-plane text-xs"></i></button>
            </div>
        </div>

    @endif

    {{-- Booking Button (Trái) --}}
    <div class="fixed bottom-8 left-6 z-40">
        <button onclick="toggleBooking()" class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-full shadow-[0_4px_14px_0_rgba(220,38,38,0.39)] font-bold flex items-center gap-2 transition transform hover:scale-105 active:scale-95 animate-bounce text-sm">
            <i class="fas fa-calendar-check"></i> 
            <span class="hidden md:inline">Đặt Lịch Sửa Chữa</span>
            <span class="md:hidden">Đặt Lịch</span>
        </button>
    </div>

    {{-- Booking Modal --}}
    <div id="booking-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-[60] flex items-center justify-center transition-all duration-300 opacity-0 invisible">
        <div id="booking-box" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 scale-90">
            {{-- Modal Header --}}
            <div class="bg-red-600 text-white px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-lg flex items-center"><i class="fas fa-tools mr-2"></i> Đặt Lịch Kỹ Thuật Viên</h3>
                <button onclick="toggleBooking()" class="text-white/80 hover:text-white transition focus:outline-none"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            {{-- Modal Body --}}
            <form id="bookingForm" action="/book-appointment" method="POST" class="p-6 space-y-4" onsubmit="validateBooking(event)">
                @csrf 
                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="input_name" required maxlength="50" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm" placeholder="Nguyễn Văn A" onblur="checkName()" oninput="clearError('name')">
                    <p id="error_name" class="text-red-500 text-xs mt-1 hidden font-medium"><i class="fas fa-exclamation-circle mr-1"></i> Tên không hợp lệ.</p>
                </div>
                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone_number" id="input_phone" required maxlength="10" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm" placeholder="09xxxxxxx" onblur="checkPhone()" oninput="clearError('phone')">
                    <p id="error_phone" class="text-red-500 text-xs mt-1 hidden font-medium"><i class="fas fa-exclamation-circle mr-1"></i> SĐT không hợp lệ.</p>
                </div>
                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Địa chỉ <span class="text-red-500">*</span></label>
                    <input type="text" name="address" id="input_address" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm" placeholder="Ví dụ: Số 12, đường Lý Thái Tổ...">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Ngày hẹn <span class="text-red-500">*</span></label>
                        <input type="date" id="date_picker" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm" onchange="checkDate()" oninput="clearError('date')">
                        <p id="error_date" class="text-red-500 text-xs mt-1 hidden font-medium"><i class="fas fa-exclamation-circle mr-1"></i> Ngày không hợp lệ.</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Ca làm việc <span class="text-red-500">*</span></label>
                        <select id="shift_picker" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm bg-white">
                            <option value="08:00">Ca Sáng (8h - 11h30)</option>
                            <option value="14:00">Ca Chiều (13h30 - 17h)</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="booking_time" id="real_booking_time">

                <div>
                    <label class="block text-gray-700 text-xs font-bold uppercase mb-1">Mô tả sự cố <span class="text-red-500">*</span></label>
                    <textarea name="issue_description" id="input_issue" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm" placeholder="Ví dụ: Camera bị mất hình, máy tính không lên nguồn..."></textarea>
                </div>
                
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition shadow-md transform active:scale-95 uppercase text-sm tracking-wide">
                    GỬI YÊU CẦU NGAY
                </button>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. CÁC HÀM CHATBOT (TOÀN CỤC)
        window.toggleChat = function() {
            const chat = document.getElementById('chat-window');
            if (chat.classList.contains('invisible')) {
                chat.classList.remove('invisible', 'opacity-0', 'scale-0'); chat.classList.add('visible', 'opacity-100', 'scale-100');
                const content = document.getElementById('chat-content'); if(content) content.scrollTop = content.scrollHeight;
            } else {
                chat.classList.remove('visible', 'opacity-100', 'scale-100'); chat.classList.add('invisible', 'opacity-0', 'scale-0');
            }
        };

        function appendMessage(sender, text) {
            const content = document.getElementById('chat-content');
            if (!content) return;
            
            let html = '';
            if(sender === 'user') {
                html = `<div class="flex justify-end mt-3 animate-fade-in"><div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none text-xs leading-relaxed max-w-[85%] shadow-sm">${text}</div></div>`;
            } else {
                html = `<div class="flex items-start mt-3 animate-fade-in">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600 text-xs mr-2 border border-blue-200"><i class="fa-solid fa-robot"></i></div>
                            <div class="bg-white border border-gray-200 text-gray-700 p-3 rounded-2xl rounded-tl-none shadow-sm text-xs leading-relaxed max-w-[85%]">${text}</div>
                        </div>`;
            }
            
            content.insertAdjacentHTML('beforeend', html);
            content.scrollTop = content.scrollHeight;
        }

        window.sendMessage = function(text = null) {
            if(window.event) window.event.stopPropagation();

            const input = document.getElementById('chat-input');
            const message = text || (input ? input.value.trim() : '');
            
            if (!message) return;
            if (!text && input) input.value = '';

            appendMessage('user', message);

            const loadingId = 'loading-' + Date.now();
            const content = document.getElementById('chat-content');
            if(content) {
                content.insertAdjacentHTML('beforeend', 
                    `<div id="${loadingId}" class="flex items-center mt-3 ml-10 text-xs text-gray-400 italic animate-pulse">
                        <span class="mr-1">Đang trả lời</span> <span class="loading-dots">...</span>
                    </div>`
                );
                content.scrollTop = content.scrollHeight;
            }

            // GỌI API
            fetch('{{ route("chatbot.ask") }}', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                const loader = document.getElementById(loadingId);
                if(loader) loader.remove();
                
                appendMessage('bot', data.reply);

                if (data.suggestions && data.suggestions.length > 0 && content) {
                    let buttonsHtml = '<div class="flex flex-wrap gap-2 mt-2 ml-10 animate-fade-in">';
                    data.suggestions.forEach(btnText => {
                        buttonsHtml += `<button type="button" onclick="window.sendMessage('${btnText}')" class="ignore-click bg-white border border-blue-200 text-blue-600 text-[11px] font-medium px-3 py-1.5 rounded-full hover:bg-blue-50 transition shadow-sm cursor-pointer transform hover:-translate-y-0.5">${btnText}</button>`;
                    });
                    buttonsHtml += '</div>';
                    content.insertAdjacentHTML('beforeend', buttonsHtml);
                    content.scrollTop = content.scrollHeight;
                }
            })
            .catch(error => {
                const loader = document.getElementById(loadingId);
                if(loader) loader.remove();
                appendMessage('bot', 'Lỗi kết nối. Vui lòng thử lại sau.');
            });
        };
        
        const chatInput = document.getElementById('chat-input');
        if(chatInput) chatInput.addEventListener('keypress', function (e) { if (e.key === 'Enter') window.sendMessage(); });


        // 2. XỬ LÝ CLICK (GLOBAL)
        document.addEventListener('click', function(event) {
            // Booking
            const bookingModal = document.getElementById('booking-modal');
            if (bookingModal && !bookingModal.classList.contains('invisible') && event.target === bookingModal) toggleBooking();
            
            // Chatbot
            const chatWindow = document.getElementById('chat-window');
            const chatBtn = document.getElementById('chat-btn');
            
            if (chatWindow && !chatWindow.classList.contains('invisible') && 
                !chatWindow.contains(event.target) && 
                !chatBtn.contains(event.target) &&
                !event.target.closest('.ignore-click')) { 
                toggleChat();
            }

            // Search
            const searchContainer = document.getElementById('searchContainer');
            const searchDropdown = document.getElementById('searchDropdown');
            if (searchContainer && searchDropdown && !searchContainer.contains(event.target) && !searchDropdown.classList.contains('invisible')) toggleSearchDropdown();
        });


        // 3. CÁC HÀM KHÁC
        let ToastMini;
        try { ToastMini = Swal.mixin({ width: 380, padding: '1rem', customClass: { popup: 'small-popup-text' } }); } catch(e){}

        window.toggleSearchDropdown = function() {
            var dropdown = document.getElementById('searchDropdown');
            var input = document.getElementById('searchInput');
            if (dropdown.classList.contains('invisible')) { dropdown.classList.remove('invisible', 'opacity-0', 'scale-95'); dropdown.classList.add('visible', 'opacity-100', 'scale-100'); setTimeout(() => { input.focus(); }, 100); } 
            else { dropdown.classList.add('invisible', 'opacity-0', 'scale-95'); dropdown.classList.remove('visible', 'opacity-100', 'scale-100'); }
        };

        // Hàm Validation form (Giữ nguyên logic cũ, chỉ đổi class css nếu cần)
        function showError(fieldId, msgId) { document.getElementById(fieldId).classList.add('border-red-500', 'bg-red-50', 'ring-1', 'ring-red-500'); document.getElementById(fieldId).classList.remove('border-gray-300'); document.getElementById(msgId).classList.remove('hidden'); }
        function clearError(type) { let fieldId, msgId; if (type === 'phone') { fieldId = 'input_phone'; msgId = 'error_phone'; } else if (type === 'date') { fieldId = 'date_picker'; msgId = 'error_date'; } else if (type === 'name') { fieldId = 'input_name'; msgId = 'error_name'; } document.getElementById(fieldId).classList.remove('border-red-500', 'bg-red-50', 'ring-1', 'ring-red-500'); document.getElementById(msgId).classList.add('hidden'); }
        function checkName() { var name = document.getElementById('input_name').value.trim(); if (name === '' || /\d/.test(name)) { showError('input_name', 'error_name'); return false; } return true; }
        function checkPhone() { var phone = document.getElementById('input_phone').value.trim(); if (phone !== '' && !/^(0)[0-9]{9}$/.test(phone)) { showError('input_phone', 'error_phone'); return false; } return true; }
        function checkDate() { var dateVal = document.getElementById('date_picker').value; if (!dateVal) return false; var selectedDate = new Date(dateVal); var today = new Date(); today.setHours(0,0,0,0); if (selectedDate < today) { showError('date_picker', 'error_date'); return false; } return true; }
        function validateBooking(e) { e.preventDefault(); if (!checkName()) { document.getElementById('input_name').focus(); return false; } if (!checkPhone()) { document.getElementById('input_phone').focus(); return false; } if (!checkDate()) { document.getElementById('date_picker').focus(); return false; } document.getElementById('real_booking_time').value = document.getElementById('date_picker').value + 'T' + document.getElementById('shift_picker').value; document.getElementById('bookingForm').submit(); }
        function setupBookingTime() { const dateInput = document.getElementById('date_picker'); const now = new Date(); const pad = (n) => n < 10 ? '0' + n : n; dateInput.min = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()); if (!dateInput.value) dateInput.value = dateInput.min; }
        
        window.toggleBooking = function() {
            const modal = document.getElementById('booking-modal');
            const box = document.getElementById('booking-box');
            if (modal.classList.contains('invisible')) { modal.classList.remove('invisible', 'opacity-0'); modal.classList.add('visible', 'opacity-100'); box.classList.remove('scale-90'); box.classList.add('scale-100'); setupBookingTime(); } 
            else { modal.classList.remove('visible', 'opacity-100'); modal.classList.add('invisible', 'opacity-0'); box.classList.remove('scale-100'); box.classList.add('scale-90'); }
        };

        @if(session('success')) try { ToastMini.fire({ title: 'THÀNH CÔNG!', text: '{{ session('success') }}', icon: 'success', confirmButtonColor: '#2563eb' }); } catch(e){} @endif
        @if(session('error')) try { ToastMini.fire({ title: 'CÓ LỖI!', text: '{{ session('error') }}', icon: 'error', confirmButtonColor: '#dc2626' }); } catch(e){} @endif
    </script>
    
    @stack('scripts')
</body>
</html>