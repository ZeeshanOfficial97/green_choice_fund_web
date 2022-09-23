<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Solution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'published', 'status', 'sub_category_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = ['status' => 'boolean'];

    protected $appends = ['added_to_cart'];

    public function solutionMedia()
    {
        return $this->hasMany(SolutionsMedia::class, 'solution_id', 'id')->withTrashed();
    }

    public function getAddedToCartAttribute()
    {
        if ($user = Auth::guard('api')->user()) {
            return Cart::where(['solution_id' => $this->id, 'user_id' => $user->id])->first() ? true : false;
        } else {
            return false;
        }
    }
}
