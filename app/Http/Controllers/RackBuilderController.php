<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\RackUnit;
use App\Models\Equipment;
use Illuminate\Http\Request;

class RackBuilderController extends Controller
{
    public function index(Request $request)
    {
        $rackId = $request->query('rack_id');

        if ($rackId) {
            $rack = Rack::with(['units.equipment'])->findOrFail($rackId);
        }
        else {
            $rack = Rack::with(['units.equipment'])->first();
        }

        $racks = Rack::all();

        // Find equipment that hasn't been racked yet anywhere
        $assignedEquipmentIds = RackUnit::whereNotNull('equipment_id')->pluck('equipment_id');
        $unassignedEquipment = Equipment::whereNotIn('id', $assignedEquipmentIds)->get();

        return view('rack.builder', compact('rack', 'racks', 'unassignedEquipment'));
    }

    public function save(Request $request, Rack $rack)
    {
        $request->validate([
            'units' => 'array',
        ]);

        // We delete all existing placements for this rack to resync the state efficiently
        // In a complex app, we might want to check for diffs, but for Rack units, state sync is solid.
        $rack->units()->delete();

        $unitsData = $request->input('units', []);

        foreach ($unitsData as $unit) {
            if (!empty($unit['occupied']) && !empty($unit['db_id'])) {
                RackUnit::create([
                    'rack_id' => $rack->id,
                    'unit_number' => $unit['number'],
                    'side' => 'front',
                    'equipment_id' => $unit['db_id'],
                    'position_size' => $unit['size'],
                    'content_type' => 'equipment',
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Topología de rack guardada con éxito']);
    }
}
