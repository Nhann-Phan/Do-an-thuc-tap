<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // 1. Import View
use App\Models\Category; // 2. Import Model Category
use Illuminate\Pagination\Paginator;

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
        // Dùng Bootstrap cho phân trang (nếu bạn dùng paginate)
        Paginator::useBootstrap();

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