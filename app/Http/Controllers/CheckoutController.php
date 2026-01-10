<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant; // <--- 1. Cần thêm cái này
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống!');
        }
        return view('clients.cart.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required'
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng rỗng!');
        }

        // 2. Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();

        try {
            // 3. XỬ LÝ KHÁCH HÀNG (Tự động định danh)
            $customer = Customer::firstOrCreate(
                ['phone_number' => $request->phone],
                [
                    'name' => $request->name,
                    'address' => $request->address,
                    'email' => $request->email,
                    'notes' => 'Khách mua hàng qua Website'
                ]
            );

            // 4. TẠO ĐƠN HÀNG
            $order = Order::create([
                'customer_id' => $customer->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'note' => $request->note,
                'total_money' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // 5. XỬ LÝ CHI TIẾT & TRỪ KHO (LOGIC MỚI)
            foreach ($cart as $id => $details) {
                
                // $id trong giỏ hàng có thể là "15" (sp thường) hoặc "15_29" (sp có biến thể)
                
                // TRƯỜNG HỢP A: SẢN PHẨM CÓ BIẾN THỂ (Có dấu gạch dưới _)
                if (strpos($id, '_') !== false) {
                    [$productId, $variantId] = explode('_', $id);
                    
                    // Tìm biến thể và khóa dòng dữ liệu (lockForUpdate)
                    $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->first();

                    if (!$variant) {
                        throw new \Exception("Phiên bản '{$details['name']}' không còn tồn tại.");
                    }

                    // Check số lượng biến thể
                    if ($variant->quantity < $details['quantity']) {
                        throw new \Exception("Sản phẩm '{$details['name']}' chỉ còn {$variant->quantity} cái, không đủ để bán.");
                    }

                    // Trừ kho biến thể
                    $variant->decrement('quantity', $details['quantity']);
                    
                    // Lưu vào order_items
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId, // Vẫn lưu ID sản phẩm cha
                        'product_name' => $details['name'] . ' (' . $variant->name . ')', // Lưu tên kèm tên biến thể
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                    ]);
                } 
                // TRƯỜNG HỢP B: SẢN PHẨM THƯỜNG (Không có biến thể)
                else {
                    $product = Product::where('id', $id)->lockForUpdate()->first();

                    if (!$product) {
                        throw new \Exception("Sản phẩm '{$details['name']}' không tồn tại.");
                    }

                    // Nếu sản phẩm thường mà bạn không quản lý số lượng (ví dụ dịch vụ), 
                    // thì bỏ qua đoạn if dưới này. Còn nếu có quản lý thì giữ nguyên.
                    if ($product->quantity < $details['quantity']) {
                        throw new \Exception("Sản phẩm '{$details['name']}' chỉ còn {$product->quantity} cái.");
                    }

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
            session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}