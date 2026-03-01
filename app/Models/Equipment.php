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
        'system_id',
        'location_id',
        'status',
        'notes',
        'specs'
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }
}
