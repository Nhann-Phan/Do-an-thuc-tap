<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Import Model Sản phẩm
use App\Models\ChatbotRule; // Import Model Rule (Nếu muốn dùng thêm)
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    // Đổi tên hàm thành 'send' để khớp với route và javascript đã sửa ở các bước trước
    // Nếu route của bạn đang gọi 'ask' thì đổi tên hàm này thành 'ask' nhé.
    public function send(Request $request)
    {
        $userMessage = $request->input('message');

        if (!$userMessage) {
            return response()->json(['reply' => 'Bạn chưa nhập nội dung tin nhắn.']);
        }

        // =================================================================
        // BƯỚC 1: TRA CỨU DỮ LIỆU THẬT TỪ DATABASE (RAG - Retrieval Augmented Generation)
        // =================================================================
        
        $contextData = ""; // Biến này dùng để mớm thông tin cho AI

        // 1.1 Tìm xem khách có nhắc đến tên sản phẩm nào không
        // (Dùng where like để tìm gần đúng, tối ưu hơn foreach all)
        $products = Product::where('is_active', 1)->get();
        $foundProducts = [];

        foreach ($products as $product) {
            if (str_contains(strtolower($userMessage), strtolower($product->name))) {
                $price = number_format($product->price);
                $foundProducts[] = "Sản phẩm: {$product->name} (Giá: {$price} VNĐ)";
            }
        }

        if (count($foundProducts) > 0) {
            // Nếu tìm thấy sản phẩm, đưa thông tin này cho AI biết
            $listStr = implode(", ", $foundProducts);
            $contextData .= "THÔNG TIN TỪ KHO HÀNG GPM: Hiện tại shop đang có các sản phẩm khớp với câu hỏi: [ {$listStr} ]. Hãy dùng thông tin giá này để báo cho khách.";
        } else {
            $contextData .= "THÔNG TIN TỪ KHO HÀNG: Hiện tại không tìm thấy tên sản phẩm cụ thể nào trong câu hỏi này.";
        }

        // 1.2 Tìm trong bảng ChatbotRule (Các câu hỏi thường gặp: địa chỉ, sđt...)
        $rules = ChatbotRule::all();
        foreach ($rules as $rule) {
            if (str_contains(strtolower($userMessage), strtolower($rule->keyword))) {
                $contextData .= " THÔNG TIN BỔ SUNG: {$rule->response}";
            }
        }

        // =================================================================
        // BƯỚC 2: CẤU HÌNH "NHÂN CÁCH" AI & GỬI DỮ LIỆU
        // =================================================================

        $systemPrompt = "
        Bạn là Trợ lý ảo AI của Công ty GPM Technology (Chuyên Camera, Mạng, Laptop, Phần mềm).
        
        NHIỆM VỤ CỦA BẠN:
        1. Trả lời câu hỏi của khách hàng dựa trên 'THÔNG TIN TỪ KHO HÀNG' mà tôi cung cấp bên dưới.
        2. Nếu có thông tin sản phẩm và giá, hãy báo giá chính xác, đừng bịa đặt giá.
        3. Nếu không có thông tin sản phẩm trong ngữ cảnh, hãy tư vấn chung chung và mời khách gọi hotline.
        
        PHONG CÁCH:
        - Thân thiện, ngắn gọn, dùng emoji 😊.
        - Xưng hô: Em - Anh/Chị.
        - Hotline công ty: 0902 777 186.
        - Địa chỉ: 38 đường số 9, KĐT Tây Sông Hậu, Long Xuyên, An Giang.

        DỮ LIỆU CUNG CẤP CHO BẠN (CONTEXT):
        {$contextData}
        ";

        // =================================================================
        // BƯỚC 3: GỌI GOOGLE GEMINI API
        // =================================================================
        
        $apiKey = env('GOOGLE_GEMINI_KEY');
        // Nếu quên set key trong .env thì dùng tạm string rỗng để tránh lỗi code, nhưng sẽ không chạy được AI
        if(!$apiKey) {
            return response()->json(['reply' => 'Lỗi: Chưa cấu hình API Key trong file .env']);
        }

        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, [
                    "contents" => [
                        [
                            "parts" => [
                                // Gửi cả lời nhắc hệ thống + câu hỏi của khách
                                ["text" => $systemPrompt . "\n\nKhách hàng hỏi: " . $userMessage]
                            ]
                        ]
                    ],
                    "generationConfig" => [
                        "temperature" => 0.7, 
                        "maxOutputTokens" => 500,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Em đang kiểm tra kho, anh chị chờ xíu nhé...';
                
                // Trả về JSON chuẩn cho Frontend
                return response()->json([
                    'status' => 'success', // Giữ lại field này cho tương thích code cũ nếu cần
                    'reply' => nl2br($aiReply)
                ]);
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return response()->json([
                    'status' => 'error',
                    'reply' => 'Hệ thống AI đang bảo trì. Anh chị vui lòng gọi Hotline 0902 777 186 nhé!'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'reply' => 'Có lỗi kết nối mạng. Bạn kiểm tra lại giúp em nha!'
            ]);
        }
    }
}