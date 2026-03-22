<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System;

class CoreSystemsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Los "Hubs" (Patch Panels, Switches)
        System::updateOrCreate(
            ['slug' => 'NET-HUB'],
            [
                'name' => 'Dispositivos de Red (Paneles/Switches)',
                'is_core' => true,
                'form_schema' => [
                    'specs' => [
                        ['label' => 'Marca / Fabricante', 'type' => 'text'],
                        ['label' => 'Modelo / Serie', 'type' => 'text'],
                    ]
                ]
            ]
        );

        // 2. Los "Outlets" (Cajillas, Rosetas)
        System::updateOrCreate(
            ['slug' => 'NET-OUTLET'],
            [
                'name' => 'Puntos de Red (Cajillas/Rosetas)',
                'is_core' => true,
                'form_schema' => [
                    'specs' => [
                        ['label' => 'Color de Identificación', 'type' => 'text'],
                    ]
                ]
            ]
        );

        // 3. Los "Links" (Cables o Tramos)
        System::updateOrCreate(
            ['slug' => 'NET-LINK'],
            [
                'name' => 'Cableado Estructurado (Enlaces)',
                'is_core' => true,
                'form_schema' => [
                    'specs' => [
                        ['label' => 'Estándar del Cable', 'type' => 'select', 'options' => 'Cat 6, Cat 6A, Fibra OM3, Fibra OM4'],
                    ]
                ]
            ]
        );
    }
}
