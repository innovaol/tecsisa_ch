<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_a_id',
        'port_b_id',
        'cable_type',
        'cable_color',
        'label',
        'notes',
    ];

    public function portA()
    {
        return $this->belongsTo(Port::class , 'port_a_id');
    }

    public function portB()
    {
        return $this->belongsTo(Port::class , 'port_b_id');
    }
}
