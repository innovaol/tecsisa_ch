<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\System;
use App\Models\Rack;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $locationsTree = Location::with(['children', 'equipments'])->whereNull('parent_id')->get();
        $locationsFlat = Location::all();
        $systems = System::all();
        $equipments = Equipment::with(['location', 'system'])->orderBy('created_at', 'desc')->get();
        $racks = Rack::with('location')->get();

        return view('catalog.index', compact('locationsTree', 'locationsFlat', 'systems', 'equipments', 'racks'));
    }

    public function updateEquipment(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'internal_id' => 'required|unique:equipment,internal_id,' . $equipment->id,
            'name' => 'required|string|max:255',
            'form_factor' => 'required|in:rackmount,peripheral,network_point',
            'u_height' => 'required_if:form_factor,rackmount|nullable|integer|min:1|max:42',
            'system_id' => 'required|exists:systems,id',
            'location_id' => 'nullable|exists:locations,id',
            'status' => 'required|in:operative,under_maintenance,out_of_service',
            'installation_date' => 'nullable|date',
            'last_maintenance_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'specs' => 'nullable|array',
        ]);

        if (!empty($validated['last_maintenance_at']) || !empty($validated['installation_date'])) {
            $baseDate = \Carbon\Carbon::parse($validated['last_maintenance_at'] ?? $validated['installation_date']);
            $system = System::find($validated['system_id']);
            $validated['next_maintenance_at'] = $baseDate->addDays($system->maintenance_interval_days ?? 90);
        }

        $equipment->update($validated);

        return redirect()->back()->with('success', 'Equipo actualizado correctamente.');
    }

    public function storeEquipment(Request $request)
    {
        $validated = $request->validate([
            'internal_id' => 'required|unique:equipment,internal_id',
            'name' => 'required|string|max:255',
            'form_factor' => 'required|in:rackmount,peripheral,network_point',
            'u_height' => 'required_if:form_factor,rackmount|nullable|integer|min:1|max:42',
            'system_id' => 'required|exists:systems,id',
            'location_id' => 'nullable|exists:locations,id',
            'status' => 'required|in:operative,under_maintenance,out_of_service',
            'installation_date' => 'nullable|date',
            'last_maintenance_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'specs' => 'nullable|array',
        ]);

        if (!empty($validated['last_maintenance_at']) || !empty($validated['installation_date'])) {
            $baseDate = \Carbon\Carbon::parse($validated['last_maintenance_at'] ?? $validated['installation_date']);
            $system = System::find($validated['system_id']);
            $validated['next_maintenance_at'] = $baseDate->addDays($system->maintenance_interval_days ?? 90);
        }

        Equipment::create($validated);

        return redirect()->back()->with('success', 'Equipo registrado correctamente.');
    }

    public function destroyEquipment(Equipment $equipment)
    {
        // Si el equipo está en un rack, la migración maneja el set null
        $equipment->delete();

        return redirect()->back()->with('success', 'Equipo eliminado del inventario.');
    }

    // --- Systems Management ---

    public function storeSystem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'has_ports' => 'boolean',
            'form_schema' => 'nullable|array',
            'port_config' => 'nullable|array',
            'maintenance_interval_days' => 'required|integer|min:1',
            'maintenance_guide' => 'nullable|string',
        ]);

        $validated['has_ports'] = $request->boolean('has_ports');

        System::create($validated);

        return redirect()->back()->with('success', 'Sistema técnico creado correctamente.');
    }

    public function updateSystem(Request $request, System $system)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'has_ports' => 'boolean',
            'form_schema' => 'nullable|array',
            'port_config' => 'nullable|array',
            'maintenance_interval_days' => 'required|integer|min:1',
            'maintenance_guide' => 'nullable|string',
        ]);

        $validated['has_ports'] = $request->boolean('has_ports');

        $system->update($validated);

        return redirect()->back()->with('success', 'Sistema técnico actualizado correctamente.');
    }

    public function destroySystem(System $system)
    {
        if ($system->equipments()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar un sistema que tiene equipos asociados.');
        }

        $system->delete();

        return redirect()->back()->with('success', 'Sistema técnico eliminado.');
    }

    // --- Locations Management ---

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'level' => 'nullable|integer',
        ]);

        Location::create($validated);

        return redirect()->back()->with('success', 'Ubicación creada correctamente.');
    }

    public function updateLocation(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id',
            'level' => 'nullable|integer',
        ]);

        $location->update($validated);

        return redirect()->back()->with('success', 'Ubicación actualizada correctamente.');
    }

    public function destroyLocation(Location $location)
    {
        if ($location->children()->count() > 0 || $location->equipments()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar una ubicación con sub-ubicaciones o equipos asociados.');
        }

        $location->delete();

        return redirect()->back()->with('success', 'Ubicación eliminada.');
    }

    // --- Racks Management ---

    public function storeRack(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_units' => 'required|integer|min:1|max:52',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,full,maintenance',
            'notes' => 'nullable|string',
        ]);

        Rack::create($validated);

        return redirect()->back()->with('success', 'Rack registrado correctamente.');
    }

    public function updateRack(Request $request, Rack $rack)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_units' => 'required|integer|min:1|max:52',
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,full,maintenance',
            'notes' => 'nullable|string',
        ]);

        $rack->update($validated);

        return redirect()->back()->with('success', 'Rack actualizado correctamente.');
    }

    public function destroyRack(Rack $rack)
    {
        if ($rack->units()->whereNotNull('equipment_id')->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar un rack que contiene equipos instalados.');
        }

        $rack->delete();

        return redirect()->back()->with('success', 'Rack eliminado del catálogo.');
    }
}
