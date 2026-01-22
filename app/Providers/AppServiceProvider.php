<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use App\Models\Page;

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
        // 1. Cấu hình HTTPS (Chỉ chạy khi có request thực tế, tránh lỗi CLI)
        if (!$this->app->runningInConsole()) {
            if($this->app->environment('production') || str_contains(request()->url(), 'ngrok-free.app')) {
                URL::forceScheme('https');
            }
        }

        // 2. CHỈ chạy các query view composer/share khi KHÔNG PHẢI là Console
        // Điều này giúp các lệnh php artisan chạy vèo vèo mà không bị treo do DB
        if (!$this->app->runningInConsole()) {

            // --- MENU PAGE (View Composer) ---
            // View::composer an toàn hơn View::share vì nó chỉ chạy khi View thực sự được render
            View::composer('*', function ($view) {
                try {
                    $introPages = Page::where('is_active', true)
                                      ->where('show_in_menu', true)
                                      ->orderBy('position', 'asc')
                                      ->get();
                    $view->with('introPages', $introPages);
                } catch (\Exception $e) {
                    // Nếu lỗi DB thì trả về mảng rỗng để web không sập hẳn
                    $view->with('introPages', collect([]));
                }
            });

            // --- MENU CATEGORY (View Share) ---
            // Đây là đoạn gây treo nặng nhất. Đã bọc trong runningInConsole nên sẽ an toàn.
            try {
                if (class_exists(Category::class)) {
                    // Kiểm tra xem bảng categories có tồn tại không (đề phòng lúc mới clone code chưa migrate)
                    if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
                        View::share('menuCategories', $menuCategories);
                    }
                }
            } catch (\Exception $e) {
                // Nếu DB chưa sẵn sàng, share biến rỗng để view không báo lỗi undefined variable
                View::share('menuCategories', collect([]));
            }
        }
    }
}