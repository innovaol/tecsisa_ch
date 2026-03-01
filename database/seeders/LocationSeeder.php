<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un edificio principal
        $edificioPrincipal = Location::create([
            'name' => 'Edificio Principal Hospital',
            'level' => 'edificio',
            'parent_id' => null,
        ]);

        // Crear pisos dentro del edificio
        $piso1 = Location::create([
            'name' => 'Piso 1 - Recepción y Urgencias',
            'level' => 'piso',
            'parent_id' => $edificioPrincipal->id,
        ]);

        $piso2 = Location::create([
            'name' => 'Piso 2 - Quirófanos',
            'level' => 'piso',
            'parent_id' => $edificioPrincipal->id,
        ]);

        // Crear áreas (cuartos de telecomunicaciones, etc.)
        Location::create([
            'name' => 'Cuarto de Telecomunicaciones Principal (MDF)',
            'level' => 'area',
            'parent_id' => $edificioPrincipal->id,
        ]);

        Location::create([
            'name' => 'Sala de Espera Urgencias',
            'level' => 'area',
            'parent_id' => $piso1->id,
        ]);

        Location::create([
            'name' => 'IDF Piso 2',
            'level' => 'area',
            'parent_id' => $piso2->id,
        ]);

        Location::create([
            'name' => 'Pasillo Central',
            'level' => 'area',
            'parent_id' => $edificioPrincipal->id,
        ]);
    }
}
