<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'number_label',
        'port_type',
        'status',
        'mac_address',
        'vlan',
        'notes',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Obtains the connection where this port is either Port A or Port B.
     * Assuming 1 active connection per port based on migration unique constraints.
     */
    public function connection()
    {
        return Connection::where('port_a_id', $this->id)->orWhere('port_b_id', $this->id)->first();
    }
}
