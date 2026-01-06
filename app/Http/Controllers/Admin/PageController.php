<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Hiển thị danh sách các trang.
     */
    public function index()
    {
        // Lấy danh sách, sắp xếp theo thứ tự (position) tăng dần
        $pages = Page::orderBy('position', 'asc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Hiển thị form thêm mới.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Lưu trang mới vào database.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề trang',
            'content.required' => 'Nội dung không được để trống',
        ]);

        $data = $request->all();

        // 2. Tự động tạo Slug nếu người dùng không nhập
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // 3. Xử lý checkbox hiển thị (nếu không check thì set = 0)
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // 4. Tạo mới
        Page::create($data);

        return redirect()->route('pages.index')->with('success', 'Đã thêm trang mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Cập nhật dữ liệu trang.
     */
    public function update(Request $request, $id)
    {
        // 1. Validate
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            // Slug phải là duy nhất, ngoại trừ chính id hiện tại
            'slug' => 'required|unique:pages,slug,' . $id,
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'slug.unique' => 'Đường dẫn (slug) này đã tồn tại',
        ]);

        $page = Page::findOrFail($id);
        $data = $request->all();

        // 2. Xử lý checkbox
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // 3. Cập nhật
        $page->update($data);

        return redirect()->route('pages.index')->with('success', 'Cập nhật trang thành công!');
    }

    /**
     * Xóa trang.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('pages.index')->with('success', 'Đã xóa trang thành công!');
    }
}