<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\System;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $locations = Location::with('parent')->get();
        $systems = System::all();
        $equipments = Equipment::with(['location', 'system'])->orderBy('created_at', 'desc')->get();

        return view('catalog.index', compact('locations', 'systems', 'equipments'));
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
            'notes' => 'nullable|string',
            'specs' => 'nullable|array',
        ]);

        Equipment::create($validated);

        return redirect()->back()->with('success', 'Equipo registrado correctamente.');
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
            'notes' => 'nullable|string',
            'specs' => 'nullable|array',
        ]);

        $equipment->update($validated);

        return redirect()->back()->with('success', 'Equipo actualizado correctamente.');
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
            'form_schema' => 'nullable|array',
        ]);

        System::create($validated);

        return redirect()->back()->with('success', 'Sistema técnico creado correctamente.');
    }

    public function updateSystem(Request $request, System $system)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'form_schema' => 'nullable|array',
        ]);

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
}
