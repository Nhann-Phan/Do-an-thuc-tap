<?php

use Illuminate\Support\Facades\Route;

// ====================================================
// IMPORT CÁC CONTROLLER
// ====================================================
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatbotController; 
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CompareController;

// Admin Namespace
use App\Http\Controllers\Admin\PageController; 
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\CustomerController;

// ====================================================
// 1. KHU VỰC CÔNG KHAI (KHÁCH HÀNG - CLIENT)
// ====================================================

// --- Trang chủ ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Sản phẩm & Danh mục ---
Route::controller(ProductController::class)->group(function() {
    Route::get('/san-pham', 'index')->name('product.index'); 
    Route::get('/danh-muc/{id}', 'showByCategory')->name('frontend.category.show');
    Route::get('/san-pham/{id}', 'show')->name('product.detail');
});

// --- So sánh sản phẩm (Client) ---
Route::controller(CompareController::class)->prefix('compare')->name('compare.')->group(function() {
    Route::get('/', 'index')->name('index');     // Trang so sánh
    Route::post('/add', 'add')->name('add');       // Ajax thêm
    Route::post('/remove', 'remove')->name('remove'); // Ajax xóa
});

// --- Trang Tĩnh (Giới thiệu, Chính sách...) ---
Route::get('/gioi-thieu/{slug}', [HomeController::class, 'showPage'])->name('client.page.detail');

// --- Tin tức (News) ---
Route::controller(NewsController::class)->prefix('tin-tuc')->name('client.news.')->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'detail')->name('detail');
});

// --- Đặt lịch hẹn (Booking) ---
Route::post('/book-appointment', [BookingController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('booking.store');

// --- Xác thực (Login/Logout) ---
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login'); 
    Route::post('/login', 'login'); 
    Route::get('/logout', 'logout'); 
});

// --- GIỎ HÀNG (Cart) ---
Route::controller(CartController::class)->group(function () {
    Route::get('/gio-hang', 'index')->name('cart.index');
    Route::get('/add-to-cart/{id}', 'addToCart')->name('add_to_cart');
    Route::patch('/update-cart', 'update')->name('update_cart');
    Route::delete('/remove-from-cart', 'remove')->name('remove_from_cart');
    Route::get('/buy-now/{id}', 'buyNow')->name('buy_now');
});

// --- THANH TOÁN (Checkout) ---
Route::controller(CheckoutController::class)->prefix('thanh-toan')->name('checkout.')->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'process')->name('process');
});

// --- CHATBOT AI ---
Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
    ->middleware('throttle:10,1') 
    ->name('chatbot.ask');


// ====================================================
// 2. KHU VỰC QUẢN TRỊ (ADMIN) - YÊU CẦU ĐĂNG NHẬP
// ====================================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // --- Dashboard ---
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard'); 
    
    // --- Quản lý Booking ---
    Route::get('/booking/update/{id}/{status}', [AdminController::class, 'updateStatus'])->name('admin.booking.update');

    // --- Quản lý Khách hàng (CRM) - ĐÃ CHUYỂN VÀO ĐÂY ---
    Route::controller(CustomerController::class)
        ->prefix('customers')
        ->name('admin.customers.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
        });

    // --- Quản lý Đơn hàng ---
    Route::controller(OrderController::class)->prefix('orders')->name('admin.orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/status', 'updateStatus')->name('update_status');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // --- Quản lý Sản phẩm ---
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'indexAdmin')->name('product.index_admin');
        Route::get('/categories/{id}/products', 'adminShowByCategory')->name('admin.category.products');
        
        Route::get('/product/create/{category_id?}', 'create')->name('product.create');
        Route::post('/product', 'store')->name('product.store');
        Route::get('/product/{id}/edit', 'edit')->name('product.edit');
        Route::put('/product/{id}', 'update')->name('product.update');
        Route::delete('/product/{id}', 'destroy')->name('product.destroy');
    });

    // --- Quản lý Danh mục ---
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::post('/categories', 'store')->name('categories.store');
        Route::put('/categories/{id}', 'update')->name('categories.update');
        Route::delete('/categories/{id}', 'destroy')->name('categories.destroy');
        Route::get('/category/{id}', 'show')->name('category.show');
    });

    // --- Quản lý Tin Tức ---
    Route::controller(NewsController::class)->prefix('news')->name('news.')->group(function () {
        Route::get('/', 'indexAdmin')->name('index_admin');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // --- Quản lý Trang & Sections ---
    Route::resource('pages', PageController::class);
    Route::patch('/pages/{id}/toggle-menu', [PageController::class, 'toggleMenu'])->name('pages.toggle-menu');

    Route::controller(PageSectionController::class)->group(function () {
        Route::get('pages/{page}/sections', 'index')->name('page_sections.index');
        Route::post('pages/{page}/sections', 'store')->name('page_sections.store');
        Route::get('page-sections/{section}/edit', 'edit')->name('page_sections.edit');
        Route::put('page-sections/{section}', 'update')->name('page_sections.update');
        Route::delete('page-sections/{section}', 'destroy')->name('page_sections.destroy');
    });

    // --- Quản lý Thư viện ảnh ---
    Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });
});