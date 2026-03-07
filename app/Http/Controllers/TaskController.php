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
                'in_review' => $allTasks->where('status', 'in_review')->count(),
                'completed' => $allTasks->whereIn('status', ['completed', 'verified'])->count(),
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
                'in_review' => $myTasks->where('status', 'in_review')->count(),
                'completed' => $myTasks->whereIn('status', ['completed', 'verified'])->count(),
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

        return redirect()->route('tasks.edit', $task)->with('success', 'Reporte de ' . $task->title . ' iniciado correctamente.');
    }

    /**
     * Show the form for editing/filling out the task (The Technical Form)
     */
    public function edit(Task $task)
    {
        // Ensure user can only edit their own tasks or is admin
        if ($task->assigned_to != Auth::id() && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'No autorizado para ver esta tarea.');
        }

        $task->load('equipment.system', 'equipment.location');
        $users = Auth::user()->hasRole('Administrador') ? \App\Models\User::all(['id', 'name']) : [];

        // This is where the magic happens: Getting the JSON schema from the System
        $formSchema = $task->equipment->system->form_schema ?? [];

        return view('tasks.edit', compact('task', 'formSchema', 'users'));
    }

    /**
     * Update the task (Save draft or submit)
     */
    public function update(Request $request, Task $task)
    {
        if ($task->assigned_to != Auth::id() && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'form_data' => 'nullable|array',
            'action' => 'required|in:save_draft,submit,approve,reject,reassign',
            'assigned_to' => 'nullable|exists:users,id',
            'review_comment' => 'nullable|string',
            'photos.*' => 'nullable|image|max:5120', // Max 5MB per photo
        ]);

        $isAdmin = Auth::user()->hasRole('Administrador');

        // REASSIGNMENT (Admin Only)
        if ($isAdmin && $request->filled('assigned_to')) {
            $task->assigned_to = $validated['assigned_to'];
        }

        // Merge existing form data with new
        $mergedData = array_merge($task->form_data ?? [], $validated['form_data'] ?? []);
        
        if ($request->filled('review_comment')) {
            $mergedData['review_comment'] = $validated['review_comment'];
        }

        // HANDLE PHOTO UPLOADS (BEFORE, AFTER, FLUKE)
        $photoKeys = ['before', 'after', 'fluke_screen'];
        $photoPaths = $mergedData['photos'] ?? [];
        foreach ($photoKeys as $key) {
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
            $uploadedFile = $findingPhotos[$index] ?? $findingPhotosCapture[$index] ?? null;
            if ($uploadedFile) {
                $path = $uploadedFile->store('tasks/' . $task->id . '/findings', 'public');
            }
            if ($path || !empty($caption)) {
                $findings[] = ['photo' => $path, 'caption' => $caption];
            }
        }
        $mergedData['findings'] = $findings;

        // HANDLE MATERIALS
        $materials = [];
        $materialNames = $request->input('material_names', []);
        $materialQtys = $request->input('material_qtys', []);
        foreach ($materialNames as $index => $name) {
            if (!empty($name)) {
                $materials[] = ['name' => $name, 'qty' => $materialQtys[$index] ?? 1];
            }
        }
        $mergedData['materials'] = $materials;

        // Update task model
        $task->description = $validated['description'] ?? $task->description;
        $task->form_data = $mergedData;

        // Auto-set started_at
        if (!$task->started_at && $validated['action'] !== 'reassign') {
            $task->started_at = now();
        }

        // STATE TRANSITIONS
        if ($validated['action'] === 'submit') {
            // If tech submits, goes to review. If admin submits, it's completed immediately.
            $task->status = $isAdmin ? 'completed' : 'in_review';
            if ($isAdmin) $task->completed_at = now();
            $message = $isAdmin ? 'Tarea finalizada directamente.' : 'Reporte enviado a revisión.';
        } 
        elseif ($validated['action'] === 'approve' && $isAdmin) {
            $task->status = 'completed';
            $task->completed_at = now();
            $message = 'Tarea aprobada y finalizada.';
        }
        elseif ($validated['action'] === 'reject' && $isAdmin) {
            $task->status = 'pending'; // Or 'draft'? Technician needs to edit it again.
            $message = 'Tarea rechazada. Se ha notificado al técnico para correcciones.';
        }
        elseif ($validated['action'] === 'save_draft') {
            $task->status = $task->status === 'in_review' ? 'in_review' : 'pending';
            $message = 'Borrador guardado.';
        }
        elseif ($validated['action'] === 'reassign' && $isAdmin) {
             $message = 'Tarea reasignada correctamente.';
        }

        // Sync with Equipment status if completed
        if ($task->status === 'completed' && $task->equipment) {
            $task->equipment->update([
                'status' => 'operative',
                'last_maintenance_at' => now(),
                'next_maintenance_at' => now()->addMonths(6)
            ]);
        }

        $task->save();
        return redirect()->route('tasks.index')->with('success', $message);
    }

    public function destroy(Task $task, Request $request)
    {
        $isAdmin = Auth::user()->hasRole('Administrador');
        $isOwner = $task->assigned_to == Auth::id();
        $equipmentId = $task->equipment_id;

        // Determination of "New/Fresh" task: 
        // 1. Created within the last 30 minutes
        // 2. No work started (started_at is null)
        // 3. No form data filled (form_data findings and materials are empty)
        $isRecentlyCreated = $task->created_at->diffInMinutes(now()) < 30;
        $hasNoProgress = !$task->started_at && empty($task->form_data['findings']) && empty($task->form_data['materials']);
        $isNewTask = $isRecentlyCreated && $hasNoProgress;

        // If it's a new task or the user is admin, allow deletion.
        // If it's an existing task, don't delete, just redirect as "canceled".
        if ($isAdmin || ($isOwner && ($task->status === 'draft' || $isNewTask))) {
            $task->delete();
            $message = 'Intervención descartada y eliminada.';
            $type = 'info';
        } else {
            $message = 'Edición de tarea cancelada. Los datos existentes se mantienen.';
            $type = 'warning';
        }

        if ($request->has('redirect_to_equipment') && $equipmentId) {
            return redirect()->route('technician.scanner.result', $equipmentId)->with($type, $message);
        }

        return redirect()->route('tasks.index')->with($type, $message);
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
