<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasRoles;
    protected $fillable = [
     'name',
     'email',
     'password',
     'phone_no',
     'cnic',
     'address',
     'pic',
     'role_id',
     'employee_details',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}