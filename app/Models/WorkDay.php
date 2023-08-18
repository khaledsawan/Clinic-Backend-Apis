<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'doctor_id',
    ];

    public function users()
    {
    	return $this->belongsTo('App\Models\User');
    }
}
