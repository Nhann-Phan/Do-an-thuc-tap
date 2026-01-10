<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProjectImage;
use App\Models\News;
use App\Models\ProductVariant; // <--- 1. QUAN TRỌNG: Phải import Model này
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // ==========================================
    // PHẦN 1: FRONTEND (KHÁCH HÀNG)
    // ==========================================

    public function index()
    {
        // Lấy sản phẩm hiển thị, kèm theo biến thể để tính giá min-max nếu cần
        $products = Product::where('is_active', 1)->with('variants')->get();
        $projectImages = ProjectImage::latest()->take(6)->get();
        
        // Lấy 5 tin tức mới nhất
        $latestNews = News::where('is_active', 1)->latest()->take(5)->get();

        return view('clients.store', compact('products', 'projectImages', 'latestNews'));
    }   

    public function showByCategory($id)
    {
        $currentCategory = Category::with('children')->findOrFail($id);
        $categoryIds = $currentCategory->children->pluck('id')->toArray();
        $categoryIds[] = $currentCategory->id; 

        $products = Product::whereIn('category_id', $categoryIds)
                           ->where('is_active', 1)
                           ->with('variants') // Eager load variants
                           ->orderBy('created_at', 'desc')
                           ->get();

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        return view('clients.category.category_products', compact('products', 'menuCategories', 'currentCategory'));
    }

    public function show($id)
    {
        // Load kèm variants để hiển thị nút chọn giá
        $product = Product::with(['category.parent', 'category.children', 'variants'])->findOrFail($id);
        
        $relatedProducts = collect(); 

        if ($product->category) {
            $cat = $product->category;
            $ids = [];
            
            if ($cat->parent_id) {
                if ($cat->parent) {
                     $ids = $cat->parent->children->pluck('id')->toArray();
                     $ids[] = $cat->parent_id; 
                }
            } else {
                $ids = $cat->children->pluck('id')->toArray();
                $ids[] = $cat->id;
            }
            
            $relatedProducts = Product::where('is_active', 1)
                                      ->whereIn('category_id', $ids) 
                                      ->where('id', '!=', $id)      
                                      ->with('variants')
                                      ->inRandomOrder()              
                                      ->take(4)                      
                                      ->get();
        }

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        
        return view('clients.category.product_detail', compact('product', 'menuCategories', 'relatedProducts'));
    }

    // ==========================================
    // PHẦN 2: BACKEND ADMIN (QUẢN TRỊ)
    // ==========================================

    public function indexAdmin(Request $request)
    {
        // Load thêm 'variants' để đếm số lượng phiên bản ở trang danh sách
        $query = Product::with(['category', 'variants'])->latest();
        
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        $products = $query->paginate(10);
        return view('admin.product_list', compact('products'));
    }

    public function create(Request $request, $id = null)
    {
        $categories = Category::all(); 
        
        $selectedCategoryId = $id;
        if (!$selectedCategoryId) $selectedCategoryId = $request->route('category_id');
        if (!$selectedCategoryId) $selectedCategoryId = $request->get('category_id');

        return view('admin.product_create', compact('categories', 'selectedCategoryId'));
    }

    // --- HÀM LƯU MỚI (ĐÃ CẬP NHẬT: LƯU SỐ LƯỢNG) ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255', 
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['price'] = $request->input('price', 0); 
        $data['brand'] = $request->brand;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        // 1. Tạo sản phẩm chính
        $product = Product::create($data);

        // 2. [CẬP NHẬT] Lưu các biến thể (Variants) KÈM SỐ LƯỢNG
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['name']) && !empty($variantData['price'])) {
                    ProductVariant::create([
                        'product_id' => $product->id, // Lấy ID vừa tạo
                        'name'     => $variantData['name'],
                        'price'    => $variantData['price'],
                        // 🔥 THÊM DÒNG NÀY: Lưu số lượng, nếu không nhập thì mặc định 0
                        'quantity' => isset($variantData['quantity']) ? (int)$variantData['quantity'] : 0 
                    ]);
                }
            }
        }

        return redirect()
                ->route('product.create', ['category_id' => $request->category_id])
                ->with('success', 'Đã thêm sản phẩm thành công! Mời nhập tiếp sản phẩm tiếp theo.');
    }

    public function edit($id)
    {
        // Load variants để hiển thị trong form sửa
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all(); 
        return view('admin.product_edit', compact('product', 'categories'));
    }

    // --- HÀM CẬP NHẬT (ĐÃ CẬP NHẬT: LƯU SỐ LƯỢNG) ---
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); 

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255', 
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . $product->id;
        $data['brand'] = $request->brand;

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

        // 1. Cập nhật thông tin chính
        $product->update($data);

        // 2. [CẬP NHẬT] Xử lý cập nhật Biến thể (Variants) KÈM SỐ LƯỢNG
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                
                // Trường hợp A: Xóa biến thể (nếu tick chọn xóa)
                if (isset($variantData['delete']) && $variantData['delete'] == 1) {
                    if (isset($variantData['id'])) {
                        ProductVariant::destroy($variantData['id']);
                    }
                    continue; // Bỏ qua dòng này
                }

                // Trường hợp B: Thêm mới hoặc Cập nhật
                if (!empty($variantData['name']) && !empty($variantData['price'])) {
                    ProductVariant::updateOrCreate(
                        ['id' => $variantData['id'] ?? null], // Điều kiện tìm
                        [
                            'product_id' => $product->id,
                            'name'     => $variantData['name'],
                            'price'    => $variantData['price'],
                            // 🔥 THÊM DÒNG NÀY: Cập nhật số lượng
                            'quantity' => isset($variantData['quantity']) ? (int)$variantData['quantity'] : 0
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.category.products', $product->category_id)->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }
        
        // Variants sẽ tự động xóa nhờ ràng buộc khóa ngoại (cascade) trong migration
        $product->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }

    public function adminShowByCategory($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $categoryIds = $category->children->pluck('id')->toArray();
        $categoryIds[] = $category->id;

        $products = Product::whereIn('category_id', $categoryIds)
                           ->with('variants') // Load thêm variants
                           ->latest()
                           ->paginate(10);
        
        return view('admin.category_detail', compact('products', 'category'));
    }
}