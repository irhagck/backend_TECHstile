<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'variety_type',
        'total_length',
        'ready_production',
        'machine_id',
        'employee_id',
        'factory_id',
        'shift_start',
        'shift_end'
    ];
    public function factory() {
    return $this->belongsTo(Factory::class);
}
    public function employee() {   // Define the relationship with the Employee model
    return $this->belongsTo(Employee::class);
}

public function machine() {
    return $this->belongsTo(Machine::class);
}

// public function manager() {
//     return $this->belongsTo(Manager::class);
// }
}
