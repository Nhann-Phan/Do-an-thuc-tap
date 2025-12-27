<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectImage; // Sử dụng Model ảnh
use Illuminate\Support\Facades\File; // [QUAN TRỌNG] Thêm dòng này để sửa lỗi gạch đỏ chữ File

class GalleryController extends Controller
{
    // 1. Hiển thị danh sách ảnh
    public function index()
    {
        $images = ProjectImage::latest()->get();
        return view('admin.gallery', compact('images'));
    }

    // 2. Thêm ảnh mới
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Lưu ảnh vào thư mục public/uploads/projects
            $file->move(public_path('uploads/projects'), $filename);

            ProjectImage::create([
                'image_path' => '/uploads/projects/' . $filename,
                'caption' => $request->input('caption')
            ]);

            return redirect()->back()->with('success', 'Đã thêm ảnh thành công!');
        }
    }

    // 3. Cập nhật ảnh (ĐÃ SỬA LỖI LOGIC TẠI ĐÂY)
    public function update(Request $request, $id)
    {
        // Tìm đúng đối tượng ẢNH (ProjectImage)
        $image = ProjectImage::find($id);
        
        if ($image) {
            // Cập nhật mô tả (caption) thay vì 'name'
            $image->caption = $request->input('caption');

            // Nếu người dùng chọn ảnh mới -> Thay thế ảnh cũ
            if ($request->hasFile('image')) {
                // Xóa file ảnh cũ cho sạch server
                if (File::exists(public_path($image->image_path))) {
                    File::delete(public_path($image->image_path));
                }

                // Upload ảnh mới
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/projects'), $filename);
                
                // Cập nhật đường dẫn mới vào database
                $image->image_path = '/uploads/projects/' . $filename;
            }

            $image->save(); // Lưu lại thay đổi
            return redirect()->back()->with('success', 'Đã cập nhật ảnh thành công!');
        }

        return redirect()->back()->with('error', 'Không tìm thấy ảnh cần sửa!');
    }

    // 4. Xóa ảnh
    public function destroy($id)
    {
        $image = ProjectImage::find($id);
        if ($image) {
            // Xóa file vật lý
            if (File::exists(public_path($image->image_path))) {
                File::delete(public_path($image->image_path));
            }
            // Xóa trong database
            $image->delete();
            return redirect()->back()->with('success', 'Đã xóa ảnh!');
        }
        return redirect()->back()->with('error', 'Ảnh không tồn tại');
    }
}