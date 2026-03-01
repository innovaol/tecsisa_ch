<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\RackUnit;
use App\Models\Equipment;
use App\Models\Port;
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

    public function getEquipmentPorts(Equipment $equipment)
    {
        // Simple demonstration logic: if the equipment has no ports generated yet, 
        // we generate a standard 24-port switch layout for it.
        if ($equipment->ports()->count() === 0) {
            $portsToCreate = [];
            for ($i = 1; $i <= 24; $i++) {
                $portsToCreate[] = [
                    'number_label' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'port_type' => 'rj45',
                    'status' => 'free',
                ];
            }
            // Add 2 SFP ports
            $portsToCreate[] = ['number_label' => 'SFP 1', 'port_type' => 'sfp', 'status' => 'free'];
            $portsToCreate[] = ['number_label' => 'SFP 2', 'port_type' => 'sfp', 'status' => 'free'];

            $equipment->ports()->createMany($portsToCreate);
        }

        $ports = $equipment->ports()->orderBy('id')->get();
        // Since connections are not fully generated yet, we map them as standard
        $mappedPorts = $ports->map(function ($port) {
            return [
            'id' => $port->id,
            'label' => $port->number_label,
            'type' => $port->port_type,
            'status' => $port->status,
            // Add connection details if existed later
            'connected_to' => null
            ];
        });

        return response()->json([
            'equipment' => [
                'id' => $equipment->id,
                'internal_id' => $equipment->internal_id,
                'name' => $equipment->name,
                'specs' => $equipment->specs
            ],
            'ports' => $mappedPorts
        ]);
    }
}
