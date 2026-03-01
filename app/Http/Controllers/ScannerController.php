<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * View for the mobile technician to scan or manually search for equipment.
     */
    public function index()
    {
        return view('scanner.index');
    }

    /**
     * Process a search or scan result.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $validated['query'];

        // Find equipment exactly matching internal_id (Simulation of QR reading)
        $equipment = Equipment::with('system', 'location')->where('internal_id', $query)->first();

        if ($equipment) {
            // Found exact match via "QR"
            return view('scanner.result', ['equipment' => $equipment]);
        }

        // Otherwise, do a soft search
        $results = Equipment::where('name', 'LIKE', "%{$query}%")
            ->orWhere('internal_id', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        return view('scanner.list', ['results' => $results, 'query' => $query]);
    }
}
