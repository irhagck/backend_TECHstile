<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Production;

class Notification extends Model
{

protected $fillable = [

'user_id',
'production_id',
'sender_id',
'title',
'message',
'type',
'is_read'

];


protected $casts = [

'is_read'=>'boolean'

];


public function user()
{
return $this->belongsTo(User::class);
}


public function sender()
{
return $this->belongsTo(User::class,'sender_id');
}


public function production()
{
return $this->belongsTo(Production::class);
}


public function employeedetails()
{
    return $this->belongsTo(
        Employee::class,
        'employee_id'
    );
}


public function machineemploye()
{
    return $this->belongsTo(
        Machine::class,
        'machine_id'
    );
}
}