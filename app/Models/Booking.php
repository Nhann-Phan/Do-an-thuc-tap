<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name', 
        'phone_number',
        'address', 
        'booking_time',
        'issue_description',
        'status'
    ];
}