<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - TechShop</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FontAwesome & Font --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Sidebar transition & width logic */
        .sidebar { width: 70px; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar:hover { width: 260px; }
        
        /* Main wrapper margin logic matches sidebar width */
        .main-wrapper { margin-left: 70px; transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar:hover ~ .main-wrapper { margin-left: 260px; }

        /* Hide scrollbar for sidebar menu */
        .sidebar-menu::-webkit-scrollbar { display: none; }
        .sidebar-menu { -ms-overflow-style: none; scrollbar-width: none; }

        /* Active menu item styling */
        .menu-item.active { background-color: rgba(255,255,255,0.05); color: #fff; border-left-color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 overflow-x-hidden">

    {{-- SIDEBAR --}}
    <aside class="sidebar fixed top-0 left-0 h-screen bg-[#0f172a] z-50 flex flex-col overflow-hidden border-r border-white/5 shadow-xl group">
        
        {{-- Logo Header --}}
        <div class="h-[60px] flex items-center bg-black/20 border-b border-white/5 whitespace-nowrap overflow-hidden flex-shrink-0">
            <div class="min-w-[70px] flex justify-center text-xl text-yellow-400">
                <i class="fa-regular fa-user"></i>
            </div>
            <span class="text-white font-bold text-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                ADMIN
            </span>
        </div>

        {{-- Menu Items --}}
        <nav class="sidebar-menu flex-grow py-2 overflow-y-auto">
            <a href="/admin" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden group/item {{ request()->is('admin') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Xử lý lịch sửa chữa</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Đơn hàng</span>
            </a>

            <a href="{{ route('categories.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-list"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Danh mục</span>
            </a>
            
            <a href="{{ route('product.index_admin') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/products*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-boxes"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Tất cả sản phẩm</span>
            </a>

            <a href="{{ route('news.index_admin') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/news*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-newspaper"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Tin tức</span>
            </a>

            <a href="{{ route('gallery.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-images"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Thư viện ảnh</span>
            </a>
        </nav>

        {{-- Footer Menu --}}
        <div class="pb-2 border-t border-white/5">
            <a href="/" target="_blank" class="flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-globe"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Xem trang chủ</span>
            </a>
            <a href="/logout" class="flex items-center h-[50px] text-red-400 hover:bg-white/5 hover:text-red-300 border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Đăng xuất</span>
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="main-wrapper flex flex-col min-h-screen">
        
        {{-- TOPBAR --}}
        <header class="h-[60px] bg-white flex items-center justify-between px-6 shadow-sm sticky top-0 z-40 border-b border-gray-100">
            <div class="flex items-center">
                <h5 class="m-0 font-bold text-gray-600 text-lg">Hệ Thống Quản Trị</h5>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="font-bold text-sm text-gray-800">Admin</div>
                    <div class="text-green-600 text-[11px] font-medium flex items-center justify-end">
                        <i class="fas fa-circle text-[8px] mr-1.5"></i>Online
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        {{-- CONTENT BODY --}}
        <main class="flex-grow p-6">
            @if(session('success'))
                <div id="alert-success" class="flex items-center justify-between p-4 mb-4 text-green-800 border border-green-200 rounded-lg bg-green-50 shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" onclick="document.getElementById('alert-success').remove()">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)] border border-gray-200 min-h-full">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>