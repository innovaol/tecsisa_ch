<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_id',
        'unit_number',
        'side',
        'equipment_id',
        'position_size',
        'content_type',
    ];

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
