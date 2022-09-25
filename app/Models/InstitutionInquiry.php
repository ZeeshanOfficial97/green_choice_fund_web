<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitutionInquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'contact_no',
        'country_code',
        'address',
        'company_url',
        'description',
        'contact_reason_id',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function contactReason()
    {
        return $this->belongsTo(Lookup::class, 'contact_reason_id', 'id');
    }
}
