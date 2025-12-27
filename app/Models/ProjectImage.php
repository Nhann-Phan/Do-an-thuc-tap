<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    // Chỉ giữ lại dòng này
    protected $fillable = ['image_path', 'caption'];
}