<?php

use Illuminate\Support\Facades\Route;
// --- IMPORT CÁC CONTROLLER ---
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
use App\Http\Controllers\NewsController;

// ====================================================
// 1. KHU VỰC CÔNG KHAI (KHÁCH HÀNG)
// ====================================================

// --- Trang chủ & Sản phẩm ---
Route::get('/', [ProductController::class, 'index']);
Route::get('/danh-muc/{id}', [ProductController::class, 'showByCategory'])->name('frontend.category.show');
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.detail');

// --- Tin tức (News) ---
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.index');       // Danh sách tin
Route::get('/tin-tuc/{id}', [NewsController::class, 'detail'])->name('news.detail'); // Chi tiết tin

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
Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');

// --- CHATBOT AI ---
// Giới hạn 10 request trong 1 phút để tránh spam
Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
    ->middleware('throttle:10,1') 
    ->name('chatbot.ask');


// ====================================================
// 2. KHU VỰC QUẢN TRỊ (ADMIN) - YÊU CẦU ĐĂNG NHẬP
// ====================================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // --- Dashboard ---
    Route::get('/', [AdminController::class, 'index']); 
    Route::get('/booking/update/{id}/{status}', [AdminController::class, 'updateStatus']);

    // --- Quản lý Tin Tức (News) ---
    Route::controller(NewsController::class)->prefix('news')->name('news.')->group(function () {
        Route::get('/', 'indexAdmin')->name('index_admin'); // admin/news
        Route::get('/create', 'create')->name('create');    // admin/news/create
        Route::post('/store', 'store')->name('store');      // admin/news/store
        Route::get('/{id}/edit', 'edit')->name('edit');     // admin/news/{id}/edit
        Route::put('/{id}', 'update')->name('update');      // admin/news/{id}
        Route::delete('/{id}', 'destroy')->name('destroy'); // admin/news/{id}
    });

    // --- Quản lý Thư viện ảnh ---
    Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });

    // --- Quản lý Danh mục ---
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::post('/categories', 'store')->name('categories.store');
        Route::put('/categories/{id}', 'update')->name('categories.update');
        Route::delete('/categories/{id}', 'destroy')->name('categories.destroy');
        Route::get('/category/{id}', 'show')->name('category.show');
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

    // --- Quản lý Đơn hàng ---
    Route::controller(OrderController::class)->prefix('orders')->name('admin.orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/status', 'updateStatus')->name('update_status');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

});