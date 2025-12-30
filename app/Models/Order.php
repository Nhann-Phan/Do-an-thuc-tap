<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    // Cách 1: Cho phép lưu tất cả các cột
    protected $guarded = []; 
    
    // HOẶC Cách 2: Nếu dùng fillable thì phải có 'total_money' trong này
    // protected $fillable = ['name', 'phone', 'email', 'address', 'note', 'payment_method', 'status', 'total_money'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}