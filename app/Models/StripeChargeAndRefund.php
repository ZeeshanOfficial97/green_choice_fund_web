<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripeChargeAndRefund extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['stripe_charge_id', 'stripe_refund_id', 'description', 'user_id', 'status'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = ['status' => 'boolean'];
}
