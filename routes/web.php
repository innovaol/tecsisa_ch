<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
            $equipos_operativos = \App\Models\Equipment::where('status', 'operative')->count();
            // Since we don't have tickets/tasks yet, let's hardcode or calculate maintenance
            $trabajos_pendientes = \App\Models\Equipment::where('status', 'under_maintenance')->count();
            // Fiber/Cobre total length (Just a simulated aggregate for now since we don't have links yet)
            $cable_instalado = "5,420";

            return view('dashboard', compact('equipos_operativos', 'trabajos_pendientes', 'cable_instalado'));
        }
        )->name('dashboard');

        Route::get('/catalogos', [\App\Http\Controllers\CatalogController::class , 'index'])->name('catalog.index');
        Route::post('/catalogos/equipment', [\App\Http\Controllers\CatalogController::class , 'storeEquipment'])->name('catalog.equipment.store');
        Route::put('/catalogos/equipment/{equipment}', [\App\Http\Controllers\CatalogController::class , 'updateEquipment'])->name('catalog.equipment.update');
        Route::delete('/catalogos/equipment/{equipment}', [\App\Http\Controllers\CatalogController::class , 'destroyEquipment'])->name('catalog.equipment.destroy');

        Route::get('/racks', [\App\Http\Controllers\RackBuilderController::class , 'index'])->name('rack.builder');
        Route::post('/racks/{rack}/save', [\App\Http\Controllers\RackBuilderController::class , 'save'])->name('rack.save');
        Route::get('/api/equipment/{equipment}/ports', [\App\Http\Controllers\RackBuilderController::class , 'getEquipmentPorts'])->name('api.equipment.ports');
        Route::post('/api/connections', [\App\Http\Controllers\RackBuilderController::class , 'connectPorts'])->name('api.connections.store');
        Route::delete('/api/connections/{connection}', [\App\Http\Controllers\RackBuilderController::class , 'disconnectConnection'])->name('api.connections.destroy');

        // Módulo Técnico Móvil (Buscador Activos y Tareas)
        Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
