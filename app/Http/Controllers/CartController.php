<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ========================================================
    // 🔥 LÕI ĐỒNG BỘ: ÉP DATABASE TRỞ LẠI SESSION (CHO GIAO DIỆN HIỂU)
    // ========================================================
    private function syncDbToSession()
    {
        if (Auth::check()) {
            $dbCarts = Cart::with(['product', 'variant'])->where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($dbCarts as $item) {
                if (!$item->product) continue;

                $price = $item->variant ? $item->variant->price : ($item->product->sale_price ?? $item->product->price);
                $name = $item->variant ? $item->product->name . ' (' . $item->variant->name . ')' : $item->product->name;
                $cartKey = $item->product_id . '_' . ($item->variant_id ?? 'default');

                $cart[$cartKey] = [
                    "product_id" => $item->product_id,
                    "variant_id" => $item->variant_id,
                    "name" => $name,
                    "quantity" => $item->quantity,
                    "price" => $price,
                    "image" => $item->product->image
                ];
            }
            // Ép ngược mảng DB vào lại Session để Header và Giao diện hiện số lượng
            session()->put('cart', $cart); 
            return $cart;
        }
        return session()->get('cart', []);
    }

    // 1. Xem giỏ hàng
    public function index()
    {
        $cart = $this->syncDbToSession(); // Gọi hàm đồng bộ
        return view('clients.cart.cart', compact('cart'));
    }

    // 2. Thêm vào giỏ
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->quantity ?? 1;
        $variantId = $request->variant_id;

        if (Auth::check()) {
            // ĐÃ ĐĂNG NHẬP: Lưu Database
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $id)
                            ->where('variant_id', $variantId)
                            ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'variant_id' => $variantId,
                    'quantity' => $quantity
                ]);
            }
            $this->syncDbToSession(); // 🔥 Đồng bộ lại Session ngay lập tức
        } else {
            // CHƯA ĐĂNG NHẬP: Lưu Session
            $cart = session()->get('cart', []);
            $price = $product->sale_price ?? $product->price;
            $name = $product->name;
            $image = $product->image;

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $price = $variant->price;
                    $name = $product->name . ' (' . $variant->name . ')';
                }
            }

            $cartKey = $id . '_' . ($variantId ?? 'default');

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
        }
        
        return redirect()->back()->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // 3. Cập nhật giỏ hàng
    public function update(Request $request)
    {
        if($request->id && $request->quantity) {
            if (Auth::check()) {
                $parts = explode('_', $request->id);
                if(count($parts) >= 2) {
                    Cart::where('user_id', Auth::id())
                        ->where('product_id', $parts[0])
                        ->where('variant_id', $parts[1] === 'default' ? null : $parts[1])
                        ->update(['quantity' => $request->quantity]);
                }
                $this->syncDbToSession(); // 🔥 Đồng bộ
            } else {
                $cart = session()->get('cart');
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
            }
            session()->flash('cart_success', 'Giỏ hàng đã được cập nhật');
        }
    }

    // 4. Xóa sản phẩm khỏi giỏ
    public function remove(Request $request)
    {
        if($request->id) {
            if (Auth::check()) {
                $parts = explode('_', $request->id);
                if(count($parts) >= 2) {
                    Cart::where('user_id', Auth::id())
                        ->where('product_id', $parts[0])
                        ->where('variant_id', $parts[1] === 'default' ? null : $parts[1])
                        ->delete();
                }
                $this->syncDbToSession(); // 🔥 Đồng bộ
            } else {
                $cart = session()->get('cart');
                if(isset($cart[$request->id])) {
                    unset($cart[$request->id]);
                    session()->put('cart', $cart);
                }
            }
            session()->flash('cart_success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        }
    }

    // 5. MUA NGAY
    public function buyNow(Request $request, $id)
    {
        $this->addToCart($request, $id);
        return redirect()->route('checkout.index'); 
    }
}