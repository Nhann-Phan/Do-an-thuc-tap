<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    // --- QUAN TRỌNG: Khai báo các cột được phép lưu ---
    protected $fillable = [
        'page_id', 
        'title', 
        'type', 
        'data', 
        'position'
    ];

    // Tự động chuyển đổi cột 'data' từ JSON sang Mảng khi lấy ra
    protected $casts = [
        'data' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}