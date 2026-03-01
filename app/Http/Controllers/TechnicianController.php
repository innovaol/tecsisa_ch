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

        // Get tasks assigned to this technician
        $tasks = Task::with('equipment.location')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['draft', 'pending', 'in_progress'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $completedTasks = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get();

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
            'status' => 'required|in:in_progress,completed',
            'form_data' => 'nullable|array'
        ]);

        $task->status = $validated['status'];

        if (isset($validated['form_data'])) {
            $task->form_data = $validated['form_data'];
        }

        if ($validated['status'] === 'completed') {
            $task->completed_at = now();
            // Optional: Also update the equipment 'status' to operative or schedule next maintenance
            if ($task->equipment) {
                // simple simulated logic
                $task->equipment->update([
                    'status' => 'operative',
                    'last_maintenance_at' => now(),
                    // add 6 months for next maintenance
                    'next_maintenance_at' => now()->addMonths(6)
                ]);
            }
        }

        $task->save();

        return redirect()->route('technician.dashboard')->with('success', 'Tarea actualizada correctamente.');
    }
}
