<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'gender',
        'FCMToken',
        'address',
        'birth_date',
    ];
  
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function appointments()
    {
        return $this->belongsTo('App\Models\User');
    }
}
