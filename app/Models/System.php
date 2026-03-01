<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'form_schema'];

    protected $casts = [
        'form_schema' => 'array',
    ];

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
