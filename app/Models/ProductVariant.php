<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    // Khai báo các cột được phép lưu
    protected $fillable = [
        'product_id',
        'name',
        'price'
    ];

    // Thiết lập quan hệ ngược lại với Product (nếu cần dùng sau này)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}