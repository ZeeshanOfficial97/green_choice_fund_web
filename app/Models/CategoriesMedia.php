<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriesMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories_media';

    protected $fillable = ['id', 'media_url', 'category_id', 'media_type', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

}
