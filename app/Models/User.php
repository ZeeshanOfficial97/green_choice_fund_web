<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_no',
        'country_code',
        'profile_photo_path',
        'email_verified_at',

        'privacy_policy_version',
        'is_notification_enabled',
        'social_account_id',
        'user_type_id',
        'stripe_user_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'profile_photo_path',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', 'status' => 'boolean',
        'user_type_id' => 'string', 'is_notification_enabled' => 'boolean'
    ];

    protected $appends = [
        'profile_photo_url', 'cart_items_count'
    ];


    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCartItemsCountAttribute()
    {
        return Cart::where('user_id', '=', $this->id)->count();
    }

    public function getProfilePhotoUrlAttribute()
    {
        if (str_starts_with($this->profile_photo_path, 'http')) {
            return $this->profile_photo_path;
        } else {
            return $this->profile_photo_path
                ? asset('storage/' . $this->profile_photo_path)
                : $this->defaultProfilePhotoUrl();
        }
    }

    private function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=random&background=random&size=256';
    }
}
