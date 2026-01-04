<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Khai báo cho phép lưu tất cả các cột
    protected $fillable = [
        'name', 
        'slug',
        'sku',              // Mã sản phẩm
        'brand',            // Thương hiệu
        'category_id',      // ID danh mục
        'price',            // Giá bán
        'sale_price',       // Giá khuyến mãi
        'quantity',         // Số lượng
        'short_description', // Mô tả ngắn
        'description',      // Bài viết chi tiết
        'image',            // Ảnh đại diện
        'gallery',          // Thư viện ảnh
        'is_active',        // Trạng thái hiển thị
        'is_hot'            // Sản phẩm HOT
    ];

    // Quan hệ với bảng Categories (Danh mục)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // --- BỔ SUNG: Quan hệ với bảng ProductVariants (Biến thể giá) ---
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('created_at', 'asc');
    }
}