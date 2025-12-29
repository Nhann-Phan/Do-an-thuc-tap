<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPM Technology</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hover-underline-animation { display: inline-block; position: relative; }
        .hover-underline-animation::after { content: ''; position: absolute; width: 100%; transform: scaleX(0); height: 2px; bottom: 0; left: 0; background-color: #0056b3; transform-origin: bottom right; transition: transform 0.25s ease-out; }
        .hover-underline-animation:hover::after { transform: scaleX(1); transform-origin: bottom left; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <div class="bg-blue-900 text-white text-xs py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex space-x-4">
                <span><i class="fas fa-envelope mr-1"></i> contact@gpm.vn</span>
                <span><i class="fas fa-phone-alt mr-1"></i> 0902 777 186</span>
            </div>
            <div class="flex space-x-3">
                <a href="/login" class="hover:text-blue-300 ml-2 border-l pl-3 border-blue-700">Đăng nhập Admin</a>
            </div>
        </div>
    </div>

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 relative">
            <div class="flex justify-between items-center h-20">
                
                <a href="/" class="flex items-center z-20 flex-shrink-0">
                    <div class="text-3xl font-extrabold text-blue-800 tracking-tighter">GPM</div>
                    <div class="ml-2 flex flex-col leading-none">
                        <span class="text-xs font-bold text-gray-600 tracking-widest">GIẢI PHÁP</span>
                        <span class="text-xs font-bold text-blue-600 tracking-widest">CÔNG NGHỆ</span>
                    </div>
                </a>

                <div class="flex items-center space-x-6 ml-auto">
                    <nav class="hidden md:flex space-x-6 font-medium text-sm uppercase text-gray-600 items-center h-full">
                        <a href="/" class="hover:text-blue-800 transition">Trang chủ</a>
                        <a href="#" class="hover:text-blue-800 transition">Giới thiệu</a>

                        <div class="group static h-full flex items-center py-6"> 
                            <a href="#" class="text-blue-800 font-bold flex items-center transition relative z-10 cursor-pointer">
                                SẢN PHẨM <i class="fas fa-chevron-down ml-1 text-[10px]"></i>
                            </a>

                            <div class="absolute top-full left-0 w-full invisible opacity-0 translate-y-3 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out z-50 font-sans shadow-2xl border-t-2 border-blue-500 text-left">
                                <div class="absolute -top-10 left-0 w-full h-12 bg-transparent"></div>
                                
                                @php
                                    $cats = isset($menuCategories) ? $menuCategories : collect([]);
                                    $gridCols = $cats->count() > 0 ? $cats->count() : 1;
                                @endphp

                                <div class="bg-[#003468] text-white">
                                    <div class="container mx-auto px-4">
                                        <div class="grid text-[13px] font-bold uppercase tracking-wide" style="grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));">
                                            @foreach($cats as $parent)
                                            <div class="py-3 px-2 border-r border-blue-500/30 flex items-center justify-center text-center h-full last:border-r-0">
                                                <i class="{{ $parent->icon ?? 'fas fa-folder' }} mr-2 {{ $loop->index == 0 ? 'text-yellow-400' : 'text-blue-300' }}"></i> 
                                                <span>{{ $parent->name }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white text-gray-700">
                                    <div class="container mx-auto px-4 py-5">
                                        <div class="grid gap-0" style="grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));">
                                            @foreach($cats as $parent)
                                            <div class="px-4 border-r border-gray-100 h-full last:border-r-0">
                                                <ul class="space-y-2 text-[13px] font-medium text-gray-600">
                                                    @foreach($parent->children as $child)
                                                    <li>
                                                        <a href="{{ route('frontend.category.show', $child->id) }}" class="hover:text-blue-700 hover:font-bold transition block py-0.5 transform hover:translate-x-1">
                                                            <i class="fas fa-caret-right text-gray-300 mr-1.5 text-[10px]"></i> {{ $child->name }}
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

                        <a href="#gallery" class="hover:text-blue-800 transition">Dự án</a>
                        <a href="#" class="hover:text-blue-800 transition">Liên hệ</a>
                    </nav>

                    <div class="h-6 w-px bg-gray-300 hidden md:block"></div>

                    <a href="{{ route('cart.index') }}" class="relative group flex items-center text-gray-600 hover:text-blue-800 transition">
                        <div class="relative p-2">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            @if(session('cart'))
                                <span class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full ring-2 ring-white">
                                    {{ count((array) session('cart')) }}
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="relative" id="searchContainer">
                        <button type="button" onclick="toggleSearchDropdown()" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-800 hover:bg-gray-100 rounded-full transition focus:outline-none">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                        <div id="searchDropdown" class="absolute right-0 top-full mt-4 w-80 bg-white rounded-lg shadow-xl border border-gray-200 p-4 z-50 invisible opacity-0 scale-95 transform transition-all duration-200 origin-top-right">
                            <div class="absolute -top-2 right-3 w-4 h-4 bg-white transform rotate-45 border-l border-t border-gray-200"></div>
                            <form action="#" method="GET" class="relative">
                                <input type="text" name="q" id="searchInput" class="w-full border border-gray-300 text-gray-700 text-sm rounded px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-inner" placeholder="Nhập tên sản phẩm...">
                                <button type="submit" class="absolute right-2 top-1.5 text-white bg-blue-900 hover:bg-blue-800 rounded px-3 py-1 text-xs font-bold transition">TÌM</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-300 py-10 text-sm mt-auto">
        <div class="container mx-auto px-4 text-center">
            Copyright © 2025 TechShop An Giang.
        </div>
    </footer>

    <div id="compare-bar" class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t z-50 transform translate-y-full transition-transform duration-300">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="font-bold text-gray-700">So sánh:</span>
                <div id="compare-list" class="flex space-x-2"></div>
            </div>
            <div class="flex space-x-2">
                <button onclick="clearCompare()" class="text-gray-500 hover:text-red-500 text-sm underline">Xóa hết</button>
                <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 font-bold text-sm">SO SÁNH NGAY</button>
            </div>
        </div>
    </div>

    <div class="fixed bottom-8 right-6 z-40">
        <button id="chat-btn" onclick="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-2xl flex items-center justify-center w-14 h-14 transition transform hover:scale-110">
            <i class="fas fa-comment-dots text-2xl"></i>
        </button>
    </div>

    <div id="chat-window" class="fixed bottom-24 right-6 w-80 bg-white rounded-lg shadow-2xl border border-gray-200 z-50 flex flex-col overflow-hidden h-96 transition-all duration-300 ease-in-out transform origin-bottom-right scale-0 opacity-0 invisible">
        <div class="bg-blue-800 text-white p-3 flex justify-between items-center">
            <span class="font-bold"><i class="fas fa-robot mr-2"></i>Trợ lý ảo GPM</span>
            <button onclick="toggleChat()" class="text-white hover:text-gray-300"><i class="fas fa-times"></i></button>
        </div>
        <div id="chat-content" class="flex-1 p-3 overflow-y-auto bg-gray-50 text-sm space-y-2">
            <div class="bg-blue-100 text-blue-800 p-2 rounded-lg self-start max-w-[80%]">
                Xin chào! Tôi có thể giúp gì cho bạn về Camera hay Thiết bị mạng không?
            </div>
        </div>
        <div class="p-2 border-t bg-white flex">
            <input type="text" id="chat-input" class="flex-1 border rounded-l px-2 py-1 focus:outline-none text-sm" placeholder="Nhập câu hỏi...">
            <button onclick="sendMessage()" class="bg-blue-600 text-white px-3 py-1 rounded-r hover:bg-blue-700"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <div class="fixed bottom-8 left-6 z-40">
        <button onclick="toggleBooking()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full shadow-2xl font-bold flex items-center gap-2 transition transform hover:scale-105 animate-bounce">
            <i class="fas fa-calendar-check"></i> Đặt Lịch Sửa Chữa
        </button>
    </div>

    <div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center transition-all duration-300 opacity-0 invisible">
        <div id="booking-box" class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 scale-90">
            <div class="bg-red-600 text-white p-4 flex justify-between items-center">
                <h3 class="font-bold text-lg">Đặt Lịch Kỹ Thuật Viên</h3>
                <button onclick="toggleBooking()" class="text-white hover:text-gray-200"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form id="bookingForm" action="/book-appointment" method="POST" class="p-6" onsubmit="validateBooking(event)">
                @csrf 
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="input_name" required maxlength="50"
                        class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-blue-500 transition-colors" 
                        placeholder="Nguyễn Văn A"
                        onblur="checkName()" oninput="clearError('name')">
                    <p id="error_name" class="text-red-500 text-xs italic mt-1 hidden">
                        <i class="fas fa-exclamation-circle"></i> Tên không hợp lệ.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone_number" id="input_phone" required maxlength="10"
                        class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-blue-500 transition-colors" 
                        placeholder="09xxxxxxx"
                        onblur="checkPhone()" oninput="clearError('phone')">
                    <p id="error_phone" class="text-red-500 text-xs italic mt-1 hidden">
                        <i class="fas fa-exclamation-circle"></i> SĐT không hợp lệ.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                    <input type="text" name="address" id="input_address" required 
                        class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-blue-500" 
                        placeholder="Ví dụ: Số 12, đường Lý Thái Tổ...">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Thời gian hẹn <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <input type="date" id="date_picker" required 
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-blue-500 transition-colors"
                                onchange="checkDate()" oninput="clearError('date')">
                            <p id="error_date" class="text-red-500 text-xs italic mt-1 hidden">
                                <i class="fas fa-exclamation-circle"></i> Không thể chọn ngày quá khứ.
                            </p>
                        </div>
                        <div class="flex-1">
                            <select id="shift_picker" class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-blue-500">
                                <option value="08:00">Ca Sáng (8h - 11h30)</option>
                                <option value="14:00">Ca Chiều (13h30 - 17h)</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="booking_time" id="real_booking_time">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Mô tả sự cố <span class="text-red-500">*</span></label>
                    <textarea name="issue_description" id="input_issue" rows="3" required 
                            class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-blue-500" 
                            placeholder="Ví dụ: Camera bị mất hình..."></textarea>
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded transition">GỬI YÊU CẦU NGAY</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Swal Config
        const ToastMini = Swal.mixin({ width: 380, padding: '1rem', customClass: { popup: 'small-popup-text' } });

        // Validation & Logic scripts (Giữ nguyên như cũ)
        function showError(fieldId, msgId) {
            document.getElementById(fieldId).classList.add('border-red-500', 'bg-red-50');
            document.getElementById(fieldId).classList.remove('border-gray-300');
            document.getElementById(msgId).classList.remove('hidden');
        }
        function clearError(type) {
            let fieldId, msgId;
            if (type === 'phone') { fieldId = 'input_phone'; msgId = 'error_phone'; }
            else if (type === 'date') { fieldId = 'date_picker'; msgId = 'error_date'; }
            else if (type === 'name') { fieldId = 'input_name'; msgId = 'error_name'; }
            document.getElementById(fieldId).classList.remove('border-red-500', 'bg-red-50');
            document.getElementById(msgId).classList.add('hidden');
        }
        function checkName() {
            var name = document.getElementById('input_name').value.trim();
            if (name === '' || /\d/.test(name)) { showError('input_name', 'error_name'); return false; }
            return true;
        }
        function checkPhone() {
            var phone = document.getElementById('input_phone').value.trim();
            if (phone !== '' && !/^(0)[0-9]{9}$/.test(phone)) { showError('input_phone', 'error_phone'); return false; }
            return true;
        }
        function checkDate() {
            var dateVal = document.getElementById('date_picker').value;
            if (!dateVal) return false;
            var selectedDate = new Date(dateVal);
            var today = new Date(); today.setHours(0,0,0,0);
            if (selectedDate < today) { showError('date_picker', 'error_date'); return false; }
            return true;
        }
        function validateBooking(e) {
            e.preventDefault();
            if (!checkName()) { document.getElementById('input_name').focus(); return false; }
            if (!checkPhone()) { document.getElementById('input_phone').focus(); return false; }
            if (!checkDate()) { document.getElementById('date_picker').focus(); return false; }
            
            document.getElementById('real_booking_time').value = document.getElementById('date_picker').value + 'T' + document.getElementById('shift_picker').value;
            document.getElementById('bookingForm').submit();
        }

        @if(session('success'))
            ToastMini.fire({ title: 'THÀNH CÔNG!', text: '{{ session('success') }}', icon: 'success', confirmButtonColor: '#2563eb' });
        @endif
        @if(session('error'))
            ToastMini.fire({ title: 'CÓ LỖI!', text: '{{ session('error') }}', icon: 'error', confirmButtonColor: '#dc2626' });
        @endif

        function setupBookingTime() {
            const dateInput = document.getElementById('date_picker');
            const now = new Date();
            const pad = (n) => n < 10 ? '0' + n : n;
            dateInput.min = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
            if (!dateInput.value) dateInput.value = dateInput.min;
        }

        function toggleBooking() {
            const modal = document.getElementById('booking-modal');
            const box = document.getElementById('booking-box');
            if (modal.classList.contains('invisible')) {
                modal.classList.remove('invisible', 'opacity-0'); modal.classList.add('visible', 'opacity-100');
                box.classList.remove('scale-90'); box.classList.add('scale-100');
                setupBookingTime();
            } else {
                modal.classList.remove('visible', 'opacity-100'); modal.classList.add('invisible', 'opacity-0');
                box.classList.remove('scale-100'); box.classList.add('scale-90');
            }
        }

        // Search
        function toggleSearchDropdown() {
            var dropdown = document.getElementById('searchDropdown');
            var input = document.getElementById('searchInput');
            if (dropdown.classList.contains('invisible')) {
                dropdown.classList.remove('invisible', 'opacity-0', 'scale-95'); dropdown.classList.add('visible', 'opacity-100', 'scale-100');
                setTimeout(() => { input.focus(); }, 100); 
            } else {
                dropdown.classList.add('invisible', 'opacity-0', 'scale-95'); dropdown.classList.remove('visible', 'opacity-100', 'scale-100');
            }
        }

        // Compare & Chat (Giữ nguyên logic cũ)
        let compareList = [];
        function addToCompare(id, name, img) { /* Logic compare cũ */ }
        function clearCompare() { compareList = []; document.getElementById('compare-bar').classList.add('translate-y-full'); document.getElementById('compare-list').innerHTML = ''; }
        function toggleChat() {
            const chat = document.getElementById('chat-window');
            if (chat.classList.contains('invisible')) {
                chat.classList.remove('invisible', 'opacity-0', 'scale-0'); chat.classList.add('visible', 'opacity-100', 'scale-100');
            } else {
                chat.classList.remove('visible', 'opacity-100', 'scale-100'); chat.classList.add('invisible', 'opacity-0', 'scale-0');
            }
        }
        function sendMessage() {
            const input = document.getElementById('chat-input');
            const content = document.getElementById('chat-content');
            if(input.value.trim() === '') return;
            content.innerHTML += `<div class="bg-gray-200 p-2 rounded-lg self-end max-w-[80%] ml-auto mt-2 text-right">${input.value}</div>`;
            input.value = '';
            setTimeout(() => {
                content.innerHTML += `<div class="bg-blue-100 text-blue-800 p-2 rounded-lg self-start max-w-[80%] mt-2"><i class="fas fa-robot mr-1"></i> Cảm ơn bạn!</div>`;
                content.scrollTop = content.scrollHeight;
            }, 1000);
        }
        document.getElementById('chat-input').addEventListener('keypress', function (e) { if (e.key === 'Enter') sendMessage(); });

        document.addEventListener('click', function(event) {
            const bookingModal = document.getElementById('booking-modal');
            if (!bookingModal.classList.contains('invisible') && event.target === bookingModal) toggleBooking();
            
            const chatWindow = document.getElementById('chat-window');
            const chatBtn = document.getElementById('chat-btn');
            if (!chatWindow.classList.contains('invisible') && !chatWindow.contains(event.target) && !chatBtn.contains(event.target)) toggleChat();

            const searchContainer = document.getElementById('searchContainer');
            const searchDropdown = document.getElementById('searchDropdown');
            if (!searchContainer.contains(event.target) && !searchDropdown.classList.contains('invisible')) toggleSearchDropdown();
        });
    </script>

    <style>
        .small-popup-text .swal2-title { font-size: 18px !important; }
        .small-popup-text .swal2-html-container { font-size: 14px !important; }
    </style>
    
    @stack('scripts')
</body>
</html>