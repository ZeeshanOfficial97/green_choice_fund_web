<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infographic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'name', 'description', 'file_url', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
