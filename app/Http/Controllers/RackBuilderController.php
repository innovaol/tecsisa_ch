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

        if (!$rack) {
            return redirect()->route('catalog.index')->with('error', 'No hay Racks creados. Por favor define uno primero en el catálogo.');
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
            'units' => 'required|array',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $rack) {
                // 1. Clear current state for this rack
                $rack->units()->delete();

                $unitsData = $request->input('units', []);

                // 2. Re-install equipment according to the new map
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
            });

            return response()->json(['status' => 'success', 'message' => 'Topología de rack guardada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al guardar la topología: ' . $e->getMessage()], 500);
        }
    }

    public function generatePDF(Rack $rack)
    {
        $rack->load(['location', 'units.equipment.system']);
        
        // Sorting units decresing to show the rack top-to-bottom
        $units = $rack->units->sortByDesc('unit_number');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rack.pdf_template', compact('rack', 'units'))
            ->setPaper('a4', 'portrait');

        $filename = 'Certificado-Rack-' . str_replace(' ', '_', $rack->name) . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
