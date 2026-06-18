<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'shift_starttime',
        'shift_endtime',
        'timestamp',
        'user_id',
    ];
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}