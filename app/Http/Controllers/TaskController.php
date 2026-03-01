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
     * Display a listing of the user's tasks.
     */
    public function index()
    {
        // Get tasks assigned to the current user
        $tasks = Task::where('assigned_to', Auth::id())
            ->with(['equipment.system', 'equipment.location'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tasks.index', compact('tasks'));
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
            'form_data' => 'nullable|array',
        ]);

        $task = Task::create([
            'equipment_id' => $validated['equipment_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'task_type' => $validated['task_type'],
            'assigned_to' => Auth::id(), // Automatically assign to creator for now
            'status' => 'draft', // Initial state
            'form_data' => $validated['form_data'] ?? [],
        ]);

        return redirect()->route('tasks.edit', $task)->with('success', 'Borrador de tarea creado. Proceda a llenar el formulario técnico.');
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
        ]);

        // Update basic task info
        if ($request->has('description')) {
            $task->description = $validated['description'];
        }

        // Merge existing form data with new
        $currentData = $task->form_data ?? [];
        $newData = $validated['form_data'] ?? [];
        $mergedData = array_merge($currentData, $newData);

        $task->form_data = $mergedData;

        if ($validated['action'] === 'submit') {
            $task->status = 'pending'; // Moves from draft to pending verification
            $task->save();
            return redirect()->route('technician.dashboard')->with('success', 'Tarea enviada para revisión final.');
        }
        else {
            // Just saving draft
            $task->save();
            return back()->with('success', 'Borrador guardado correctamente. Puedes continuar luego.');
        }
    }
}
