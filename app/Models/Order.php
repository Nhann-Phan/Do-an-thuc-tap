<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    // Cho phép lưu tất cả các cột (bao gồm cả customer_id vừa thêm)
    protected $guarded = []; 

    /**
     * Mối quan hệ 1: Lấy danh sách sản phẩm trong đơn này
     * Sử dụng: $order->items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}