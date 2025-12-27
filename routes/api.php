<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Đường dẫn cho Chatbot (Phương thức POST vì gửi dữ liệu lên)
Route::post('/bot-ask', [ChatbotController::class, 'ask']);

// 2. Đường dẫn xem danh sách sản phẩm (Phương thức GET)
// (Tạm thời dùng hàm mặc định index của ProductController nếu bạn đã tạo ở Bước 2)
Route::get('/products', function() {
    return \App\Models\Product::all();
});

// 3. Đường dẫn đặt lịch sửa chữa (Phương thức POST)
Route::post('/book-appointment', [BookingController::class, 'store']);