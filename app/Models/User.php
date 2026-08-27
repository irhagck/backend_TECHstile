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
    //  'pic',
     'employee_details',
     'factory_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function factory()
    {
    return $this->belongsTo(Factory::class);
    }
        public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

}