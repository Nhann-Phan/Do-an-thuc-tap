<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Khai báo cho phép lưu tất cả các cột (Mass Assignment)
    protected $fillable = [
        'name', 
        'slug',
        'sku',      
        'brand',       
        'category_id',   
        'price',     
        'sale_price',    
        'quantity',     
        'short_description', 
        'description', 
        'specs',
        'image',           
        'gallery',          
        'is_active',     
        'is_hot'            
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    // Quan hệ với bảng Categories (Danh mục)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với bảng ProductVariants (Biến thể giá)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('created_at', 'asc');
    }
}