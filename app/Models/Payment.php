<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount_paid',
        'employee_id',
        'user_id',
        'production_id', // ✅ NEW
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ NEW
    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}