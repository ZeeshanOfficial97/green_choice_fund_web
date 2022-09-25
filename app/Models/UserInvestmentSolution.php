<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvestmentSolution extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['investment_id', 'solution_id', 'user_id', 'status'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = ['status' => 'boolean'];

    public function investmentSolution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'id');
    }
}
