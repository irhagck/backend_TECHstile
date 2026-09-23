<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
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
     
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function factory()
    {
    return $this->belongsTo(Factory::class);
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

}