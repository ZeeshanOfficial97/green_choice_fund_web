<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'published', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function categoryMedia()
    {
        return $this->hasMany(CategoriesMedia::class, 'category_id', 'id')->withTrashed();
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class, 'category_id', 'id')->withTrashed();
    }
    public function getSolutionsCountAttribute() {
        return $this->solutions->count();
    }
}
