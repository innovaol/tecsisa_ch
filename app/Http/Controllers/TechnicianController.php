<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class TechnicianController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get tasks: If Admin, show all. If Technician, show only assigned.
        $query = Task::with(['equipment.location', 'assignee'])
            ->whereIn('status', ['draft', 'pending', 'in_progress', 'in_review'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc');

        $completedQuery = Task::whereIn('status', ['completed', 'verified'])
            ->orderBy('completed_at', 'desc')
            ->take(5);

        if (!$user->hasRole('Administrador')) {
            $query->where('assigned_to', $user->id);
            $completedQuery->where('assigned_to', $user->id);
        }

        $tasks = $query->get();
        $completedTasks = $completedQuery->get();

        return view('technician.dashboard', compact('tasks', 'completedTasks'));
    }

    public function showTask(Task $task)
    {
        // Ensure the task belongs to the user or user is admin
        if ($task->assigned_to !== Auth::id() && !Auth::user()->hasRole('Administrador')) {
            abort(403);
        }

        $task->load('equipment.location', 'equipment.system');

        return view('technician.task', compact('task'));
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed,in_review',
            'form_data' => 'nullable|array'
        ]);

        $isAdmin = Auth::user()->hasRole('Administrador');
        
        // If tech finishes, it goes to review.
        if ($validated['status'] === 'completed' && !$isAdmin) {
            $task->status = 'in_review';
            $message = 'Tarea enviada a revisión.';
        } else {
            $task->status = $validated['status'];
            $message = 'Tarea actualizada correctamente.';
        }

        if (isset($validated['form_data'])) {
            $task->form_data = $validated['form_data'];
        }

        if ($task->status === 'completed') {
            $task->completed_at = now();
            if ($task->equipment) {
                $task->equipment->update([
                    'status' => 'operative',
                    'last_maintenance_at' => now(),
                    'next_maintenance_at' => now()->addMonths(6)
                ]);
            }
        }

        $task->save();

        return redirect()->route('technician.dashboard')->with('success', $message);
    }

    public function infrastructureHub()
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }
        return view('technician.infrastructure_hub');
    }

    public function systemsList()
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }
        $systems = \App\Models\System::withCount('equipments')->get();
        return view('technician.systems_list', compact('systems'));
    }

    public function locationsList()
    {
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403);
        }
        $locations = \App\Models\Location::withCount('equipments')->get();
        return view('technician.locations_list', compact('locations'));
    }
}
