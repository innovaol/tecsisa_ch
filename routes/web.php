<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
            $user = \Illuminate\Support\Facades\Auth::user();

            if ($user->hasRole('Administrador')) {
                $equipos_operativos = \App\Models\Equipment::where('status', 'operative')->count();
                $trabajos_pendientes = \App\Models\Task::where('status', '!=', 'completed')->count();
                $cable_instalado = "5,420";
                $recent_activity = \App\Models\Task::with(['equipment.location'])->orderBy('updated_at', 'desc')->take(5)->get();
            }
            else {
                // Technician Specific Stats
                $equipos_operativos = \App\Models\Task::where('assigned_to', $user->id)->where('status', 'completed')->count();
                $trabajos_pendientes = \App\Models\Task::where('assigned_to', $user->id)->where('status', '!=', 'completed')->count();
                $cable_instalado = \App\Models\Task::where('assigned_to', $user->id)->count(); // Use this slot for "Total Asignadas"
                $recent_activity = \App\Models\Task::with(['equipment.location'])->where('assigned_to', $user->id)->orderBy('updated_at', 'desc')->take(5)->get();
            }

            return view('dashboard', compact('equipos_operativos', 'trabajos_pendientes', 'cable_instalado', 'recent_activity'));
        }
        )->name('dashboard');

        // Módulo Técnico Móvil (Buscador Activos y Tareas)
        Route::middleware('role:Tecnico|Administrador')->prefix('technician')->name('technician.')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\TechnicianController::class , 'dashboard'])->name('dashboard');
            Route::get('/tasks/{task}', [\App\Http\Controllers\TechnicianController::class , 'showTask'])->name('task.show');
            Route::put('/tasks/{task}', [\App\Http\Controllers\TechnicianController::class , 'updateTaskStatus'])->name('task.update');

            Route::get('/scanner', [\App\Http\Controllers\ScannerController::class , 'index'])->name('scanner');
            Route::get('/scanner/result/{equipment}', [\App\Http\Controllers\ScannerController::class , 'showResult'])->name('scanner.result');
            Route::post('/scanner/search', [\App\Http\Controllers\ScannerController::class , 'search'])->name('scanner.search');
            Route::get('/equipment', [\App\Http\Controllers\ScannerController::class , 'equipmentList'])->name('equipment.list');

            // Admin Mobile Hub
            Route::get('/infrastructure', [\App\Http\Controllers\TechnicianController::class , 'infrastructureHub'])->name('infrastructure');
            Route::get('/infrastructure/systems', [\App\Http\Controllers\TechnicianController::class , 'systemsList'])->name('systems');
            Route::get('/infrastructure/locations', [\App\Http\Controllers\TechnicianController::class , 'locationsList'])->name('locations');
        }
        );

        // Módulo Administrador (Catálogos, Racks)
        Route::middleware('role:Administrador')->group(function () {
            Route::get('/catalogos', [\App\Http\Controllers\CatalogController::class , 'index'])->name('catalog.index');
            Route::post('/catalogos/equipment', [\App\Http\Controllers\CatalogController::class , 'storeEquipment'])->name('catalog.equipment.store');
            Route::put('/catalogos/equipment/{equipment}', [\App\Http\Controllers\CatalogController::class , 'updateEquipment'])->name('catalog.equipment.update');
            Route::delete('/catalogos/equipment/{equipment}', [\App\Http\Controllers\CatalogController::class , 'destroyEquipment'])->name('catalog.equipment.destroy');

            Route::post('/catalogos/systems', [\App\Http\Controllers\CatalogController::class , 'storeSystem'])->name('catalog.systems.store');
            Route::put('/catalogos/systems/{system}', [\App\Http\Controllers\CatalogController::class , 'updateSystem'])->name('catalog.systems.update');
            Route::delete('/catalogos/systems/{system}', [\App\Http\Controllers\CatalogController::class , 'destroySystem'])->name('catalog.systems.destroy');

            Route::post('/catalogos/locations', [\App\Http\Controllers\CatalogController::class , 'storeLocation'])->name('catalog.locations.store');
            Route::put('/catalogos/locations/{location}', [\App\Http\Controllers\CatalogController::class , 'updateLocation'])->name('catalog.locations.update');
            Route::delete('/catalogos/locations/{location}', [\App\Http\Controllers\CatalogController::class , 'destroyLocation'])->name('catalog.locations.destroy');

            Route::post('/catalogos/racks', [\App\Http\Controllers\CatalogController::class , 'storeRack'])->name('catalog.racks.store');
            Route::put('/catalogos/racks/{rack}', [\App\Http\Controllers\CatalogController::class , 'updateRack'])->name('catalog.racks.update');
            Route::delete('/catalogos/racks/{rack}', [\App\Http\Controllers\CatalogController::class , 'destroyRack'])->name('catalog.racks.destroy');

            Route::get('/racks', [\App\Http\Controllers\RackBuilderController::class , 'index'])->name('rack.builder');
            Route::post('/racks/{rack}/save', [\App\Http\Controllers\RackBuilderController::class , 'save'])->name('rack.save');

            // User and Role Management
            Route::resource('users', \App\Http\Controllers\UserController::class);

            // Reportes Semanales
            Route::get('/reportes/semanal', [\App\Http\Controllers\ReportController::class , 'index'])->name('reports.weekly.index');
            Route::post('/reportes/semanal/generar', [\App\Http\Controllers\ReportController::class , 'generateWeekly'])->name('reports.weekly.generate');

            // Configuración de la Empresa
            Route::get('/configuracion', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
            Route::patch('/configuracion', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        }
        );

        // Módulo Técnico Móvil (Buscador Activos y Tareas)
        Route::get('/tasks/{task}/pdf', [\App\Http\Controllers\TaskController::class , 'generatePDF'])->name('tasks.pdf');
        Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
