<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_num',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function Patient()
    {
        return $this->hasOne('App\Models\Patient');
    }
    public function Secretary()
    {
        return $this->hasOne('App\Models\Secretary');
    }
    public function Doctor()
    {
        return $this->hasOne('App\Models\Doctor');
    }
    public function Department()
    {
        return $this->hasOne('App\Models\Department');
    }
    public function WorkDays()
    {
        return $this->hasMany('App\Models\WorkDay');
    }
    public function Appointments()
    {
        return $this->hasMany('App\Models\Appointment');
    }
  
}
