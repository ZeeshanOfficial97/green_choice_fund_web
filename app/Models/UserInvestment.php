<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvestment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'investment_num', 'name', 'email', 'country_code', 'contact_no', 'address', 'dob', 'invested_amount', 'user_id', 'stripe_charge_id',
        'investment_status', 'status'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = ['status' => 'boolean'];


    public function userInvestmentSolution()
    {
        return $this->hasMany(UserInvestmentSolution::class, 'investment_id', 'id')->withTrashed();
    }

    public function investmentUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
