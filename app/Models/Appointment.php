<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'time',
        'date',
        'description',
        'cancel_reason',
        'doctor_id',
        'patient_id',
        'department_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function doctor()
    {
        return $this->belongsTo('App\Models\Doctor');
    }

}
