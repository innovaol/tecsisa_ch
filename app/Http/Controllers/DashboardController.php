<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipment;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {
            $equipos_operativos = Equipment::where('status', 'operative')->count();
            $trabajos_pendientes = Task::where('status', '!=', 'completed')->count();
            $cable_instalado = "5,420"; 
            $recent_activity = Task::with(['equipment.location'])->orderBy('updated_at', 'desc')->take(5)->get();
        } else {
            // Technician Specific Stats
            $equipos_operativos = Task::where('assigned_to', $user->id)->where('status', 'completed')->count();
            $trabajos_pendientes = Task::where('assigned_to', $user->id)->where('status', '!=', 'completed')->count();
            $cable_instalado = Task::where('assigned_to', $user->id)->count(); 
            $recent_activity = Task::with(['equipment.location'])->where('assigned_to', $user->id)->orderBy('updated_at', 'desc')->take(5)->get();
        }

        return view('dashboard', compact('equipos_operativos', 'trabajos_pendientes', 'cable_instalado', 'recent_activity'));
    }
}
