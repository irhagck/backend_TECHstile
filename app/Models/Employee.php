<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Employee extends Model
{
    protected $fillable = [
        'factory_id',
        'user_id',
        'employee_id',
        'shift_starttime',
        'shift_endtime',
        'timestamp',
    ];

    /**
     * Get user details
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}