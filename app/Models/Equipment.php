<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_id',
        'name',
        'form_factor',
        'u_height',
        'system_id',
        'location_id',
        'status',
        'installation_date',
        'last_maintenance_at',
        'next_maintenance_at',
        'notes',
        'specs'
    ];

    protected $casts = [
        'specs' => 'array',
        'installation_date' => 'date',
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
}
