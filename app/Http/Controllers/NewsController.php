<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    // ==========================================
    // PHẦN 1: CLIENT (GIAO DIỆN KHÁCH HÀNG)
    // ==========================================

    public function index()
    {
        // paginate() luôn trả về object, kể cả khi không có bài nào
        $newsList = News::where('is_active', 1)
                        ->latest()
                        ->paginate(9);

        return view('clients.news.news_list', compact('newsList'));
    }

    public function detail($id)
    {
        // ✅ CHUẨN: Tìm bài viết theo ID (khớp với Route /tin-tuc/{id})
        $news = News::where('is_active', 1)
                    ->where('id', $id)
                    ->firstOrFail();

        // Lấy tin liên quan (trừ bài đang xem)
        $relatedNews = News::where('is_active', 1)
                            ->where('id', '!=', $news->id)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('clients.news.news_detail', compact('news', 'relatedNews'));
    }

    // ==========================================
    // PHẦN 2: ADMIN (QUẢN TRỊ VIÊN)
    // ==========================================

    // 1. DANH SÁCH TIN TỨC
    public function indexAdmin()
    {
        $newsList = News::latest()->paginate(10);
        return view('admin.news.news_list', compact('newsList'));
    }

    // 2. FORM THÊM MỚI
    public function create()
    {
        return view('admin.news.news_create');
    }

    // 3. LƯU TIN MỚI
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|max:255',
            'summary' => 'nullable|max:500',
            // ✅ Thêm dòng này để cho phép nội dung trống lúc tạo mới (giống Page)
            'content' => 'nullable', 
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết',
            'image.image'    => 'File tải lên phải là hình ảnh',
            'image.max'      => 'Ảnh không được quá 2MB'
        ]);

        $data = $request->except('image');
        
        // Tạo slug
        $data['slug'] = Str::slug($request->title) . '-' . time();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        News::create($data);

        return redirect()->route('news.index_admin')->with('success', 'Đã đăng tin tức thành công!');
    }

    // 4. FORM SỬA
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.news_edit', compact('news'));
    }

    // 5. CẬP NHẬT
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title'   => 'required|max:255',
            'summary' => 'nullable|max:500',
            'content' => 'nullable', // ✅ Cho phép cập nhật mà nội dung vẫn trống
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        // Cập nhật slug (giữ nguyên ID trong slug để SEO tốt hơn nếu muốn)
        $data['slug'] = Str::slug($request->title) . '-' . $news->id;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Xử lý ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($news->image && File::exists(public_path($news->image))) {
                File::delete(public_path($news->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $data['image'] = 'uploads/news/' . $filename;
        }

        $news->update($data);

        return redirect()->route('news.index_admin')->with('success', 'Cập nhật bài viết thành công!');
    }

    // 6. XÓA TIN
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