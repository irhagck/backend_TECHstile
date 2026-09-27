<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Backup extends Model
{
    protected $fillable = ['filename', 'size', 'type', 'status', 'error', 'created_by'];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}