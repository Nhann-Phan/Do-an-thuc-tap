<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProjectImage;
use App\Models\News;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // Phần khách hàng
    public function index()
    {
        $products = Product::where('is_active', 1)->with('variants')->latest()->get();
        $projectImages = ProjectImage::latest()->take(6)->get();
        $latestNews = News::where('is_active', 1)->latest()->take(5)->get();

        return view('clients.category.category_products', compact('products', 'projectImages', 'latestNews'));
    }   

    public function showByCategory($id)
    {
        $currentCategory = Category::with('children')->findOrFail($id);
        
        // Lấy ID cha và toàn bộ ID con
        $categoryIds = $currentCategory->children->pluck('id')->toArray();
        $categoryIds[] = $currentCategory->id; 

        $products = Product::whereIn('category_id', $categoryIds)
                           ->where('is_active', 1)
                           ->with('variants')
                           ->orderBy('created_at', 'desc')
                           ->get();

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        return view('clients.category.category_products', compact('products', 'menuCategories', 'currentCategory'));
    }

    public function show($id)
    {
        $product = Product::with(['category.parent', 'category.children', 'variants'])->findOrFail($id);
        
        // Logic sản phẩm liên quan
        $relatedProducts = collect(); 
        if ($product->category) {
            $cat = $product->category;
            $ids = [];
            
            if ($cat->parent_id && $cat->parent) {
                  $ids = $cat->parent->children->pluck('id')->toArray();
                  $ids[] = $cat->parent_id; 
            } else {
                $ids = $cat->children->pluck('id')->toArray();
                $ids[] = $cat->id;
            }
            
            $relatedProducts = Product::where('is_active', 1)
                                      ->whereIn('category_id', $ids) 
                                      ->where('id', '!=', $id)      
                                      ->with('variants')
                                      ->inRandomOrder()              
                                      ->take(7)                      
                                      ->get();
        }

        $menuCategories = Category::whereNull('parent_id')->with('children')->get();
        
        return view('clients.category.product_detail', compact('product', 'menuCategories', 'relatedProducts'));
    }

    // Phần quản trị

    public function indexAdmin(Request $request)
    {
        // 1. Lấy danh sách danh mục để đổ vào thẻ <select>
        $categories = Category::all(); 

        // 2. Khởi tạo query
        $query = Product::with(['category', 'variants'])->latest();
        
        // 3. Lọc theo từ khóa (Tên hoặc ID)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('id', $keyword); // Tao cho tìm theo ID luôn cho tiện
            });
        }

        // 4. Lọc theo Danh mục
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // 5. Phân trang và giữ lại tham số trên URL khi sang trang 2, 3
        $products = $query->paginate(10);
        $products->appends($request->all());

        return view('admin.products.product_list', compact('products', 'categories'));
    }

    public function create(Request $request, $id = null)
    {
        $categories = Category::all(); 
        
        $selectedCategoryId = $id;
        if (!$selectedCategoryId) $selectedCategoryId = $request->route('category_id');
        if (!$selectedCategoryId) $selectedCategoryId = $request->get('category_id');

        return view('admin.products.product_create', compact('categories', 'selectedCategoryId'));
    }

    // --- HÀM LƯU MỚI (Đã thêm xử lý SPECS) ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255', 
        ]);

        // 1. Chuẩn bị dữ liệu cơ bản
        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['price'] = $request->input('price', 0); 
        $data['brand'] = $request->brand;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        // 2. Xử lý ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $specsInput = $request->input('specs');
        if ($specsInput) {
            $cleanSpecs = array_filter($specsInput, function($value) {
                return !is_null($value) && $value !== '';
            });
            // Gán vào data (Model sẽ tự động ép kiểu sang JSON nhờ $casts)
            $data['specs'] = !empty($cleanSpecs) ? $cleanSpecs : null;
        }

        // 4. Tạo sản phẩm
        $product = Product::create($data);

        // 5. Lưu các biến thể (Variants)
        $hasVariants = false;
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['name']) && !empty($variantData['price'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name'     => $variantData['name'],
                        'price'    => $variantData['price'],
                        'quantity' => isset($variantData['quantity']) ? (int)$variantData['quantity'] : 0 
                    ]);
                    $hasVariants = true;
                }
            }
        }

        // 6. Cập nhật giá Product = Giá biến thể thấp nhất (Nếu có)
        if ($hasVariants) {
            $minPrice = $product->variants()->min('price');
            if ($minPrice !== null) {
                $product->update(['price' => $minPrice]);
            }
        }

        return redirect()
                ->route('product.create', ['category_id' => $request->category_id])
                ->with('success', 'Đã thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all(); 
        return view('admin.products.product_edit', compact('product', 'categories'));
    }

    // --- HÀM CẬP NHẬT (Đã thêm xử lý SPECS) ---
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id); 

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255', 
        ]);

        // 1. Chuẩn bị dữ liệu cập nhật
        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . $product->id;
        $data['brand'] = $request->brand;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_hot'] = $request->has('is_hot') ? 1 : 0;

        // 2. Xử lý ảnh mới (Xóa ảnh cũ)
        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        // 3. XỬ LÝ SPECS (THÔNG SỐ KỸ THUẬT) 
        $specsInput = $request->input('specs');
        // Nếu user gửi lên specs thì xử lý, nếu không gửi (hoặc xóa hết) thì set null
        if ($specsInput) {
            $cleanSpecs = array_filter($specsInput, function($value) {
                return !is_null($value) && $value !== '';
            });
            $data['specs'] = !empty($cleanSpecs) ? $cleanSpecs : null;
        } else {
             // Trường hợp user không nhập gì cả
             $data['specs'] = null; 
        }

        // 4. Update thông tin chính
        $product->update($data);

        // 5. Xử lý cập nhật Biến thể (Thêm/Sửa/Xóa)
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                
                // A. Xóa biến thể
                if (isset($variantData['delete']) && $variantData['delete'] == 1) {
                    if (isset($variantData['id'])) {
                        ProductVariant::destroy($variantData['id']);
                    }
                    continue; 
                }

                // B. Thêm mới hoặc Cập nhật
                if (!empty($variantData['name']) && !empty($variantData['price'])) {
                    ProductVariant::updateOrCreate(
                        ['id' => $variantData['id'] ?? null], 
                        [
                            'product_id' => $product->id,
                            'name'     => $variantData['name'],
                            'price'    => $variantData['price'],
                            'quantity' => isset($variantData['quantity']) ? (int)$variantData['quantity'] : 0
                        ]
                    );
                }
            }
        }

        // 6. Tính toán lại giá Min
        $minPrice = $product->variants()->min('price');
        if ($minPrice !== null) {
            $product->update(['price' => $minPrice]);
        } 

        return redirect()->route('admin.category.products', $product->category_id)->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }
        
        // Variants sẽ tự động xóa nhờ ràng buộc database (cascade)
        $product->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }

    public function adminShowByCategory($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $categoryIds = $category->children->pluck('id')->toArray();
        $categoryIds[] = $category->id;

        $products = Product::whereIn('category_id', $categoryIds)
                           ->with('variants')
                           ->latest()
                           ->paginate(10);
        
        return view('admin.categories.category_detail', compact('products', 'category'));
    }

    public function search(Request $request)
    {
        // Lấy từ khóa từ ô input có name="q"
        $keyword = $request->input('q');

        // Nếu khách bấm tìm kiếm mà không gõ gì thì đẩy về trang sản phẩm
        if (empty($keyword)) {
            return redirect()->route('product.index');
        }

        // Tìm sản phẩm có tên chứa từ khóa và đang active
        $products = \App\Models\Product::where('is_active', 1)
                    ->where('name', 'LIKE', "%{$keyword}%")
                    ->paginate(12);

        // Giữ lại tham số ?q=... trên URL khi chuyển trang (pagination)
        $products->appends(['q' => $keyword]);

        // Trả về view kèm theo dữ liệu (Mày có thể dùng chung view với trang danh sách sản phẩm)
        return view('clients.category.search', compact('products', 'keyword'));
    }

    public function searchAjax(Request $request)
    {
        $keyword = $request->q;
        
        if(empty($keyword)) {
            return response()->json([]);
        }

        // Tìm tối đa 5 sản phẩm khớp tên
        $products = \App\Models\Product::where('is_active', 1)
                    ->where('name', 'LIKE', "%{$keyword}%")
                    ->select('id', 'name', 'image', 'price') // Chỉ lấy mấy cột cần thiết cho nhẹ
                    ->limit(5)
                    ->get();

        // Định dạng lại ảnh và link để JS dễ dùng
        foreach($products as $p) {
            // Sửa lại chỗ này cho giống trang chi tiết (nơi mà ảnh đang hiện được)
            $p->image_url = $p->image ? asset($p->image) : 'https://placehold.co/150x150?text=No+Image';
            $p->link = route('product.detail', $p->id);
            $p->price_formatted = number_format($p->price, 0, ',', '.') . 'đ';
        }

        return response()->json($products);
    }
}