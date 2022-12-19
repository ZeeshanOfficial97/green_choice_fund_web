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
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $casts = ['status' => 'boolean'];

    public const ORDER_STATUS = array(
        'Pending',
        'Verified',
        'In Process',
        'Processed',
        'Cancelled',

    );
    public const ORDER_BG_CLR = array(
        '#000000',
        '#FF0000',
        '#FF0000',
        '#FF0000',
        '#FF0000',
    );

    public const ORDER_TEXT_CLR = array(
        '#FFFFFF',
        '#000000',
        '#000000',
        '#000000',
        '#000000',
    );


    public const ORDER_STATUS_INDEX = [
        'Pending' => 0,
        'Verified' => 1,
        'In Process' => 2,
        'Processed' => 3,
        'Cancelled' => 4,
    ];

    public function userInvestmentSolution()
    {
        return $this->hasMany(UserInvestmentSolution::class, 'investment_id', 'id')->withTrashed();
    }

    public function investmentUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
