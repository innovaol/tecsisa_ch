<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'has_ports',
        'form_schema',
        'port_config',
        'maintenance_interval_days',
        'maintenance_guide'
    ];

    protected $casts = [
        'has_ports' => 'boolean',
        'form_schema' => 'array',
        'port_config' => 'array',
    ];

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
