<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // 1. Import View
use App\Models\Category; // 2. Import Model Category
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use App\Models\Page; // Import Model Page

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- CẬP NHẬT Ở ĐÂY ---
        View::composer('*', function ($view) {
            $introPages = Page::where('is_active', true)
                              ->where('show_in_menu', true) // <--- THÊM DÒNG NÀY: Chỉ lấy trang được phép hiện menu
                              ->orderBy('position', 'asc')
                              ->get();
            $view->with('introPages', $introPages);
        });
        // ----------------------

        // Nếu đang chạy ngrok (hoặc môi trường production), ép dùng HTTPS
        if($this->app->environment('production') || str_contains(request()->url(), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }

        // 3. CHIA SẺ BIẾN $menuCategories CHO TẤT CẢ CÁC VIEW
        // Dùng try-catch để tránh lỗi khi chạy lệnh migrate lúc chưa có bảng dữ liệu
        try {
            // Kiểm tra xem class Category có tồn tại không để tránh lỗi
            if (class_exists(Category::class)) {
                $menuCategories = Category::whereNull('parent_id')->with('children')->get();
                View::share('menuCategories', $menuCategories);
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu database chưa kết nối được
        }
    }
}