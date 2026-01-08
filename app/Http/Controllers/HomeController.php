<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Page;           // <--- QUAN TRỌNG: Nhớ import Model Page
use App\Models\ProjectImage;   // (Hoặc App\Models\Gallery tùy theo tên model của bạn)

class HomeController extends Controller
{
    public function index()
    {
        // 1. LẤY DỮ LIỆU CẤU HÌNH TRANG CHỦ (Để hiển thị Intro, Slider...)
        // Tìm trang có slug là 'trang-chu'. Bạn nhớ vào Admin tạo trang này nhé.
        $page = Page::where('slug', 'trang-chu')->with('sections')->first();

        // 2. Lấy sản phẩm nổi bật
        $products = Product::where('is_active', 1)
                           ->orderBy('created_at', 'desc')
                           ->take(10)
                           ->get();

        // 3. Lấy tin tức mới nhất
        $latestNews = News::orderBy('created_at', 'desc')
                          ->take(3)
                          ->get();

        // 4. Lấy hình ảnh dự án
        $projectImages = ProjectImage::latest()->take(6)->get(); 

        // Trả về View (Lưu ý: tên view phải đúng với file home.blade.php bạn vừa sửa)
        // Mình sửa lại thành 'clients.home' cho khớp với các bước trước.
        return view('clients.home', compact('products', 'latestNews', 'projectImages', 'page'));
    }

    // Hàm hiển thị trang giới thiệu động (Các trang con khác)
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', 1)->firstOrFail();
        return view('clients.page_detail', compact('page'));
    }
}