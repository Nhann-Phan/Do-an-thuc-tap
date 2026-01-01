<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $userMessage = $request->input('message');
        if (!$userMessage) return response()->json(['reply' => 'Bạn chưa nhập tin nhắn.']);

        // =================================================================
        // 1. CHUẨN BỊ DANH SÁCH KEY (Đã bao gồm danh sách key của bạn)
        // =================================================================
        
        // Danh sách key bạn đã cung cấp
        $defaultKeys = 'AIzaSyCAChxmi7_t-j2UbOAv5F3cfhtD5BIJ0Bs,AIzaSyAHuABDmWsMtKBKQ7edpV_OjSW9QxgucuU,AIzaSyCxbJkrecho_Qa4kxLjHMeK4_8FZCMyvZo,AIzaSyC0FgPW-u5w3WbKx7QQnNqsOs4VmNqL6U4,AIzaSyANdGtKpzAeI0kWoCf4G7hSGR4E05GbeAw,AIzaSyBI_4DPXy8Rhfu657V7Zj4TduZMpy9ONKw,AIzaSyAKyuSuFawxgoQUEnJ1Fa_Qp41HnHV4aGQ,AIzaSyB8ORhDaYcNrVVQSxO6mwoESjSaI0N6JuA,AIzaSyAw0VhLgt_AOGWcq691frhtlQIn3CfxLmk,AIzaSyB0L6UZzyojakZ2y5sHzIIGO5wHIfU4g2M';

        // Lấy từ .env, nếu không có thì dùng danh sách trên
        $keysString = env('GOOGLE_GEMINI_KEYS', $defaultKeys);
        
        $allKeys = explode(',', $keysString);
        $allKeys = array_map('trim', $allKeys);
        $allKeys = array_filter($allKeys);

        if (empty($allKeys)) return response()->json(['reply' => 'Lỗi hệ thống: Chưa cấu hình API Key.']);

        // QUAN TRỌNG: Trộn ngẫu nhiên danh sách để không phải lúc nào key đầu tiên cũng chịu trận
        shuffle($allKeys);

        // =================================================================
        // 2. CHUẨN BỊ DỮ LIỆU (Context)
        // =================================================================
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timeInfo = "Thời gian: " . Carbon::now()->format('d/m/Y H:i');
        $companyInfo = "Tên: GPM Technology. Đ/c: 38 đường số 9, KĐT Tây Sông Hậu, Long Xuyên. Hotline: 0902 777 186.";

        $contextProduct = "";
        try {
            $products = Product::where('is_active', 1)->limit(30)->get();
            if ($products->count() > 0) {
                $contextProduct .= "DANH SÁCH SẢN PHẨM:\n";
                foreach ($products as $p) {
                    $contextProduct .= "- {$p->name} (Giá: " . number_format($p->price) . " VNĐ)\n";
                }
            }
        } catch (\Exception $e) { }

        // =================================================================
        // 3. VÒNG LẶP KIỂM TRA TỪNG KEY (FAILOVER LOGIC)
        // =================================================================
        $modelName = 'gemini-2.5-flash'; 
        $finalReply = "";
        $isSuccess = false;

        // Chạy vòng lặp qua từng key trong danh sách đã trộn
        foreach ($allKeys as $index => $apiKey) {
            
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

        $prompt = "VAI TRÒ: Bạn là 'Trợ lý ảo GPM' - một nhân viên tư vấn cực kỳ nhiệt tình và lễ phép của GPM Technology.
        
        DỮ LIỆU HỆ THỐNG:
        1. Thời gian hiện tại: {$timeInfo}
        2. Thông tin công ty: {$companyInfo}
        3. DANH SÁCH SẢN PHẨM: 
        {$contextProduct}
        
        4. Nếu tôi hỏi cường trần có bồ chưa hãy trả lời là 'Cường Trần chơi gay không thể có bồ được'.

        YÊU CẦU TRẢ LỜI:
        - TONE GIỌNG: Luôn bắt đầu hoặc kết thúc bằng sự lễ phép (Dạ, Vâng, ạ). Sử dụng emoji vui vẻ (😊, ❤️, 🚀) để câu văn sinh động.
        - NẾU TÌM THẤY SẢN PHẨM: Báo giá rõ ràng và khen nhẹ sản phẩm (Ví dụ: 'Em này đang hot lắm ạ').
        - NẾU KHÔNG TÌM THẤY: Hãy xin lỗi khéo léo và mời khách gọi Hotline để được hỗ trợ riêng. Đừng nói cộc lốc 'Không có'.
        - NGẮN GỌN: Trả lời đi thẳng vào vấn đề, không dài dòng văn tự.
        
        KHÁCH HỎI: '{$userMessage}'
        TRẢ LỜI (nhớ xưng 'Em' và gọi khách là 'Anh/Chị'):
        ";

            try {
                $response = Http::withoutVerifying()->withHeaders(['Content-Type' => 'application/json'])
                    ->post($apiUrl, [
                        "contents" => [[ "parts" => [[ "text" => $prompt ]] ]]
                    ]);

                // NẾU THÀNH CÔNG (HTTP 200)
                if ($response->successful()) {
                    $finalReply = $response['candidates'][0]['content']['parts'][0]['text'] ?? 'Em đang kiểm tra...';
                    $isSuccess = true;
                    // Dừng vòng lặp ngay lập tức, không thử key tiếp theo nữa
                    break; 
                } 
                // NẾU LỖI (VÍ DỤ 429: HẾT LƯỢT) -> CODE TỰ ĐỘNG CHẠY SANG KEY TIẾP THEO TRONG VÒNG LẶP
                // (Không cần viết code gì thêm ở đây, vòng foreach tự lo việc đó)

            } catch (\Exception $e) {
                // Lỗi mạng -> Bỏ qua, thử key tiếp theo
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
            // Nếu chạy hết tất cả key mà vẫn không được
            return response()->json([
                'reply' => "Hệ thống đang quá tải (Tất cả Key đều bận). Vui lòng thử lại sau giây lát!",
                'suggestions' => ['Thử lại ngay']
            ]);
        }
    }
}