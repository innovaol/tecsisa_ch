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
                'name' => 'Network Hub / Panel',
                'is_core' => true,
                'form_schema' => [
                    'specs' => [
                        ['label' => 'Manufacturer', 'type' => 'text'],
                        ['label' => 'Model', 'type' => 'text'],
                    ]
                ]
            ]
        );

        // 2. Los "Outlets" (Cajillas, Rosetas)
        System::updateOrCreate(
            ['slug' => 'NET-OUTLET'],
            [
                'name' => 'Network Outlet / Box',
                'is_core' => true,
                'form_schema' => [
                    'specs' => [
                        ['label' => 'Outlet Color', 'type' => 'text'],
                    ]
                ]
            ]
        );

        // 3. Los "Links" (Cables o Tramos)
        System::updateOrCreate(
            ['slug' => 'NET-LINK'],
            [
                'name' => 'Network Link / Cable',
                'is_core' => true,
                'form_schema' => [
                    'specs' => [
                        ['label' => 'Cable Standard', 'type' => 'select', 'options' => 'Cat 6, Cat 6A, Fiber OM3, Fiber OM4'],
                    ]
                ]
            ]
        );
    }
}
