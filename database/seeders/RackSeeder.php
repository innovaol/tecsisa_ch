<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Rack;
use Illuminate\Database\Seeder;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mdf = Location::where('name', 'Cuarto de Telecomunicaciones Principal (MDF)')->first();

        if ($mdf) {
            Rack::create([
                'name' => 'Rack A1 - Core Switches',
                'total_units' => 45,
                'location_id' => $mdf->id,
            ]);

            Rack::create([
                'name' => 'Rack A2 - Distribución Fibra',
                'total_units' => 42,
                'location_id' => $mdf->id,
            ]);
        }
    }
}
