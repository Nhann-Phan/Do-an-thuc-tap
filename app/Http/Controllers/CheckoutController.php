<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    private function getCartData()
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
            session()->put('cart', $cart); 
            return $cart;
        }
        return session()->get('cart', []);
    }

    public function index()
    {
        $cart = $this->getCartData();

        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống!');
        }
        return view('clients.cart.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required'
        ]);

        $cart = $this->getCartData();

        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng rỗng!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // 1:LƯU/CẬP NHẬT THÔNG TIN VÀO BẢNG CUSTOMERS
        $customer = Customer::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'name' => $request->name,
                'phone_number' => $request->phone,
                'email' => $request->email ?? Auth::user()->email,
                'address' => $request->address,
                'notes' => 'Khách mua hàng qua Web'
            ]
        );
        DB::beginTransaction();

        try {
            // 2: TẠO ĐƠN HÀNG VỚI CUSTOMER_ID CHUẨN
            $order = Order::create([
                'customer_id' => $customer->id, // Dùng ID của bảng customers
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email ?? Auth::user()->email,
                'address' => $request->address,
                'note' => $request->note,
                'total_money' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            foreach ($cart as $id => $details) {
                if (strpos($id, '_') !== false) {
                    [$productId, $variantId] = explode('_', $id);
                    $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->first();

                    if (!$variant) throw new \Exception("Phiên bản '{$details['name']}' không còn tồn tại.");
                    if ($variant->quantity < $details['quantity']) throw new \Exception("Sản phẩm '{$details['name']}' chỉ còn {$variant->quantity} cái.");

                    $variant->decrement('quantity', $details['quantity']);
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'product_name' => $details['name'],
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                    ]);
                } 
                else {
                    $product = Product::where('id', $id)->lockForUpdate()->first();

                    if (!$product) throw new \Exception("Sản phẩm '{$details['name']}' không tồn tại.");
                    if ($product->quantity < $details['quantity']) throw new \Exception("Sản phẩm '{$details['name']}' chỉ còn {$product->quantity} cái.");

                    $product->decrement('quantity', $details['quantity']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'product_name' => $details['name'],
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                    ]);
                }
            }

            DB::commit();

            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            }
            session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}