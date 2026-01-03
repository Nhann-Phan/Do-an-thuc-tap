<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    // ==========================================
    // PHẦN 1: CLIENT (KHÁCH HÀNG)
    // ==========================================

    public function index()
    {
        // Lấy tất cả tin tức active, mới nhất lên đầu, phân trang 9 bài mỗi trang
        $newsList = News::where('is_active', 1)
                        ->latest()
                        ->paginate(9);

        return view('clients.news_list', compact('newsList'));
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

    // ==========================================
    // PHẦN 2: ADMIN (QUẢN TRỊ)
    // ==========================================

    // 1. DANH SÁCH TIN TỨC
    public function indexAdmin()
    {
        $newsList = News::latest()->paginate(10);
        
        // SỬA LỖI: Trỏ đúng vào thư mục admin/news/index.blade.php
        return view('admin.news.news_list', compact('newsList'));
    }

    // 2. HIỂN THỊ FORM THÊM MỚI
    public function create()
    {
        // SỬA LỖI: Trỏ đúng vào thư mục admin/news/create.blade.php
        return view('admin.news.news_create');
    }

    // 3. LƯU TIN TỨC MỚI
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

        // Chuyển hướng về trang danh sách sau khi thêm xong
        return redirect()->route('news.index_admin')->with('success', 'Đã đăng tin tức thành công!');
    }

    // 4. HIỂN THỊ FORM SỬA
    public function edit($id)
    {
        $news = News::findOrFail($id);
        
        // SỬA LỖI: Trỏ đúng vào thư mục admin/news/edit.blade.php
        return view('admin.news.news_edit', compact('news'));
    }

    // 5. CẬP NHẬT TIN TỨC
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . $news->id; // Cập nhật slug mới
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Xử lý ảnh: Nếu có upload ảnh mới -> Xóa ảnh cũ -> Lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($news->image && File::exists(public_path($news->image))) {
                File::delete(public_path($news->image));
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        $news->update($data);

        return redirect()->route('news.index_admin')->with('success', 'Cập nhật bài viết thành công!');
    }

    // 6. XÓA TIN TỨC
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image && File::exists(public_path($news->image))) {
            File::delete(public_path($news->image));
        }

        $news->delete();

        return redirect()->back()->with('success', 'Đã xóa bài viết!');
    }
}