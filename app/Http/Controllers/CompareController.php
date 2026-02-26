<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CompareController extends Controller
{
    public function index()
    {
        $compareIds = Session::get('compare_products', []);
        $products = Product::whereIn('id', $compareIds)->with('variants')->get();
        return view('clients.category.compare', compact('products'));
    }

    // Hàm lấy danh sách sản phẩm hiện tại trong Session (Dùng chung)
    private function getCurrentCompareList()
    {
        $compareIds = Session::get('compare_products', []);
        // Chỉ lấy id và ảnh để hiển thị trên thanh nhỏ
        return Product::whereIn('id', $compareIds)->select('id', 'name', 'image')->get();
    }

    public function add(Request $request)
    {
        $id = $request->id;
        $compareIds = Session::get('compare_products', []);

        if (!in_array($id, $compareIds)) {
            if (count($compareIds) >= 3) {
                return response()->json(['status' => 'warning', 'message' => 'Chỉ được so sánh tối đa 3 sản phẩm!']);
            }
            
            $compareIds[] = $id;
            Session::put('compare_products', $compareIds);
            
            // 🔥 TRẢ VỀ DANH SÁCH SẢN PHẨM MỚI
            return response()->json([
                'status' => 'success', 
                'message' => 'Đã thêm vào so sánh!',
                'list' => $this->getCurrentCompareList() 
            ]);
        }

        return response()->json(['status' => 'info', 'message' => 'Sản phẩm đã có trong danh sách!']);
    }

    public function remove(Request $request)
    {
        $id = $request->id;
        $compareIds = Session::get('compare_products', []);

        if(($key = array_search($id, $compareIds)) !== false) {
            unset($compareIds[$key]);
            Session::put('compare_products', array_values($compareIds));
        }

        // 🔥 TRẢ VỀ DANH SÁCH MỚI SAU KHI XÓA
        return response()->json([
            'status' => 'success', 
            'message' => 'Đã xóa sản phẩm!',
            'list' => $this->getCurrentCompareList()
        ]);
    }

    // Thêm hàm này để xóa toàn bộ session
    public function clear()
    {
        Session::forget('compare_products');
        return response()->json([
            'status' => 'success', 
            'message' => 'Đã xóa toàn bộ danh sách so sánh!'
        ]);
    }
}