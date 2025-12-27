<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotRule;
use App\Models\Product;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        // 1. Lấy tin nhắn và chuyển về chữ thường
        $userMessage = strtolower($request->input('message'));

        // --- CÁCH TÌM KIẾM MỚI (THÔNG MINH HƠN) ---

        // 2. Tìm trong bảng Sản phẩm trước
        // Lấy tất cả sản phẩm ra để so sánh
        $products = Product::all();
        
        foreach ($products as $product) {
            // Kiểm tra: Nếu tên sản phẩm (vd: "dell xps 13") CÓ NẰM TRONG câu nói của khách
            if (str_contains($userMessage, strtolower($product->name))) {
                $price = number_format($product->price);
                return response()->json([
                    'status' => 'success',
                    'reply' => "Sản phẩm {$product->name} hiện đang có giá {$price} VNĐ. Máy đang sẵn hàng tại shop ạ!"
                ]);
            }
        }

        // 3. Tìm trong bảng Luật trả lời (Rules)
        $rules = ChatbotRule::all();
        
        foreach ($rules as $rule) {
            // Kiểm tra: Nếu từ khóa (vd: "địa chỉ") CÓ NẰM TRONG câu nói của khách
            if (str_contains($userMessage, strtolower($rule->keyword))) {
                return response()->json([
                    'status' => 'success',
                    'reply' => $rule->response
                ]);
            }
        }

        // 4. Nếu vẫn không hiểu thì trả về mặc định
        return response()->json([
            'status' => 'fail',
            'reply' => 'Dạ em chưa hiểu rõ lắm. Anh/chị có thể hỏi ngắn gọn tên sản phẩm (ví dụ: "Dell", "Macbook") hoặc hỏi "địa chỉ", "sđt" được không ạ?'
        ]);
    }
}