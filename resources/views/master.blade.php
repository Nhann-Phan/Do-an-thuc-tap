<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPM Technology Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <span><i class="fas fa-envelope mr-1"></i> info@gpm.vn</span>
                <span><i class="fas fa-phone-alt mr-1"></i> 0986.596.343</span>
            </div>
            <div class="flex space-x-3">
                <a href="#" class="hover:text-blue-300"><i class="fab fa-facebook-f"></i></a>
                <a href="/login" class="hover:text-blue-300 ml-2 border-l pl-3 border-blue-700">Đăng nhập Admin</a>
            </div>
        </div>
    </div>

    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center">
                <div class="text-3xl font-extrabold text-blue-800 tracking-tighter">GPM</div>
                <div class="ml-2 flex flex-col leading-none">
                    <span class="text-xs font-bold text-gray-600 tracking-widest">GIẢI PHÁP</span>
                    <span class="text-xs font-bold text-blue-600 tracking-widest">CÔNG NGHỆ</span>
                </div>
            </a>

            <nav class="hidden md:flex space-x-8 font-medium text-sm uppercase text-gray-600">
                <a href="/" class="text-blue-800 font-bold hover-underline-animation">Trang chủ</a>
                <a href="#" class="hover:text-blue-800 hover-underline-animation">Giới thiệu</a>
                <a href="#" class="hover:text-blue-800 hover-underline-animation">Sản phẩm</a>
                <a href="#" class="hover:text-blue-800 hover-underline-animation">Liên hệ</a>
            </nav>

            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-blue-800"><i class="fas fa-search text-lg"></i></button>
                <div class="relative">
                    <button class="text-gray-500 hover:text-blue-800"><i class="fas fa-shopping-cart text-lg"></i></button>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">0</span>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-300 py-10 text-sm">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-white font-bold text-lg mb-4 uppercase">Về GPM Việt Nam</h3>
                <p class="leading-relaxed mb-4 text-xs text-gray-400">Chuyên cung cấp các giải pháp phần mềm quản lý, thiết bị mã số mã vạch, máy in hóa đơn.</p>
            </div>
            <div>
                <h3 class="text-white font-bold mb-4 uppercase">Hỗ trợ khách hàng</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white">Chính sách bảo hành</a></li>
                    <li><a href="#" class="hover:text-white">Tra cứu đơn hàng</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-bold mb-4 uppercase">Liên hệ</h3>
                <ul class="space-y-3 text-xs">
                    <li class="flex items-start"><i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-500"></i> Rạch Giá, Kiên Giang</li>
                    <li class="flex items-center"><i class="fas fa-phone mt-1 mr-2 text-blue-500"></i> 0986.596.343</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-4 text-center text-xs text-gray-500">
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

    @stack('scripts')
</body>
</html>