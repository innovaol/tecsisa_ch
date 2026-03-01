<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\Equipment;
use Illuminate\Http\Request;

class RackBuilderController extends Controller
{
    public function index()
    {
        // Load the first rack as default for now, with its units
        $rack = Rack::with(['units.equipment'])->first();
        $racks = Rack::all(); // To build a selector later

        // Find equipment that hasn't been racked yet (simple simulation)
        // In a real scenario, we'd check if Equipment ID exists in rack_units table.
        // For now, let's load all equipment to drag & drop
        $unassignedEquipment = Equipment::all();

        return view('rack.builder', compact('rack', 'racks', 'unassignedEquipment'));
    }
}
