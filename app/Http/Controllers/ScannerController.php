<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        $equipments = Equipment::with('system', 'location')->get();
        $locations = \App\Models\Location::all();
        $systems = \App\Models\System::all();
        
        return view('scanner.index', compact('equipments', 'locations', 'systems'));
    }

    public function showResult(Equipment $equipment)
    {
        $equipment->load('system', 'location');
        return view('scanner.result', ['equipment' => $equipment]);
    }

    /**
     * Process a search or scan result.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = trim($validated['query']);

        // Find equipment exactly matching internal_id or serial_number (Simulation of barcode/QR reading)
        $equipment = Equipment::with('system', 'location')
            ->where('internal_id', $query)
            ->orWhere('serial_number', $query)
            ->first();

        if ($equipment) {
            // Found exact match "QR / Barcode", redirect to result status page
            return redirect()->route('technician.scanner.result', $equipment->id);
        }

        // Otherwise, do a soft search
        $results = Equipment::with('system', 'location')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('internal_id', 'LIKE', "%{$query}%")
            ->orWhere('serial_number', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        return view('scanner.list', ['results' => $results, 'query' => $query]);
    }

    /**
     * View all equipment with unified search and filters.
     */
    public function equipmentList()
    {
        return $this->index();
    }
}
