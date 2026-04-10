<?php

use Illuminate\Support\Facades\Route;

// Core & Frontend Controllers
use App\Http\Controllers\{
    HomeController,
    ProductController,
    CategoryController,
    NewsController,
    GalleryController,
    CartController,
    CheckoutController,
    CompareController,
    ChatbotController,
    AuthController,
    ForgotPasswordController,
    ProfileController
};

// Admin Controllers
use App\Http\Controllers\Admin\{
    PageController,
    PageSectionController,
    CustomerController
};

// General Admin/Account Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gioi-thieu/{slug}', [HomeController::class, 'showPage'])->name('client.page.detail');

Route::controller(ProductController::class)->group(function() {
    Route::get('/san-pham', 'index')->name('product.index'); 
    Route::get('/danh-muc/{id}', 'showByCategory')->name('frontend.category.show');
    Route::get('/san-pham/{id}', 'show')->name('product.detail');
});

Route::controller(CompareController::class)->prefix('compare')->name('compare.')->group(function() {
    Route::get('/', 'index')->name('index'); 
    Route::post('/add', 'add')->name('add'); 
    Route::post('/remove', 'remove')->name('remove'); 
    Route::post('/clear', 'clear')->name('clear'); 
});

Route::controller(NewsController::class)->prefix('tin-tuc')->name('client.news.')->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'detail')->name('detail');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/gio-hang', 'index')->name('cart.index');
    Route::get('/add-to-cart/{id}', 'addToCart')->name('add_to_cart');
    Route::patch('/update-cart', 'update')->name('update_cart');
    Route::delete('/remove-from-cart', 'remove')->name('remove_from_cart');
    Route::get('/buy-now/{id}', 'buyNow')->name('buy_now');
});

Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->middleware('throttle:10,1')->name('chatbot.ask');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION & PASSWORD RESET
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login'); 
    Route::post('/login', 'login'); 
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::get('/logout', 'logout')->name('logout'); 
});

Route::controller(ForgotPasswordController::class)->group(function() {
    Route::get('forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('forgot-password', 'sendResetCodeEmail')->name('password.email');
    Route::post('reset-password', 'resetPassword')->name('password.update');
});

Route::controller(ProductController::class)->group(function() {
    Route::get('/san-pham', 'index')->name('product.index'); 
    Route::get('/tim-kiem', 'search')->name('product.search'); // THÊM DÒNG NÀY
    Route::get('/ajax/search', 'searchAjax'); // <--- THÊM DÒNG NÀY ĐỂ XỬ LÝ LIVE SEARCH
    Route::get('/danh-muc/{id}', 'showByCategory')->name('frontend.category.show');
    Route::get('/san-pham/{id}', 'show')->name('product.detail');
});

/*
|--------------------------------------------------------------------------
| SECURE ROUTES (REQUIRED AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // --- Customer Profile ---
    Route::controller(ProfileController::class)->prefix('tai-khoan')->name('client.profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/cap-nhat-thong-tin', 'updateInfo')->name('update_info');
        Route::post('/doi-mat-khau', 'updatePassword')->name('update_password');
        Route::get('/don-hang', 'orders')->name('orders');
        Route::get('/dat-lich', 'bookings')->name('bookings');
        Route::get('/don-hang/{id}', 'showOrder')->name('orders.detail');
    });

    // --- Checkout & Booking ---
    Route::post('/book-appointment', [BookingController::class, 'store'])->middleware('throttle:3,1')->name('booking.store');
    
    Route::controller(CheckoutController::class)->prefix('thanh-toan')->name('checkout.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'process')->name('process');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard'); 
        Route::get('/booking/update/{id}/{status}', [AdminController::class, 'updateStatus'])->name('admin.booking.update');

        Route::controller(AccountController::class)->group(function() {
            Route::get('/profile/password', 'changePasswordForm')->name('admin.profile.password');
            Route::post('/profile/password', 'updatePassword')->name('admin.profile.update_password');
            
            Route::prefix('accounts')->name('admin.accounts.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::get('/destroy/{id}', 'destroy')->name('destroy');
            });
        });

        Route::controller(CustomerController::class)->prefix('customers')->name('admin.customers.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
        });

        Route::controller(OrderController::class)->prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}/status', 'updateStatus')->name('update_status');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'indexAdmin')->name('product.index_admin');
            Route::get('/categories/{id}/products', 'adminShowByCategory')->name('admin.category.products');
            Route::get('/product/create/{category_id?}', 'create')->name('product.create');
            Route::post('/product', 'store')->name('product.store');
            Route::get('/product/{id}/edit', 'edit')->name('product.edit');
            Route::put('/product/{id}', 'update')->name('product.update');
            Route::delete('/product/{id}', 'destroy')->name('product.destroy');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('categories.index');
            Route::post('/categories', 'store')->name('categories.store');
            Route::put('/categories/{id}', 'update')->name('categories.update');
            Route::delete('/categories/{id}', 'destroy')->name('categories.destroy');
            Route::get('/category/{id}', 'show')->name('category.show');
        });

        Route::controller(NewsController::class)->prefix('news')->name('news.')->group(function () {
            Route::get('/', 'indexAdmin')->name('index_admin');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::resource('pages', PageController::class);
        Route::patch('/pages/{id}/toggle-menu', [PageController::class, 'toggleMenu'])->name('pages.toggle-menu');

        Route::controller(PageSectionController::class)->group(function () {
            Route::get('pages/{page}/sections', 'index')->name('page_sections.index');
            Route::post('pages/{page}/sections', 'store')->name('page_sections.store');
            Route::get('page-sections/{section}/edit', 'edit')->name('page_sections.edit');
            Route::put('page-sections/{section}', 'update')->name('page_sections.update');
            Route::delete('page-sections/{section}', 'destroy')->name('page_sections.destroy');
        });

        Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::get('/delete/{id}', 'destroy')->name('delete');
        });
    });
});