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

    // --- HÀM SHOW ---
    public function show($id)
    {
        $product = Product::with('category.parent', 'category.children')->findOrFail($id);
        
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
                                      ->inRandomOrder()              
                                      ->take(4)                      
                                      ->get();
        }

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        
        return view('clients.product_detail', compact('product', 'menuCategories', 'relatedProducts'));
    }

    // ==========================================
    // PHẦN 2: BACKEND ADMIN (QUẢN TRỊ)
    // ==========================================

    public function indexAdmin(Request $request)
    {
        // Thêm logic tìm kiếm nếu có
        $query = Product::with('category')->latest();
        
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

    // --- HÀM STORE (ĐÃ CẬP NHẬT BRAND) ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255', // Validate brand
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['price'] = $request->input('price', 0); 
        
        // Lưu Brand (Nếu không nhập thì để null)
        $data['brand'] = $request->brand;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        Product::create($data);

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

    // --- HÀM UPDATE (ĐÃ CẬP NHẬT BRAND) ---
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); 

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255', // Validate brand
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . $product->id;
        
        // Cập nhật Brand
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