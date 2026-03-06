<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\System;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $query = Task::with(['equipment.system', 'equipment.location', 'assignee']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $users = \App\Models\User::all(['id', 'name']);
        $equipments = \App\Models\Equipment::all(['id', 'name', 'internal_id']);

        if (Auth::user()->hasRole('Administrador')) {
            $allTasks = Task::all(); // For global stats
            $tasks = $query->orderBy('created_at', 'desc')->get();
            $stats = [
                'total' => $allTasks->count(),
                'pending' => $allTasks->where('status', 'pending')->count(),
                'in_progress' => $allTasks->where('status', 'in_progress')->count(),
                'completed' => $allTasks->where('status', 'completed')->count(),
            ];
        }
        else {
            $myTasks = Task::where('assigned_to', Auth::id())->get();
            $tasks = $query->where('assigned_to', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
            $stats = [
                'total' => $myTasks->count(),
                'pending' => $myTasks->where('status', 'pending')->count(),
                'in_progress' => $myTasks->where('status', 'in_progress')->count(),
                'completed' => $myTasks->where('status', 'completed')->count(),
            ];
        }

        return view('tasks.index', compact('tasks', 'users', 'equipments', 'stats'));
    }

    /**
     * Show the form for creating a new task manually (if needed, otherwise created via QR/Scanner)
     */
    public function create(Request $request)
    {
        $equipmentId = $request->query('equipment_id');
        $equipment = null;

        if ($equipmentId) {
            $equipment = Equipment::with('system')->findOrFail($equipmentId);
        }

        return view('tasks.create', compact('equipment'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'task_type' => 'required|in:maintenance,replacement,installation',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:draft,pending,in_progress,completed',
            'form_data' => 'nullable|array',
        ]);

        $equipment = Equipment::with('location')->findOrFail($validated['equipment_id']);
        $locationPath = $equipment->location ? $equipment->location->name : 'Sin ubicación';

        // Build full path if possible
        if ($equipment->location && $equipment->location->parent) {
            $locationPath = $equipment->location->parent->name . ' > ' . $locationPath;
        }

        $task = Task::create([
            'equipment_id' => $validated['equipment_id'],
            'location_snapshot' => $locationPath,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'task_type' => $validated['task_type'],
            'assigned_to' => $validated['assigned_to'] ?? Auth::id(),
            'status' => $validated['status'] ?? 'pending',
            'form_data' => $validated['form_data'] ?? [],
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarea ' . $task->title . ' creada correctamente.');
    }

    /**
     * Show the form for editing/filling out the task (The Technical Form)
     */
    public function edit(Task $task)
    {
        // Ensure user can only edit their own tasks or is admin
        if ($task->assigned_to !== Auth::id() && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'No autorizado para ver esta tarea.');
        }

        $task->load('equipment.system', 'equipment.location');

        // This is where the magic happens: Getting the JSON schema from the System
        $formSchema = $task->equipment->system->form_schema ?? [];

        return view('tasks.edit', compact('task', 'formSchema'));
    }

    /**
     * Update the task (Save draft or submit)
     */
    public function update(Request $request, Task $task)
    {
        if ($task->assigned_to !== Auth::id() && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'form_data' => 'nullable|array',
            'action' => 'required|in:save_draft,submit',
            'photos.*' => 'nullable|image|max:5120', // Max 5MB per photo
        ]);

        // Merge existing form data with new
        $mergedData = array_merge($task->form_data ?? [], $validated['form_data'] ?? []);

        // HANDLE PHOTO UPLOADS (BEFORE, AFTER, FLUKE)
        // HANDLE PHOTO UPLOADS (BEFORE, AFTER, FLUKE)
        $photoKeys = ['before', 'after', 'fluke_screen'];
        $photoPaths = $mergedData['photos'] ?? [];
        foreach ($photoKeys as $key) {
            // Check both regular and capture variants
            $file = $request->file("photos.$key") ?? $request->file("photos.{$key}_capture");
            if ($file) {
                $path = $file->store('tasks/' . $task->id, 'public');
                $photoPaths[$key] = $path;
            }
        }
        $mergedData['photos'] = $photoPaths;

        // HANDLE FINDINGS GALLERY (HALLAZGOS)
        $findings = [];
        $findingCaptions = $request->input('finding_captions', []);
        $findingPaths = $request->input('finding_paths', []);
        $findingPhotos = $request->file('finding_photos', []);
        $findingPhotosCapture = $request->file('finding_photos_capture', []);

        foreach ($findingCaptions as $index => $caption) {
            $path = $findingPaths[$index] ?? null;

            // Priority: New upload (Gallery or Camera) over existing path
            $uploadedFile = $findingPhotos[$index] ?? $findingPhotosCapture[$index] ?? null;

            if ($uploadedFile) {
                $path = $uploadedFile->store('tasks/' . $task->id . '/findings', 'public');
            }

            if ($path || !empty($caption)) {
                $findings[] = [
                    'photo' => $path,
                    'caption' => $caption
                ];
            }
        }
        $mergedData['findings'] = $findings;

        // HANDLE MATERIALS
        $materials = [];
        $materialNames = $request->input('material_names', []);
        $materialQtys = $request->input('material_qtys', []);
        foreach ($materialNames as $index => $name) {
            if (!empty($name)) {
                $materials[] = [
                    'name' => $name,
                    'qty' => $materialQtys[$index] ?? 1
                ];
            }
        }
        $mergedData['materials'] = $materials;

        // Update task model
        $task->description = $validated['description'] ?? $task->description;
        $task->form_data = $mergedData;

        // Auto-set started_at if not set and data is coming in
        if (!$task->started_at && !empty($validated['form_data'])) {
            $task->started_at = now();
            $task->initial_status = $request->input('initial_status') ?? 'En revisión';
        }

        if ($validated['action'] === 'submit') {
            $task->status = 'completed';
            $task->completed_at = now();
            $task->final_status = $request->input('final_status') ?? 'Operativo / Finalizado';

            // Sync with Equipment status if needed
            if ($task->equipment) {
                $task->equipment->update([
                    'status' => 'operative',
                    'last_maintenance_at' => now(),
                    'next_maintenance_at' => now()->addMonths(6)
                ]);
            }

            $task->save();
            return redirect()->route('tasks.index')->with('success', 'Tarea completada profesionalmente.');
        }
        else {
            $task->save();
            return redirect()->route('tasks.index')->with('success', 'Avance guardado.');
        }
    }

    public function destroy(Task $task)
    {
        // Admin can delete anything. Technician only their own drafts.
        $isAdmin = Auth::user()->hasRole('Administrador');
        $isOwner = $task->assigned_to === Auth::id();

        if ($isAdmin || ($isOwner && ($task->status === 'draft' || $task->status === 'pending'))) {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Tarea eliminada correctamente.');
        }

        abort(403, 'No tienes permiso para eliminar esta tarea.');
    }

    /**
     * Generate PDF Report for a single task
     */
    public function generatePDF(Task $task)
    {
        // Ensure user is authorized
        if ($task->assigned_to !== Auth::id() && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'No autorizado.');
        }

        $task->load('equipment.system', 'equipment.location', 'assignee');

        $formData = $task->form_data;

        // DomPDF options for image resolution and remote paths if needed
        $pdf = Pdf::loadView('tasks.pdf_template', compact('task', 'formData'))
            ->setPaper('a4', 'portrait');

        $filename = 'Reporte-' . $task->equipment->internal_id . '-' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
