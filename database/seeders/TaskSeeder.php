<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vozip = \App\Models\System::where('name', 'Voz y Datos')->first();
        $cctv = \App\Models\System::where('name', 'CCTV')->first();

        $swMdf = \App\Models\Equipment::where('internal_id', 'SW-MDF-001')->first();
        $camUrg = \App\Models\Equipment::where('internal_id', 'CAM-URG-001')->first();
        $admin = \App\Models\User::first();

        // 1. Tarea de Mantenimiento Preventivo (Voz y Datos)
        \App\Models\Task::create([
            'equipment_id' => $swMdf->id,
            'title' => 'Mantenimiento Preventivo Trimestral Rack Principal',
            'description' => 'Se realizó limpieza integral del gabinete, aspirado de filtros y peinado de patchcords frontales.',
            'priority' => 'medium',
            'task_type' => 'maintenance',
            'assigned_to' => $admin->id,
            'status' => 'completed',
            'location_snapshot' => 'SÓTANO > CUARTO MDF',
            'started_at' => now()->subHours(5),
            'completed_at' => now()->subHours(2),
            'form_data' => [
                'required_installations' => 'Se requiere instalar barra de tierra física dedicada para el rack #15. Actualmente está derivado a un borne de AC.',
                'findings' => [
                    ['caption' => 'Exceso de polvo en ventiladores superiores', 'photo' => 'tasks/seed/finding1.jpg'],
                    ['caption' => 'Patchcords sin etiqueta de destino en switch core', 'photo' => 'tasks/seed/finding2.jpg']
                ],
                'materials' => [
                    ['name' => 'Aire comprimido', 'qty' => 1],
                    ['name' => 'Etiquetas térmicas 1x2', 'qty' => 24]
                ],
                'maint_clean' => '1',
                'maint_cables' => '1',
                'maint_tags' => '1'
            ]
        ]);

        // 2. Tarea de Instalación Expres (Voz y Datos)
        \App\Models\Task::create([
            'equipment_id' => $swMdf->id,
            'title' => 'Migración de Enlace de Fibra a Puerto SFP+ #4',
            'description' => 'Habilitación de puerto 10G para nuevo enlace hacia consultorios.',
            'priority' => 'high',
            'task_type' => 'installation',
            'assigned_to' => $admin->id,
            'status' => 'completed',
            'location_snapshot' => 'SÓTANO > CUARTO MDF',
            'started_at' => now()->subDays(1)->subHours(3),
            'completed_at' => now()->subDays(1),
            'form_data' => [
                'required_installations' => 'Faltan protectores contra transientes en la acometida de fibra.',
                'install_test' => 'PASS',
                'materials' => [
                    ['name' => 'Transceiver SFP+ 10G', 'qty' => 1],
                    ['name' => 'Jumper Fibra LC-LC 3m', 'qty' => 1]
                ]
            ]
        ]);

        // 3. Tarea de Sustitución (CCTV)
        \App\Models\Task::create([
            'equipment_id' => $camUrg->id,
            'title' => 'Cambio de Cámara PTZ por Falla de Sensor',
            'description' => 'La cámara original presentaba ruido térmico en imagen nocturna. Se instala unidad nueva funcional.',
            'priority' => 'critical',
            'task_type' => 'replacement',
            'assigned_to' => $admin->id,
            'status' => 'completed',
            'location_snapshot' => 'PLANTA ALTA > URGENCIAS',
            'started_at' => now()->subDays(2),
            'completed_at' => now()->subDays(2)->addHours(4),
            'form_data' => [
                'new_serial' => 'SN-HK-TEST-9988-2026',
                'required_installations' => 'Sello estanco de la caja de paso está degradado, se debe sellar con silicona industrial.',
                'materials' => [
                    ['name' => 'Conector RJ45 Blindado', 'qty' => 2]
                ]
            ]
        ]);
    }
}
