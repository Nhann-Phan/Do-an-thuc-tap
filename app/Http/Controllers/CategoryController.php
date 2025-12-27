<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Nhớ dòng này để dùng Str::slug
use App\Models\Category;
use App\Models\ProjectImage; // Để sửa lỗi update nhầm model

class CategoryController extends Controller
{
    // 1. Hiển thị trang quản lý
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.categories', compact('categories'));
    }

    // 2. Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Tạo slug từ tên
            'parent_id' => $request->parent_id,
            'icon' => $request->icon ?? 'fas fa-folder',
        ]);

        return redirect()->back()->with('success', 'Thêm danh mục thành công!');
    }

    // 3. Xóa danh mục
    public function destroy($id)
    {
        $category = Category::find($id);
        
        // Xóa các danh mục con trước
        if ($category) {
            Category::where('parent_id', $id)->delete();
            $category->delete();
            return redirect()->back()->with('success', 'Đã xóa danh mục!');
        }
        
        return redirect()->back()->with('error', 'Danh mục không tồn tại!');
    }

    // 4. Cập nhật danh mục
    // --- KHẮC PHỤC LỖI UPDATE NHẦM MODEL ---
    public function update(Request $request, $id)
    {
        // 1. Tìm đúng đối tượng ẢNH (ProjectImage) chứ không phải Category
        $image = ProjectImage::find($id);
        
        if ($image) {
            // 2. Cập nhật mô tả
            $image->caption = $request->input('caption');

            // 3. Nếu người dùng chọn ảnh mới -> Thay thế ảnh cũ
            if ($request->hasFile('image')) {
                // Xóa file ảnh cũ khỏi thư mục để đỡ rác
                if (File::exists(public_path($image->image_path))) {
                    File::delete(public_path($image->image_path));
                }

                // Upload ảnh mới
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/projects'), $filename);
                
                // Cập nhật đường dẫn trong database
                $image->image_path = '/uploads/projects/' . $filename;
            }

            $image->save(); // Lưu lại
            return redirect()->back()->with('success', 'Đã cập nhật ảnh thành công!');
        }

        return redirect()->back()->with('error', 'Không tìm thấy ảnh cần sửa!');
    }
    
    // 5. Xem chi tiết (để thêm sản phẩm vào danh mục này)
    public function show($id)
    {
        $category = Category::with(['products' => function($query) {
            $query->orderBy('id', 'desc');
        }])->findOrFail($id);

        // Lưu ý: Đảm bảo bạn có file view 'admin.category_detail'
        // Nếu chưa có, có thể đổi thành trỏ về trang danh sách sản phẩm lọc theo danh mục
        return view('admin.category_detail', compact('category'));
    }
}