<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'batch_id',
        'variety_type',
        'total_length',
        'ready_production',
        'waste_production',
        'remaining',
        'machine_id',
        'employee_id',
        'manager_id',
        'factory_id',
        'shift_start',
        'shift_end',
        'status',
    ];
    public function factory() {
    return $this->belongsTo(Factory::class);
}
    public function employee() {   // Define the relationship with the Employee model
    return $this->belongsTo(Employee::class);
}

public function manager() {
    return $this->belongsTo(Manager::class);
}

public function machine() {
    return $this->belongsTo(Machine::class);
}
  public function machineemploye()
{
    return $this->belongsTo(Machine::class, 'machine_id', 'id');
}

    public function employeedetails()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'id'
        );
    }
}
