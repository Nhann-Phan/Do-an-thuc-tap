<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', // Phải có cái này
        'customer_name', 
        'phone_number', 
        'address', 
        'booking_time', 
        'issue_description', 
        'status'
    ];

    // Một lịch đặt thuộc về một khách hàng
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}