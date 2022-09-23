<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['provider', 'provider_user_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
