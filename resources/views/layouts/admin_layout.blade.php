<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - TechShop</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-collapsed: 70px; 
            --sidebar-expanded: 260px;
            --sidebar-bg: #0f172a;
            --topbar-height: 60px;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f3f4f6; 
            overflow-x: hidden; 
        }

        /* --- SIDEBAR --- */
        .sidebar {
            position: fixed;
            top: 0; left: 0; height: 100vh;
            width: var(--sidebar-collapsed);
            background-color: var(--sidebar-bg);
            transition: width 0.3s ease-in-out;
            z-index: 1000;
            display: flex; flex-direction: column;
            overflow: hidden;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar:hover {
            width: var(--sidebar-expanded);
            box-shadow: 5px 0 15px rgba(0,0,0,0.1);
        }

        /* LOGO */
        .sidebar-header {
            height: var(--topbar-height);
            display: flex; align-items: center;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            white-space: nowrap; overflow: hidden;
        }
        .logo-icon {
            min-width: var(--sidebar-collapsed);
            display: flex; justify-content: center;
            font-size: 1.4rem; color: #facc15;
        }
        .logo-text {
            color: #fff; font-weight: 700; font-size: 1.1rem;
            opacity: 0; transition: opacity 0.2s 0.1s;
        }
        .sidebar:hover .logo-text { opacity: 1; }

        /* MENU */
        .sidebar-menu { flex-grow: 1; padding: 10px 0; overflow-y: auto; }
        .sidebar-menu::-webkit-scrollbar { display: none; }

        .menu-item {
            display: flex; align-items: center; height: 50px;
            color: #94a3b8; text-decoration: none;
            transition: all 0.2s; border-left: 3px solid transparent;
            white-space: nowrap; overflow: hidden;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.05);
            color: #fff; border-left-color: #3b82f6;
        }
        .menu-item i {
            min-width: var(--sidebar-collapsed);
            display: flex; justify-content: center; font-size: 1.2rem;
        }
        .menu-item span { opacity: 0; transition: opacity 0.3s; }
        .sidebar:hover .menu-item span { opacity: 1; }

        .sidebar-footer { padding-bottom: 10px; border-top: 1px solid rgba(255,255,255,0.05); }

        /* --- MAIN WRAPPER --- */
        .main-wrapper {
            margin-left: var(--sidebar-collapsed);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover + .main-wrapper { margin-left: var(--sidebar-expanded); }

        .topbar {
            height: var(--topbar-height); background: #fff;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 25px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            position: sticky; top: 0; z-index: 99;
        }

        .content-body { padding: 25px; flex-grow: 1; }
        .page-content-card {
            background: #fff; border-radius: 12px; padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #e5e7eb;
            min-height: 100%;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon"><i class="fas fa-bolt"></i></div>
            <span class="logo-text">TECH ADMIN</span>
        </div>

        <nav class="sidebar-menu">
            <a href="/admin" class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Quản lý Đơn hàng</span>
            </a>

            <a href="{{ route('categories.index') }}" class="menu-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>Quản lý Danh mục</span>
            </a>
            
            <a href="{{ route('product.index_admin') }}" class="menu-item {{ request()->is('admin/products*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <span>Tất cả sản phẩm</span>
            </a>

            {{-- MENU TIN TỨC (NEW) --}}
            <a href="{{ route('news.index_admin') }}" class="menu-item {{ request()->is('admin/news*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Quản lý Tin tức</span>
            </a>

            <a href="{{ route('gallery.index') }}" class="menu-item {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>Thư viện ảnh</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/" target="_blank" class="menu-item">
                <i class="fas fa-globe"></i>
                <span>Xem trang chủ</span>
            </a>
            <a href="/logout" class="menu-item text-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <h5 class="m-0 fw-bold text-secondary">Hệ Thống Quản Trị</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold small text-dark">Admin</div>
                    <div class="text-success small" style="font-size: 11px;"><i class="fas fa-circle me-1"></i>Online</div>
                </div>
                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fas fa-user text-secondary"></i>
                </div>
            </div>
        </header>

        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="page-content-card">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>