<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\Page;
// use App\Models\Gallery; // Tạm thời comment dòng này nếu chưa tạo Model Gallery

class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy sản phẩm nổi bật
        $products = Product::where('is_active', 1) // Giả sử có cột is_active
                           ->orderBy('created_at', 'desc')
                           ->take(10)
                           ->get();

        // 2. Lấy tin tức mới nhất
        $latestNews = News::orderBy('created_at', 'desc')
                          ->take(3)
                          ->get();

        // 3. Lấy hình ảnh dự án (Xử lý an toàn khi chưa có Gallery)
        // $projectImages = \App\Models\Gallery::inRandomOrder()->take(6)->get(); 
        $projectImages = collect([]); // Trả về rỗng để không gây lỗi view

        return view('clients.store', compact('products', 'latestNews', 'projectImages'));
    }

    // Hàm hiển thị trang giới thiệu động
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', 1)->firstOrFail();
        return view('clients.page_detail', compact('page'));
    }
}