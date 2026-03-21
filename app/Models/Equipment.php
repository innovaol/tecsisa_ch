<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_id',
        'serial_number',
        'name',
        'form_factor',
        'u_height',
        'system_id',
        'parent_id', // Temporal maintenance
        'source_equipment_id',
        'source_port',
        'destination_equipment_id',
        'destination_port',
        'certification_pdf',
        'certification_status',
        'certification_date',
        'cable_category',
        'cable_length',
        'status',
        'installation_date',
        'last_maintenance_at',
        'next_maintenance_at',
        'notes',
        'specs'
    ];

    protected $casts = [
        'specs' => 'array',
        'certification_date' => 'date',
        'last_maintenance_at' => 'date',
        'next_maintenance_at' => 'date',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function rackUnits()
    {
        return $this->hasMany(RackUnit::class);
    }

    public function rack()
    {
        return $this->hasOneThrough(Rack::class, RackUnit::class, 'equipment_id', 'id', 'id', 'rack_id');
    }

    public function source()
    {
        return $this->belongsTo(Equipment::class, 'source_equipment_id');
    }

    public function destination()
    {
        return $this->belongsTo(Equipment::class, 'destination_equipment_id');
    }
}
