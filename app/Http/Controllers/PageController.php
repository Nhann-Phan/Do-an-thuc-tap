<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    // Hàm hiển thị danh sách trang
    public function index()
    {
        $pages = Page::orderBy('position', 'asc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    // Hàm hiển thị form thêm mới
    public function create()
    {
        return view('admin.pages.create');
    }

    // Hàm lưu trang mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $data = $request->all();
        
        // Tự động tạo slug nếu không nhập
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        // Xử lý checkbox (nếu không check thì trả về null, cần set về 0)
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Page::create($data);

        return redirect()->route('pages.index')->with('success', 'Thêm trang mới thành công');
    }

    // Hàm hiển thị form chỉnh sửa
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    // Hàm cập nhật trang
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'slug' => 'required|unique:pages,slug,'.$id,
        ]);

        $page = Page::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $page->update($data);

        return redirect()->route('pages.index')->with('success', 'Cập nhật thành công');
    }

    // Hàm xóa trang
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Đã xóa trang');
    }
}