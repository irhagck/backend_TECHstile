<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_starttime',
        'shift_endtime',
        'timestamp',
        'user_id'
    ];
    // app/Models/Employee.php

public function user() {
    // Yeh batata hai ke har employee ek user se belong karta hai
    return $this->belongsTo(User::class, 'user_id'); 
}
}