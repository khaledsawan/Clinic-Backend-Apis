<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'specialty',
        'image_path',
        'consultation_price',
        'review',
        'department_id',
        'user_id',
    ];
  
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function Appointments()
    {
        return $this->hasMany('App\Models\Appointment');
    }
}
