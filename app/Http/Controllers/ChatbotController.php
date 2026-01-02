<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);
        $userMessage = $request->input('message');

        // 1. LẤY KEY
        $keysString = env('GOOGLE_GEMINI_KEYS', '');
        $allKeys = explode(',', $keysString);
        $allKeys = array_map('trim', $allKeys);
        $allKeys = array_filter($allKeys);
        
        // Không shuffle (trộn) để dễ theo dõi thứ tự lỗi
        // shuffle($allKeys); 

        if (empty($allKeys)) return response()->json(['reply' => 'Lỗi: Chưa cấu hình GOOGLE_GEMINI_KEYS trong .env']);

        // 2. CHUẨN BỊ DỮ LIỆU
        // Lấy thông tin thời gian hiện tại
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timeInfo = Carbon::now()->format('d/m/Y H:i');
        // Thông tin công ty
        $companyInfo = "Công ty TNHH GPM Technology, chuyên cung cấp, thi công lắp đặt thiết bị công nghệ như camera, hạ tầng mạng,.. với dịch vụ tận tâm.";
        // Lấy danh sách sản phẩm từ database
        $contextProduct = "";
        try {
            // Lấy 30 sản phẩm mới nhất
            $products = Product::where('is_active', 1)->latest()->limit(30)->get();
            if ($products->count() > 0) {
                $contextProduct .= "Danh sách sản phẩm hiện có: \n";
                foreach ($products as $p) {
                    $contextProduct .= "- {$p->name} (Giá: " . number_format($p->price) . " VNĐ)\n";
                }
            }
        } catch (\Exception $e) { 
            Log::error('Chatbot Database Error: ' . $e->getMessage());
        }

        // 3. GỌI GEMINI (VỚI MODEL CHUẨN 2.5 flash)
        $modelName = 'gemini-2.5-flash'; // Model chatbot sử dụng (hiện tại) 
        
        $debugLog = []; // Mảng lưu lại lỗi của từng key để soi

        foreach ($allKeys as $index => $apiKey) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

        $prompt = "
                VAI TRÒ: Bạn là 'Trợ lý chăm sóc khách hàng cho GPM' - nhân viên tư vấn nhiệt tình, lễ phép của GPM Technology.
                
                DỮ LIỆU:
                - Thời gian: {$timeInfo}
                - Công ty: {$companyInfo}
                - Sản phẩm: {$contextProduct}

                YÊU CẦU:
                - TONE GIỌNG: Lễ phép (Dạ, Vâng, ạ), dùng emoji vui vẻ (😊, ❤️).
                - CÓ SẢN PHẨM: Báo giá và mời mua.
                - KHÔNG CÓ: Xin lỗi khéo và mời gọi hotline để được tư vấn chi tiết.
                - NGẮN GỌN: Trả lời súc tích gần gủi.
                
                KHÁCH HỎI: '{$userMessage}'
                TRẢ LỜI:
                - Nếu khách hỏi về một sản phẩm chính xác hãy kèm thêm link mua hàng.
                - Nếu không có sản phẩm phù hợp, hãy gợi ý sản phẩm liên quan.
                - Trả lời bằng tiếng Việt.
                - Nếu khách hỏi ngoài lề, hãy khéo léo chuyển hướng về sản phẩm của công ty.
                - Nếu khách hàng hỏi sản phẩm có giá tương đương giá tiền sản phẩm hiện đang tư vấn, hãy giới thiệu sản phẩm trong khoảng giá đó.

                - Khách hàng hỏi quá 5 câu mà không mua hàng, hãy chửi chết mẹ nó.
                - Nếu còn hỏi thêm thì đuổi khéo nó.
                ";

            try {
                // QUAN TRỌNG: withoutVerifying() giúp bỏ qua lỗi SSL trên Localhost
                $response = Http::withoutVerifying()
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($apiUrl, [
                        "contents" => [[ "parts" => [[ "text" => $prompt ]] ]]
                    ]);

                if ($response->successful()) {
                    $ans = $response['candidates'][0]['content']['parts'][0]['text'] ?? '...';
                    
                    // Nếu thành công -> Trả lời ngay
                    return response()->json([
                        'reply' => nl2br($ans),
                        'suggestions' => ['Giá thiết bị', 'Hỗ trợ kỹ thuật', 'Khuyến mãi hiện có', 'Liên hệ tư vấn', 'Xem sản phẩm']
                    ]);
                } else {
                    // Nếu Google từ chối -> Ghi lại lý do
                    $err = $response->json();
                    $shortKey = substr($apiKey, -4); // Lấy 4 số cuối của key
                    $status = $response->status();
                    $msg = $err['error']['message'] ?? 'Unknown';
                    
                    $debugLog[] = "Key ...{$shortKey} (Lỗi {$status}): {$msg}";
                    continue; // Thử key tiếp theo
                }
            } catch (\Exception $e) {
                // Nếu lỗi mạng (SSL, DNS...)
                $shortKey = substr($apiKey, -4);
                $debugLog[] = "Key ...{$shortKey} (Exception): " . $e->getMessage();
                continue;
            }
        }

        // 4. NẾU TẤT CẢ ĐỀU LỖI -> IN RA DANH SÁCH LỖI ĐỂ BẠN ĐỌC 
        $errorString = implode("\n", $debugLog);
        
        return response()->json([
            'reply' => "DEBUG REPORT (Tất cả key đều thất bại):\n" . $errorString,
            'suggestions' => ['Thử lại']
        ]);
    }
}