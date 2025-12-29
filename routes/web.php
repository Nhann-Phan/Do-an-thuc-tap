<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

// ====================================================
// 1. KHU VỰC CÔNG KHAI (Khách hàng)
// ====================================================

// --- Trang chủ & Sản phẩm ---
Route::get('/', [ProductController::class, 'index']);
Route::get('/danh-muc/{id}', [ProductController::class, 'showByCategory'])->name('frontend.category.show');
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('product.detail'); // Đã chuyển ra ngoài để khách xem được

// --- Đặt lịch hẹn ---
Route::post('/book-appointment', [BookingController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('booking.store');

// --- Xác thực (Login/Logout) ---
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login'); 
    Route::post('/login', 'login'); 
    Route::get('/logout', 'logout'); 
});


// ====================================================
// 2. KHU VỰC QUẢN TRỊ (ADMIN) - Yêu cầu đăng nhập
// ====================================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // --- Dashboard & Booking ---
    Route::get('/', [AdminController::class, 'index']); // /admin
    Route::get('/booking/update/{id}/{status}', [AdminController::class, 'updateStatus']);

    // --- Quản lý Thư viện ảnh (Gallery) ---
    Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('delete');
    });

    // --- Quản lý Danh mục (Categories) ---
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::post('/categories', 'store')->name('categories.store');
        Route::put('/categories/{id}', 'update')->name('categories.update');
        Route::delete('/categories/{id}', 'destroy')->name('categories.destroy');
        
        // Xem chi tiết danh mục trong Admin (để list sản phẩm)
        Route::get('/category/{id}', 'show')->name('category.show');
    });

    // --- Quản lý Sản phẩm (Products) ---
    Route::controller(ProductController::class)->group(function () {
        // Danh sách
        Route::get('/products', 'indexAdmin')->name('product.index_admin');
        Route::get('/categories/{id}/products', 'adminShowByCategory')->name('admin.category.products');

        // Thêm, Sửa, Xóa
        Route::get('/product/create/{category_id}', 'create')->name('product.create');
        Route::post('/product', 'store')->name('product.store');
        Route::get('/product/{id}/edit', 'edit')->name('product.edit');
        Route::put('/product/{id}', 'update')->name('product.update');
        Route::delete('/product/{id}', 'destroy')->name('product.destroy');
    });

});

// Route Giỏ hàng
Route::get('gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::get('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add_to_cart');
Route::patch('update-cart', [CartController::class, 'update'])->name('update_cart');
Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('remove_from_cart');

// --- ROUTE QUẢN LÝ ĐƠN HÀNG ---
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

// --- ROUTE THANH TOÁN (Dán vào cuối file) ---
Route::get('thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('thanh-toan', [CheckoutController::class, 'placeOrder'])->name('checkout.process');

// --- Route Thêm vào giỏ (Cũ) ---
Route::get('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add_to_cart');

// --- THÊM DÒNG NÀY: Route Mua ngay ---
Route::get('buy-now/{id}', [CartController::class, 'buyNow'])->name('buy_now');

