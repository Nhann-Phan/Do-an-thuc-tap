<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - TechShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { overflow-x: hidden; background-color: #f8f9fa; }
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar-wrapper { min-width: 250px; max-width: 250px; min-height: 100vh; background-color: #0f172a; color: white; transition: all 0.3s; }
        #sidebar-wrapper .sidebar-heading { padding: 1.5rem; font-size: 1.2rem; font-weight: bold; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        #sidebar-wrapper .list-group-item { background: transparent; color: #adb5bd; border: none; padding: 1rem 1.5rem; }
        #sidebar-wrapper .list-group-item:hover, #sidebar-wrapper .list-group-item.active { background: rgba(255,255,255,0.1); color: #fff; text-decoration: none; border-left: 4px solid #0d6efd; }
        #page-content-wrapper { width: 100%; }
        .content-padding { padding: 20px; }
    </style>
</head>
<body>

    <div id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-uppercase">
                <i class="fas fa-user-shield me-2"></i> Admin Panel
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="/admin" class="list-group-item {{ request()->is('admin') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2 w-25"></i> Dashboard
                </a>
                <a href="{{ route('categories.index') }}" class="list-group-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fas fa-list me-2 w-25"></i> Danh mục
                </a>

                <a href="{{ route('product.index_admin') }}" class="list-group-item {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <i class="fas fa-boxes me-2 w-25"></i> Tất cả sản phẩm
                </a>
                <a href="{{ route('gallery.index') }}" class="list-group-item {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                    <i class="fas fa-images me-2 w-25"></i> Thư viện ảnh
                </a>
                <a href="/" target="_blank" class="list-group-item">
                    <i class="fas fa-globe me-2 w-25"></i> Xem trang chủ
                </a>
                <a href="/logout" class="list-group-item text-danger">
                    <i class="fas fa-sign-out-alt me-2 w-25"></i> Đăng xuất
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-3">
                <button class="btn btn-light" id="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="ms-auto fw-bold text-secondary">
                    Xin chào, Quản trị viên
                </div>
            </nav>

            <div class="container-fluid content-padding">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");
        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
    </script>
</body>
</html>