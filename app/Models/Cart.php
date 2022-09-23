<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    protected $fillable = ['solution_id', 'user_id', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
}
