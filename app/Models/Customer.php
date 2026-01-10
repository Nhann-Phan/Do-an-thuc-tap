<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone_number', 'address', 'email', 'notes'
    ];

    // Một khách hàng có nhiều lịch đặt
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Lấy lịch sử Mua hàng (Checkout)
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}