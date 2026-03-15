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
    public function index(Request $request)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        // Logic for dates based on preset OR manual input
        $preset = $request->input('preset_week');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($preset === 'current' || (!$preset && !$start_date)) {
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($preset === 'last') {
            $startDate = date('Y-m-d', strtotime('monday last week'));
            $endDate = date('Y-m-d', strtotime('sunday last week'));
        } else {
            try {
                // Convert from d/m/Y back to Y-m-d for querying
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback to current week if dates are invalid
                $startDate = date('Y-m-d', strtotime('monday this week'));
                $endDate = date('Y-m-d', strtotime('sunday this week'));
            }
        }

        // Base Query
        $query = Task::with(['equipment.location', 'system', 'assignee'])
            ->orderBy('updated_at', 'desc');

        // Apply Filters
        if ($request->filled('system_id')) {
            $query->where(function($q) use ($request) {
                $q->where('system_id', $request->system_id)
                  ->orWhereHas('equipment', function($sq) use ($request) {
                      $sq->where('system_id', $request->system_id);
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Include everything including drafts to ensure nothing is missed
            $query->whereIn('status', ['draft', 'pending', 'in_progress', 'in_review', 'completed', 'verified']);
        }

        if ($request->filled('building')) {
            $query->where('form_data->building', 'LIKE', '%' . $request->building . '%');
        }

        if ($request->filled('technician_id')) {
            $query->where('assigned_to', $request->technician_id);
        }

        // Date filter: Use created_at to ensure that any task initiated in the period is shown,
        // regardless of if it was updated/completed slightly outside the range (e.g. past midnight)
        $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $tasks = $query->paginate(50)->withQueryString();
        
        // Data for dropdowns
        $systems = System::all();
        $technicians = \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'Tecnico'); })->get();

        return view('reports.index', compact('tasks', 'systems', 'technicians', 'startDate', 'endDate'));
    }

    public function generateWeekly(Request $request)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        $validated = $request->validate([
            'system_id' => 'nullable|exists:systems,id',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $startDateObj = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['start_date'], 'America/Panama')->startOfDay();
        $endDateObj = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['end_date'], 'America/Panama')->endOfDay();

        $allStatuses = ['draft', 'pending', 'in_progress', 'in_review', 'active', 'approval', 'completed', 'verified'];

        // Use status filter if passed, otherwise include everything
        $statusFilter = $request->input('status_filter', []);
        $statusesToInclude = !empty($statusFilter) ? array_intersect($statusFilter, $allStatuses) : $allStatuses;

        $query = Task::with(['equipment.location', 'equipment.rack', 'assignee', 'system', 'equipment.system'])
            ->whereIn('status', $statusesToInclude)
            ->where(function($q) use ($startDateObj, $endDateObj) {
                // Match tasks created OR updated in the period
                $q->whereBetween('created_at', [$startDateObj, $endDateObj])
                  ->orWhereBetween('updated_at', [$startDateObj, $endDateObj]);
            })
            ->orderBy('created_at', 'asc');

        $systemName = "GLOBAL";
        $systemObj = null;

        if (!empty($validated['system_id'])) {
            $systemObj = System::findOrFail($validated['system_id']);
            $systemName = $systemObj->name;
            $query->whereHas('equipment', function ($q) use ($validated) {
                $q->where('system_id', $validated['system_id']);
            });
        }

        $tasks = $query->get();

        $stats = [
            'total_tasks' => $tasks->count(),
            'certified' => $tasks->where('is_certified', true)->count(),
            'new_cable' => $tasks->where('has_new_cable', true)->count(),
            'new_jack' => $tasks->where('has_new_jack', true)->count(),
            'new_faceplate' => $tasks->where('has_new_faceplate', true)->count(),
        ];

        // Consolidated Materials
        $consolidatedMaterials = [];
        foreach ($tasks as $task) {
            $mats = $task->form_data['materials'] ?? [];
            foreach ($mats as $m) {
                $name = strtoupper(trim($m['name']));
                $qty = (int)($m['qty'] ?? 1);
                if (isset($consolidatedMaterials[$name])) {
                    $consolidatedMaterials[$name] += $qty;
                } else {
                    $consolidatedMaterials[$name] = $qty;
                }
            }
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        $reportData = [
            'system'               => $systemObj,
            'tasks'                => $tasks,
            'title'                => 'Reporte Ejecutivo — ' . strtoupper($systemName),
            'start_date'           => $startDateObj,
            'end_date'             => $endDateObj,
            'stats'                => $stats,
            'consolidatedMaterials'=> $consolidatedMaterials,
            'company_name'         => $settings['company_name'] ?? 'TECSISA',
            'company_logo'         => $settings['company_logo'] ?? null,
            'company_footer'       => $settings['company_footer'] ?? 'Reporte de Operaciones Tecnológicas',
        ];

        $pdf = Pdf::loadView('reports.weekly_pdf_template', $reportData)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isRemoteEnabled'   => false,
                'isHtml5ParserEnabled' => true,
                'defaultFont'       => 'DejaVu Sans',
                'dpi'               => 150,
                'chroot'            => storage_path('app/public'),
            ]);
        $filename = 'REPORTE-TECNICO-' . strtoupper($systemName) . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportInternal(Request $request) 
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        $query = Task::with(['equipment.rack', 'system', 'assignee'])
            ->whereIn('status', ['draft', 'pending', 'in_progress', 'in_review', 'completed', 'verified'])
            ->orderBy('updated_at', 'desc');

        if ($request->filled('system_id')) {
            $query->where(function($q) use ($request) {
                $q->where('system_id', $request->system_id)
                  ->orWhereHas('equipment', function($sq) use ($request) {
                      $sq->where('system_id', $request->system_id);
                  });
            });
        }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('building')) { $query->where('form_data->building', 'LIKE', '%' . $request->building . '%'); }
        if ($request->filled('technician_id')) { $query->where('assigned_to', $request->technician_id); }
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $filename = "TECSISA_Produccion_" . date('Y_m_d_H_i') . ".xlsx";
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\TasksExport($query), 
            $filename
        );
    }
}
