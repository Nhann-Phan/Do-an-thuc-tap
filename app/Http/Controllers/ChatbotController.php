<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // <--- THÊM DÒNG NÀY ĐỂ SỬA LỖI
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'message' => 'required|string|max:500', 
        ]);

        $userMessage = $request->input('message');

        // =================================================================
        // 1. BẢO MẬT KEY: CHỈ LẤY TỪ .ENV
        // =================================================================
        
        $keysString = env('GOOGLE_GEMINI_KEYS', ''); 
        
        $allKeys = explode(',', $keysString);
        $allKeys = array_map('trim', $allKeys);
        $allKeys = array_filter($allKeys);

        if (empty($allKeys)) {
            // Log lỗi vào hệ thống (storage/logs/laravel.log) để Admin kiểm tra
            Log::error('Chatbot Error: Chưa cấu hình GOOGLE_GEMINI_KEYS trong file .env');
            return response()->json(['reply' => 'Hệ thống đang bảo trì tính năng chat. Vui lòng quay lại sau!']);
        }

        // Trộn ngẫu nhiên danh sách Key
        shuffle($allKeys);

        // =================================================================
        // 2. CHUẨN BỊ DỮ LIỆU
        // =================================================================
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timeInfo = Carbon::now()->format('d/m/Y H:i');
        
        $companyInfo = "Tên: GPM Technology. Đ/c: 38 đường số 9, KĐT Tây Sông Hậu, Long Xuyên. Hotline: 0902 777 186.";

        $contextProduct = "";
        try {
            // Lấy 30 sản phẩm mới nhất
            $products = Product::where('is_active', 1)->latest()->limit(30)->get();
            if ($products->count() > 0) {
                $contextProduct .= "DANH SÁCH SẢN PHẨM HIỆN CÓ:\n";
                foreach ($products as $p) {
                    $contextProduct .= "- {$p->name} (Giá: " . number_format($p->price) . " VNĐ)\n";
                }
            }
        } catch (\Exception $e) { 
            Log::error('Chatbot DB Error: ' . $e->getMessage());
        }

        // =================================================================
        // 3. GỌI GEMINI (CƠ CHẾ FAILOVER)
        // =================================================================
        $modelName = 'gemini-2.5-flash'; 
        $finalReply = "";
        $isSuccess = false;

        $prompt = "
        VAI TRÒ: Bạn là 'Trợ lý ảo GPM' - nhân viên tư vấn nhiệt tình, lễ phép của GPM Technology.
        
        DỮ LIỆU:
        - Thời gian: {$timeInfo}
        - Công ty: {$companyInfo}
        - Sản phẩm: 
        {$contextProduct}
        
        YÊU CẦU:
        - TONE GIỌNG: Lễ phép (Dạ, Vâng, ạ), dùng emoji vui vẻ (😊, ❤️).
        - CÓ SẢN PHẨM: Báo giá và mời mua.
        - KHÔNG CÓ: Xin lỗi khéo và mời gọi hotline.
        - NGẮN GỌN: Trả lời súc tích.
        
        KHÁCH HỎI: '{$userMessage}'
        TRẢ LỜI:
        ";

        foreach ($allKeys as $apiKey) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

            try {
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(10)
                    ->post($apiUrl, [
                        "contents" => [[ "parts" => [[ "text" => $prompt ]] ]]
                    ]);

                if ($response->successful()) {
                    $finalReply = $response['candidates'][0]['content']['parts'][0]['text'] ?? 'Em đang kiểm tra...';
                    $isSuccess = true;
                    break; // Thành công -> Thoát
                }

            } catch (\Exception $e) {
                // Lỗi mạng -> Thử key khác
                continue;
            }
        }

        // =================================================================
        // 4. TRẢ KẾT QUẢ
        // =================================================================
        if ($isSuccess) {
            return response()->json([
                'reply' => nl2br($finalReply),
                'suggestions' => ['📷 Giá Camera', '💻 Laptop văn phòng', '📞 Gọi Hotline']
            ]);
        } else {
            return response()->json([
                'reply' => "Hệ thống đang quá tải xíu. Anh/chị đợi 1 phút rồi hỏi lại em nha! 😭",
                'suggestions' => ['Thử lại ngay']
            ]);
        }
    }
}