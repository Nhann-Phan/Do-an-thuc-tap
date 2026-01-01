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
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $timeInfo = Carbon::now()->format('d/m/Y H:i');
        
        // 3. GỌI GEMINI (VỚI MODEL CHUẨN 1.5)
        // Mình đổi về 2.5-flash vì đây là bản ổn định nhất, ít lỗi 404 nhất
        $modelName = 'gemini-2.5-flash'; 
        
        $debugLog = []; // Mảng lưu lại lỗi của từng key để soi

        foreach ($allKeys as $index => $apiKey) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

            $prompt = "Trả lời ngắn gọn: {$userMessage}";

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
                        'suggestions' => ['📷 Giá Camera', '💻 Laptop văn phòng']
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
            'reply' => "⚠️ DEBUG REPORT (Tất cả key đều thất bại):\n" . $errorString,
            'suggestions' => ['Thử lại']
        ]);
    }
}