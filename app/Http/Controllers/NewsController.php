<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    public function index()
    {
        // Lấy tất cả tin tức active, mới nhất lên đầu, phân trang 9 bài mỗi trang
        $newsList = News::where('is_active', 1)
                        ->latest()
                        ->paginate(9);

        return view('clients.news_list', compact('newsList'));
    }
    // Hiển thị form thêm mới
    public function create()
    {
        return view('admin.news_create');
    }

    // Lưu tin tức
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . time();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        News::create($data);

        return redirect()->back()->with('success', 'Đã đăng tin tức thành công!');
    }

        public function detail($id)
    {
        // Tìm bài viết theo ID, nếu không thấy thì báo lỗi 404
        $news = News::where('is_active', 1)->findOrFail($id);

        // Lấy thêm các tin khác để hiển thị ở cột bên phải (trừ bài đang xem)
        $relatedNews = News::where('is_active', 1)
                            ->where('id', '!=', $id)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('clients.news_detail', compact('news', 'relatedNews'));
    }
}   