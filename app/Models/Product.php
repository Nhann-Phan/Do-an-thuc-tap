<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Khai báo cho phép lưu tất cả các cột chúng ta vừa tạo
    protected $fillable = [
        'name', 
        'slug',
        'sku',              // Mã sản phẩm
        'category_id',      // ID danh mục
        'price',            // Giá bán
        'sale_price',       // Giá khuyến mãi
        'quantity',         // Số lượng
        'short_description', // Mô tả ngắn
        'description',       // Bài viết chi tiết
        'image',             // Ảnh đại diện
        'gallery',           // Thư viện ảnh
        'is_active',         // Trạng thái hiển thị
        'is_hot'             // Sản phẩm HOT
    ];

    public function category()
        {
            return $this->belongsTo(Category::class, 'category_id');
        }
}