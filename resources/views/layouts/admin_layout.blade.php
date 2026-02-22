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
        
        /* Modal Animation */
        .modal-active { display: block !important; }
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
            
            {{-- QUẢN LÝ KHÁCH HÀNG --}}
            <a href="{{ route('admin.customers.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/customers*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-users"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Khách hàng</span>
            </a>

            {{-- XỬ LÝ LỊCH SỬ CHỮA CHỮA --}}
            <a href="/admin" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden group/item {{ request()->is('admin') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Xử lý lịch sửa chữa</span>
            </a>

            {{-- QUẢN LÝ ĐƠN HÀNG --}}
            <a href="{{ route('admin.orders.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Đơn hàng</span>
            </a>
            
            {{-- TẤT CẢ SẢN PHẨM --}}
            <a href="{{ route('product.index_admin') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/products*') ? 'active' : '' }}">
                <div class="min-w-[70px] flex justify-center text-lg">
                    <i class="fas fa-boxes"></i>
                </div>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Tất cả sản phẩm</span>
            </a>

            @if(Auth::check() && Auth::user()->role == 0)
                {{-- QUẢN LÝ TÀI KHOẢN --}}
                <a href="{{ route('admin.accounts.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/accounts*') ? 'active' : '' }}">
                    <div class="min-w-[70px] flex justify-center text-lg">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Tài khoản</span>
                </a>

                {{-- QUẢN LÝ DANH MỤC --}}
                <a href="{{ route('categories.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <div class="min-w-[70px] flex justify-center text-lg">
                        <i class="fas fa-list"></i>
                    </div>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Danh mục</span>
                </a>

                {{-- QUẢN LÝ TIN TỨC --}}
                <a href="{{ route('news.index_admin') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/news*') ? 'active' : '' }}">
                    <div class="min-w-[70px] flex justify-center text-lg">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Quản lý Tin tức</span>
                </a>

                {{-- THÔNG TIN CÔNG TY --}}
                <a href="{{ route('pages.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/pages*') ? 'active' : '' }}">
                    <div class="min-w-[70px] flex justify-center text-lg">
                        <i class="fas fa-building"></i>
                    </div>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Thông tin Công ty</span>
                </a>

                {{-- THƯ VIỆN ẢNH --}}
                <a href="{{ route('gallery.index') }}" class="menu-item flex items-center h-[50px] text-slate-400 hover:bg-white/5 hover:text-white border-l-[3px] border-transparent transition-all whitespace-nowrap overflow-hidden {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                    <div class="min-w-[70px] flex justify-center text-lg">
                        <i class="fas fa-images"></i>
                    </div>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">Thư viện ảnh</span>
                </a>
            @endif
        </nav>

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
            
            <div class="flex items-center gap-4 relative">
                <div class="text-right hidden sm:block">
                    <div class="font-bold text-sm text-gray-800">
                        {{ Auth::user()->name ?? 'Tài khoản' }}
                    </div>
                    <div class="text-xs {{ Auth::check() && Auth::user()->role == 0 ? 'text-red-600' : 'text-blue-600' }} font-medium flex items-center justify-end">
                        {{ Auth::check() && Auth::user()->role == 0 ? 'Quản trị viên' : 'Nhân viên bán hàng' }}
                    </div>
                </div>

                <button id="avatarBtn" onclick="toggleProfileMenu()" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition focus:outline-none cursor-pointer">
                    <i class="fas fa-user"></i>
                </button>

                {{-- Menu thả xuống --}}
                <div id="profileDropdown" class="hidden absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden z-50">
                    <button type="button" onclick="openPasswordModal()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fas fa-key w-5 text-center text-gray-400 mr-1"></i> Đổi mật khẩu
                    </button>
                    <a href="/logout" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition border-t border-gray-50">
                        <i class="fas fa-sign-out-alt w-5 text-center mr-1"></i> Đăng xuất
                    </a>
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
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert-error" class="flex items-center justify-between p-4 mb-4 text-red-800 border border-red-200 rounded-lg bg-red-50 shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" onclick="document.getElementById('alert-error').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)] border border-gray-200 min-h-full">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- MODAL ĐỔI MẬT KHẨU --}}
    <div id="passwordModal" class="fixed inset-0 z-[999] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closePasswordModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-800">Đổi Mật Khẩu Cá Nhân</h3>
                    <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="passwordForm" action="{{ route('admin.profile.update_password') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="mb-4 p-2 bg-red-50 text-red-600 text-xs rounded border border-red-100">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                        <input type="password" name="new_password" required class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" required class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Hủy bỏ</button>
                    {{-- Đảm bảo nút này là type="submit" --}}
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md">Lưu thay đổi</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    {{-- Script xử lý Dropdown & Modal --}}
    <script>
        function toggleProfileMenu() {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        }

        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('profileDropdown').classList.add('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('passwordForm').reset();
        }

        window.addEventListener('click', function(e) {
            const avatarBtn = document.getElementById('avatarBtn');
            const dropdown = document.getElementById('profileDropdown');
            if (avatarBtn && !avatarBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        @if($errors->any())
        openPasswordModal();
        @endif
    </script>
</body>
</html>