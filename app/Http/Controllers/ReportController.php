<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\System;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Show the filter view for weekly reports
     */
    public function index()
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        $systems = System::withCount('equipments')->get();
        return view('reports.weekly_filter', compact('systems'));
    }

    /**
     * Generate the massive concatenated PDF report
     */
    public function generateWeekly(Request $request)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        $validated = $request->validate([
            'system_id' => 'required|exists:systems,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'report_title' => 'required|string|max:255'
        ]);

        $system = System::findOrFail($validated['system_id']);

        // Find all COMPLETED tasks for this system in the date range
        $tasks = Task::with(['equipment.location', 'assignee'])
            ->whereHas('equipment', function ($query) use ($validated) {
            $query->where('system_id', $validated['system_id']);
        })
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$validated['start_date'] . ' 00:00:00', $validated['end_date'] . ' 23:59:59'])
            ->orderBy('completed_at', 'asc')
            ->get();

        if ($tasks->isEmpty()) {
            return back()->with('error', 'No se encontraron tareas completadas para este sistema en el rango seleccionado.');
        }

        $reportData = [
            'system' => $system,
            'tasks' => $tasks,
            'title' => $validated['report_title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date']
        ];

        // Generate the PDF using a consolidated template
        $pdf = Pdf::loadView('reports.weekly_pdf_template', $reportData)
            ->setPaper('a4', 'portrait');

        $filename = 'REPORTE-SEMANAL-' . str_replace(' ', '-', strtoupper($system->name)) . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
