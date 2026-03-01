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
            $rack = Rack::with(['units.equipment.system'])->findOrFail($rackId);
        }
        else {
            $rack = Rack::with(['units.equipment.system'])->first();
        }

        $racks = Rack::all();

        // 1. Equipos en inventario que sí son de rack y no han sido instalados
        $assignedEquipmentIds = RackUnit::whereNotNull('equipment_id')->pluck('equipment_id');
        $unassignedEquipment = Equipment::with('system')->where('form_factor', 'rackmount')
            ->whereNotIn('id', $assignedEquipmentIds)
            ->get();

        // 2. Equipos periféricos o puntos de red (no van en rack pero se pueden cablear)
        $externalTargets = Equipment::with('system')->whereIn('form_factor', ['peripheral', 'network_point'])->get();

        return view('rack.builder', compact('rack', 'racks', 'unassignedEquipment', 'externalTargets'));
    }

    public function save(Request $request, Rack $rack)
    {
        $request->validate([
            'units' => 'array',
        ]);

        // 1. Identificar equipos que estaban antes en el rack para detectar remociones
        $oldEquipmentIds = $rack->units()->whereNotNull('equipment_id')->pluck('equipment_id')->unique()->toArray();

        // 2. Limpiar estado actual para resincronizar
        $rack->units()->delete();

        $unitsData = $request->input('units', []);
        $newEquipmentIds = [];

        // 3. Re-instalar equipos según el nuevo mapa
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
                $newEquipmentIds[] = $unit['db_id'];
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Topología de rack guardada correctamente.']);
    }
}
