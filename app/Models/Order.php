<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = []; // Cho phép lưu mọi trường

    // Liên kết: 1 Đơn hàng có nhiều Chi tiết
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}