<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'published', 'status', 'category_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = ['status' => 'boolean'];

    protected $appends = ['is_wishlisted'];

    public function getIsWishlistedAttribute()
    {
        if ($user = Auth::guard('api')->user()) {
            return Wishlist::where(['sub_category_id' => $this->id, 'user_id' => $user->id])->first() ? true : false;
        } else {
            return false;
        }
    }

    public function subCategoryMedia()
    {
        return $this->hasMany(SubCategoriesMedia::class, 'sub_category_id', 'id')->withTrashed();
    }

    public function subCategoryWishlist()
    {
        return $this->hasMany(Wishlist::class, 'sub_category_id', 'id');
    }
}
