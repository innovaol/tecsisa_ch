<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Location::class , 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class , 'parent_id');
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
