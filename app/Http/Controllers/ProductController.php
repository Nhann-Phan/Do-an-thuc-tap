<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProjectImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; // Sử dụng thư viện File giống Gallery

class ProductController extends Controller
{
    // ==========================================
    // PHẦN 1: FRONTEND (KHÁCH HÀNG)
    // ==========================================

    public function index()
    {
        $products = Product::where('is_active', 1)->get();
        $projectImages = ProjectImage::latest()->take(6)->get();
        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        return view('clients.store', compact('products', 'projectImages', 'menuCategories'));
    }   

    public function showByCategory($id)
    {
        $currentCategory = Category::with('children')->findOrFail($id);
        $categoryIds = $currentCategory->children->pluck('id')->toArray();
        $categoryIds[] = $currentCategory->id; 

        $products = Product::whereIn('category_id', $categoryIds)
                           ->where('is_active', 1)
                           ->orderBy('created_at', 'desc')
                           ->get();

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        return view('clients.category_products', compact('products', 'menuCategories', 'currentCategory'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        return view('clients.product_detail', compact('product', 'menuCategories'));
    }

    // ==========================================
    // PHẦN 2: BACKEND ADMIN (QUẢN TRỊ)
    // ==========================================

    public function indexAdmin()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.product_list', compact('products'));
    }

    public function create($category_id)
    {
        $category = Category::findOrFail($category_id);
        return view('admin.product_create', compact('category'));
    }

    // --- SỬA LẠI HÀM LƯU (GIỐNG GALLERY) ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['price'] = $request->input('price', 0); 

        // XỬ LÝ ẢNH MỚI (Lưu trực tiếp vào public/uploads/products)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Di chuyển file vào thư mục public/uploads/products
            $file->move(public_path('uploads/products'), $filename);
            
            // Lưu đường dẫn vào DB (Lưu ý: không có chữ public ở đầu)
            $data['image'] = 'uploads/products/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        Product::create($data);

        return redirect()->route('category.show', $request->category_id)->with('success', 'Đã thêm sản phẩm!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); 
        return view('admin.product_edit', compact('product', 'categories'));
    }

    // --- SỬA LẠI HÀM CẬP NHẬT (GIỐNG GALLERY) ---
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . $product->id;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            // Upload ảnh mới
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        $product->update($data);

        return redirect()->route('category.show', $product->category_id)->with('success', 'Cập nhật thành công!');
    }

    // --- SỬA LẠI HÀM XÓA (GIỐNG GALLERY) ---
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Xóa ảnh trong thư mục public
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $product->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }

    // --- ADMIN: XEM SẢN PHẨM THEO DANH MỤC ---
    public function adminShowByCategory($id)
    {
        // 1. Lấy thông tin danh mục
        $category = Category::with('children')->findOrFail($id);

        // 2. Lấy ID của danh mục này và các danh mục con (để hiển thị hết)
        $categoryIds = $category->children->pluck('id')->toArray();
        $categoryIds[] = $category->id;

        // 3. Lọc sản phẩm theo danh sách ID trên
        $products = Product::whereIn('category_id', $categoryIds)
                           ->latest()
                           ->paginate(10);
        
        // 4. Trả về view danh sách (Kèm biến $category để hiển thị tiêu đề)
        return view('admin.product_list', compact('products', 'category'));
    }
}