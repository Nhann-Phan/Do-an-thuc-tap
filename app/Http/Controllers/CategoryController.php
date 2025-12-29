<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    // 1. Hiển thị danh mục
    public function index()
    {
        // QUAN TRỌNG: Chỉ lấy danh mục GỐC (parent_id là null)
        // Nếu dùng Category::all() sẽ bị lỗi hiện mục con thành ô trống
        $categories = Category::whereNull('parent_id')
                              ->with('children') // Lấy kèm con
                              ->orderBy('id', 'desc')
                              ->get();

        return view('admin.categories', compact('categories'));
    }

    // 2. Thêm danh mục mới
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            // QUAN TRỌNG: Nếu chọn "Danh mục gốc" (giá trị rỗng) thì phải lưu là NULL
            'parent_id' => $request->parent_id ? $request->parent_id : null, 
            'icon' => $request->icon ?? 'fas fa-folder',
        ]);

        return redirect()->back()->with('success', 'Thêm danh mục thành công!');
    }

    // 3. Cập nhật danh mục (ĐÃ SỬA LẠI ĐÚNG LOGIC)
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if ($category) {
            // Cập nhật thông tin cơ bản
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->icon = $request->icon ?? 'fas fa-folder';

            // Xử lý logic cha-con
            // Nếu chọn "Danh mục gốc" hoặc chọn chính nó làm cha -> Về NULL
            if ($request->parent_id && $request->parent_id != $category->id) {
                $category->parent_id = $request->parent_id;
            } else {
                $category->parent_id = null;
            }

            $category->save();
            return redirect()->back()->with('success', 'Đã cập nhật danh mục!');
        }

        return redirect()->back()->with('error', 'Lỗi: Không tìm thấy danh mục!');
    }

    // 4. Xóa danh mục
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            // Xóa hết con trước khi xóa cha để sạch database
            Category::where('parent_id', $id)->delete();
            $category->delete();
            return redirect()->back()->with('success', 'Đã xóa danh mục!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy danh mục!');
    }
    
    // 5. Link tới trang sản phẩm
    public function show($id)
    {
        return redirect()->route('admin.category.products', $id);
    }
}