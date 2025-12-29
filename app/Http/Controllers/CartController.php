<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // 1. Xem giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('clients.cart', compact('cart'));
    }

    // 2. Thêm sản phẩm vào giỏ
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Nếu sản phẩm đã có trong giỏ -> Tăng số lượng
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Nếu chưa có -> Thêm mới
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->sale_price ?? $product->price, // Ưu tiên giá sale
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        
        // Đã đổi 'success' thành 'cart_success' để tránh hiện Popup to
        return redirect()->back()->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // 3. Cập nhật giỏ hàng (khi sửa số lượng)
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
            // Thông báo dạng tĩnh (không Popup)
            session()->flash('cart_success', 'Giỏ hàng đã được cập nhật');
        }
    }

    // 4. Xóa sản phẩm khỏi giỏ
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            
            // Thông báo dạng tĩnh (không Popup)
            session()->flash('cart_success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        }
    }
    // --- HÀM MỚI: MUA NGAY (Thêm vào giỏ -> Chuyển đến Thanh toán) ---
    public function buyNow($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Logic thêm vào giỏ
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->sale_price ?? $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        // Chuyển hướng thẳng đến trang Thanh toán
        return redirect()->route('checkout.index'); 
    }
}