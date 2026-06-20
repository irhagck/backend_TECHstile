<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'machine_type',
        'time',
        'status',
        'factory_id',
    ];

    public function factory()
    {
        return $this->belongsTo(
            Factory::class,
            'factory_id'
        );
    }

    public function productions()
{
    return $this->hasMany(
        Production::class,
        'id'
    );
}
}