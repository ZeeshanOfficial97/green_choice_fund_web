<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolutionsMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solutions_media';

    protected $fillable = ['id', 'media_url', 'solution_id', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

}
