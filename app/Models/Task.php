<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'equipment_id',
        'assigned_to',
        'status',
        'priority',
        'form_data',
        'completed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'completed_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }
}
