<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'name',
        'email',
        'password',
        'type',
        'bar_code',
        'profile_photo',
        'enrollment_id',
        'active',
        'birth_date',
        'course',
        'status_enrollment_id',
        'ticket_amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array

     */

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     * protected $appends = [
    'profile_photo_url'    ];
     */

    public function isRUEmployee(){
        return $this->type == UserType::RUEmployee->value;
    }

    public function isThridPartyCashierEmployee(){
        return $this->type == UserType::ThirdPartyCashierEmployee->value;
    }

    // TODO -> Create this logic
    public function isRoomsAdministrator(){
        return true;
    }
}
