<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant; // <--- 1. QUAN TRỌNG: Import Model Biến thể
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // 1. Xem giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('clients.cart.cart', compact('cart'));
    }

    // =========================================================
    // 2. THÊM VÀO GIỎ (ĐÃ CẬP NHẬT LOGIC BIẾN THỂ)
    // =========================================================
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        
        // Mặc định lấy số lượng là 1 nếu không truyền
        $quantity = $request->quantity ?? 1;

        // A. Thiết lập thông tin mặc định (Gốc)
        $price = $product->sale_price ?? $product->price;
        $name = $product->name;
        $image = $product->image;
        $variantId = $request->variant_id; // Lấy ID biến thể từ URL

        // B. Kiểm tra biến thể để ghi đè giá và tên
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $price = $variant->price; // Lấy giá biến thể
                $name = $product->name . ' (' . $variant->name . ')'; // VD: Youtube (1 năm)
            }
        }

        // C. Tạo ID giỏ hàng duy nhất (Product_ID + Variant_ID)
        // Ví dụ: Sản phẩm ID 10, Variant ID 5 -> Key là "10_5"
        // Nếu không có variant -> Key là "10_default"
        $cartKey = $id . '_' . ($variantId ?? 'default');

        // D. Thêm vào session
        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id, // Lưu thêm ID gốc để tiện truy vấn sau này
                "variant_id" => $variantId,
                "name" => $name,
                "quantity" => $quantity,
                "price" => $price,
                "image" => $image
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // 3. Cập nhật giỏ hàng (khi sửa số lượng)
    public function update(Request $request)
    {
        // Lưu ý: $request->id lúc này là Cart Key (VD: "10_5" hoặc "10_default")
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
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
            
            session()->flash('cart_success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        }
    }

    // =========================================================
    // 5. MUA NGAY (ĐÃ CẬP NHẬT LOGIC BIẾN THỂ)
    // =========================================================
    public function buyNow(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->quantity ?? 1;

        // A. Thiết lập thông tin mặc định
        $price = $product->sale_price ?? $product->price;
        $name = $product->name;
        $image = $product->image;
        $variantId = $request->variant_id;

        // B. Kiểm tra biến thể
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $price = $variant->price;
                $name = $product->name . ' (' . $variant->name . ')';
            }
        }

        // C. Tạo ID giỏ hàng
        $cartKey = $id . '_' . ($variantId ?? 'default');

        // D. Thêm vào session
        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "variant_id" => $variantId,
                "name" => $name,
                "quantity" => $quantity,
                "price" => $price,
                "image" => $image
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('checkout.index'); 
    }
}