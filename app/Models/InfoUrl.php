<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfoUrl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['url_key', 'url_version', 'url_web', 'updated_date','status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'updated_date' => 'date',
    ];
}
