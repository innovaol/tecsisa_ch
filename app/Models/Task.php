<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'system_id',
        'location_snapshot',
        'title',
        'description',
        'admin_notes',
        'priority',
        'task_type',
        'is_additional',
        'has_new_cable',
        'has_new_jack',
        'has_new_faceplate',
        'is_certified',
        'assigned_to',
        'status',
        'form_data',
        'initial_status',
        'final_status',
        'started_at',
        'completed_at',
        'required_installations'
    ];

    protected $casts = [
        'form_data' => 'array',
        'completed_at' => 'datetime',
        'started_at' => 'datetime',
        'is_additional' => 'boolean',
        'has_new_cable' => 'boolean',
        'has_new_jack' => 'boolean',
        'has_new_faceplate' => 'boolean',
        'is_certified' => 'boolean'
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }
}
