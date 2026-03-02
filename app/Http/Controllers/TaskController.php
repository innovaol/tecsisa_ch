<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\System;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $query = Task::with(['equipment.system', 'equipment.location', 'assignee']);
        $users = collect();
        $equipments = collect();

        if (Auth::user()->hasRole('Administrador')) {
            // Admin sees everything
            $tasks = $query->orderBy('created_at', 'desc')->get();
            $users = \App\Models\User::all(['id', 'name']);
            $equipments = \App\Models\Equipment::all(['id', 'name', 'internal_id']);
        }
        else {
            // Technicians only see their assignments
            $tasks = $query->where('assigned_to', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('tasks.index', compact('tasks', 'users', 'equipments'));
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

        $task = Task::create([
            'equipment_id' => $validated['equipment_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'task_type' => $validated['task_type'],
            'assigned_to' => $validated['assigned_to'] ?? Auth::id(), // Allow admins to set assignee
            'status' => $validated['status'] ?? 'pending', // Admins probably want directly pending tasks
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

        // HANDLE PHOTO UPLOADS
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $photoPaths = $mergedData['photos'] ?? [];

            foreach ($photos as $key => $file) {
                // Key can be 'before', 'after', etc.
                $path = $file->store('tasks/' . $task->id, 'public');
                $photoPaths[$key] = $path;
            }
            $mergedData['photos'] = $photoPaths;
        }

        // Update task model
        $task->description = $validated['description'] ?? $task->description;
        $task->form_data = $mergedData;

        if ($validated['action'] === 'submit') {
            $task->status = 'pending';
            $task->completed_at = now();
            $task->save();
            return redirect()->route('technician.scanner.result', $task->equipment_id)->with('success', 'Tarea completada profesionalmente.');
        }
        else {
            $task->save();
            return redirect()->route('technician.scanner.result', $task->equipment_id)->with('success', 'Avance guardado.');
        }
    }
}
