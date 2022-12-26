<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['solution_id', 'user_id', 'status'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = ['status' => 'boolean'];

    public function solution() {
        return $this->belongsTo(Solution::class, 'solution_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
