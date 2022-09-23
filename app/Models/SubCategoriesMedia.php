<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoriesMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_categories_media';

    protected $fillable = ['id', 'image_url', 'sub_category_id', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

}
