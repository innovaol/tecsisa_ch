<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\RackUnit;
use App\Models\Equipment;
use App\Models\Port;
use App\Models\Connection;
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

        // 1. Equipos en inventario que sí son de rack y no han sido instalados
        $assignedEquipmentIds = RackUnit::whereNotNull('equipment_id')->pluck('equipment_id');
        $unassignedEquipment = Equipment::where('form_factor', 'rackmount')
            ->whereNotIn('id', $assignedEquipmentIds)
            ->get();

        // 2. Equipos periféricos o puntos de red (no van en rack pero se pueden cablear)
        $externalTargets = Equipment::whereIn('form_factor', ['peripheral', 'network_point'])->get();

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

        // 4. Mecanismo de Limpieza Automática de Enlaces (Data Integrity)
        // Buscamos equipos que fueron retirados del rack (estaban antes, no están ahora)
        $removedIds = array_diff($oldEquipmentIds, $newEquipmentIds);

        if (!empty($removedIds)) {
            // Buscamos todos los puertos de los equipos retirados
            $portIds = Port::whereIn('equipment_id', $removedIds)->pluck('id');

            if ($portIds->count() > 0) {
                // Identificamos las conexiones físicas que pasan por estos puertos
                $connections = Connection::whereIn('port_a_id', $portIds)
                    ->orWhereIn('port_b_id', $portIds)
                    ->get();

                foreach ($connections as $conn) {
                    // Liberamos ambos extremos (el que se va y el que se queda en el rack)
                    Port::whereIn('id', [$conn->port_a_id, $conn->port_b_id])->update(['status' => 'free']);
                    // Eliminamos el enlace físico (el cable fue desconectado)
                    $conn->delete();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Topología guardada. Se han desconectado los enlaces de los equipos retirados.']);
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

        $portIds = $ports->pluck('id');
        $connections = Connection::with(['portA.equipment', 'portB.equipment'])
            ->whereIn('port_a_id', $portIds)
            ->orWhereIn('port_b_id', $portIds)
            ->get();

        $mappedPorts = $ports->map(function ($port) use ($connections) {
            $conn = $connections->first(function ($c) use ($port) {
                    return $c->port_a_id == $port->id || $c->port_b_id == $port->id;
                }
                );

                $connectedTo = null;
                $cableInfo = null;

                if ($conn) {
                    $otherPort = ($conn->port_a_id == $port->id) ? $conn->portB : $conn->portA;

                    $connectedTo = [
                        'equipment_id' => $otherPort->equipment->internal_id,
                        'equipment_name' => $otherPort->equipment->name,
                        'port_label' => $otherPort->number_label
                    ];

                    $cableInfo = [
                        'id' => $conn->id,
                        'type' => $conn->cable_type,
                        'color' => $conn->cable_color
                    ];
                }

                return [
                'id' => $port->id,
                'label' => $port->number_label,
                'type' => $port->port_type,
                'status' => $port->status,
                'connected_to' => $connectedTo,
                'cable' => $cableInfo
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

    public function connectPorts(Request $request)
    {
        $request->validate([
            'port_a_id' => 'required|exists:ports,id',
            'port_b_id' => 'required|exists:ports,id|different:port_a_id',
            'cable_type' => 'required|string',
            'cable_color' => 'required|string',
        ]);

        $portA = Port::findOrFail($request->port_a_id);
        $portB = Port::findOrFail($request->port_b_id);

        if ($portA->status === 'connected' || $portB->status === 'connected') {
            return response()->json(['error' => 'Uno o ambos puertos ya están ocupados'], 422);
        }

        $connection = Connection::create([
            'port_a_id' => $portA->id,
            'port_b_id' => $portB->id,
            'cable_type' => $request->cable_type,
            'cable_color' => $request->cable_color,
        ]);

        $portA->update(['status' => 'connected']);
        $portB->update(['status' => 'connected']);

        return response()->json(['status' => 'success', 'connection' => $connection]);
    }

    public function disconnectConnection(Connection $connection)
    {
        $portA = $connection->portA;
        $portB = $connection->portB;

        $connection->delete();

        if ($portA)
            $portA->update(['status' => 'free']);
        if ($portB)
            $portB->update(['status' => 'free']);

        return response()->json(['status' => 'success', 'message' => 'Desconexión exitosa']);
    }
}
