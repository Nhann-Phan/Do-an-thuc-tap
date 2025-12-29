<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // 1. Kiểm tra giỏ hàng
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống!');
        }

        // 2. Không cần lấy menuCategories nữa (để giao diện thoáng, tập trung thanh toán)
        return view('clients.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        // ... (Giữ nguyên logic xử lý của bạn) ...
        // Demo:
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công!');
    }
}