<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProjectImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // ==========================================
    // PHẦN 1: FRONTEND (KHÁCH HÀNG)
    // ==========================================

    public function index()
    {
        $products = Product::where('is_active', 1)->get();
        $projectImages = ProjectImage::latest()->take(6)->get();
        return view('clients.store', compact('products', 'projectImages'));
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

    // --- HÀM SHOW: ĐÃ THÊM LOGIC LẤY SẢN PHẨM TƯƠNG TỰ ---
    public function show($id)
    {
        // Load kèm quan hệ category để check cha/con
        $product = Product::with('category.parent', 'category.children')->findOrFail($id);
        
        $relatedProducts = collect(); // Khởi tạo collection rỗng

        if ($product->category) {
            $cat = $product->category;
            $ids = [];
            
            // Logic: Lấy hết ID của các danh mục liên quan (Anh em cùng cha hoặc Cha-Con)
            if ($cat->parent_id) {
                // Nếu là mục con -> Lấy danh sách ID của các mục con khác cùng cha
                if ($cat->parent) {
                     $ids = $cat->parent->children->pluck('id')->toArray();
                     $ids[] = $cat->parent_id; // Thêm cả cha vào
                }
            } else {
                // Nếu là mục cha -> Lấy danh sách ID của các con
                $ids = $cat->children->pluck('id')->toArray();
                $ids[] = $cat->id;
            }
            
            // Query lấy sản phẩm tương tự
            $relatedProducts = Product::where('is_active', 1)
                                    ->whereIn('category_id', $ids) // Thuộc nhóm ID đã lọc
                                    ->where('id', '!=', $id)       // Trừ chính sản phẩm đang xem
                                    ->inRandomOrder()              // Lấy ngẫu nhiên
                                    ->take(4)                      // Lấy 4 sản phẩm
                                    ->get();
        }

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        
        return view('clients.product_detail', compact('product', 'menuCategories', 'relatedProducts'));
    }

    // ==========================================
    // PHẦN 2: BACKEND ADMIN (QUẢN TRỊ)
    // ==========================================

    public function indexAdmin()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.product_list', compact('products'));
    }

    // --- HÀM CREATE: TỰ ĐỘNG BẮT ID DANH MỤC TỪ URL ---
    public function create(Request $request, $id = null)
    {
        $categories = Category::all(); 
        
        // Ưu tiên lấy từ tham số hàm, sau đó đến route param, cuối cùng là query string
        $selectedCategoryId = $id;
        if (!$selectedCategoryId) $selectedCategoryId = $request->route('category_id');
        if (!$selectedCategoryId) $selectedCategoryId = $request->get('category_id');

        return view('admin.product_create', compact('categories', 'selectedCategoryId'));
    }

    // --- HÀM STORE: LƯU XONG QUAY LẠI FORM ĐỂ NHẬP TIẾP ---
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

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        Product::create($data);

        // Quay lại trang create kèm theo ID danh mục để nhập tiếp
        return redirect()
                ->route('product.create', ['category_id' => $request->category_id])
                ->with('success', 'Đã thêm sản phẩm thành công! Mời nhập tiếp sản phẩm tiếp theo.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); 
        return view('admin.product_edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); // Sửa lỗi undefined variable $product

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . $product->id;

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        $product->update($data);

        return redirect()->route('admin.category.products', $product->category_id)->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }
        
        $product->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }

    public function adminShowByCategory($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $categoryIds = $category->children->pluck('id')->toArray();
        $categoryIds[] = $category->id;

        $products = Product::whereIn('category_id', $categoryIds)
                           ->latest()
                           ->paginate(10);
        
        return view('admin.category_detail', compact('products', 'category'));
    }
}