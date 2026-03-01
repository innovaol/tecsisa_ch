<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
            if (\Illuminate\Support\Facades\Auth::user()->hasRole('Tecnico')) {
                return redirect()->route('technician.dashboard');
            }

            $equipos_operativos = \App\Models\Equipment::where('status', 'operative')->count();
            // Since we don't have tickets/tasks yet, let's hardcode or calculate maintenance
            $trabajos_pendientes = \App\Models\Equipment::where('status', 'under_maintenance')->count();
            // Fiber/Cobre total length (Just a simulated aggregate for now since we don't have links yet)
            $cable_instalado = "5,420";

            return view('dashboard', compact('equipos_operativos', 'trabajos_pendientes', 'cable_instalado'));
        }
        )->name('dashboard');

        // Módulo Técnico Móvil (Buscador Activos y Tareas)
        Route::middleware('role:Tecnico|Administrador')->prefix('technician')->name('technician.')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\TechnicianController::class , 'dashboard'])->name('dashboard');
            Route::get('/tasks/{task}', [\App\Http\Controllers\TechnicianController::class , 'showTask'])->name('task.show');
            Route::put('/tasks/{task}', [\App\Http\Controllers\TechnicianController::class , 'updateTaskStatus'])->name('task.update');
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
        }
        );

        // Módulo Técnico Móvil (Buscador Activos y Tareas)
        Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
