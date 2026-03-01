<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Location;
use App\Models\System;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mdf = Location::where('name', 'Cuarto de Telecomunicaciones Principal (MDF)')->first();
        $urgencias = Location::where('name', 'Sala de Espera Urgencias')->first();
        $quir = Location::where('name', 'Piso 2 - Quirófanos')->first(); // A un nivel más alto como ej.
        $idfPiso2 = Location::where('name', 'IDF Piso 2')->first();

        $cctv = System::where('name', 'CCTV')->first();
        $vozip = System::where('name', 'Voz y Datos')->first();

        // Switch de Cobre y Fibra
        Equipment::create([
            'internal_id' => 'SW-MDF-001',
            'serial_number' => 'FOC2345678',
            'name' => 'Switch Core Principal',
            'system_id' => $vozip->id,
            'location_id' => $mdf->id,
            'status' => 'operative',
            'notes' => 'Concentra los enlaces de fibra desde los IDFs',
            'specs' => [
                'marca_switch' => 'Cisco Catalyst 9300',
                'puertos_cobre' => '24',
                'puertos_fibra' => '4',
                'velocidad_enlace' => '10 Gbps',
            ]
        ]);

        // Cámara en Urgencias
        Equipment::create([
            'internal_id' => 'CAM-URG-001',
            'serial_number' => '801325136883',
            'name' => 'Cámara PTZ Sala Espera',
            'system_id' => $cctv->id,
            'location_id' => $urgencias->id,
            'status' => 'operative',
            'notes' => 'Cámara cubriendo entradas principales de emergencia.',
            'specs' => [
                'marca_camara' => 'Hikvision',
                'modelo_camara' => 'PTZ-4K-Series',
                'resolucion' => '8MP (4K)',
                'tipo_lente' => 'Varifocal Automático',
            ]
        ]);

        // Cámara Pasillo
        Equipment::create([
            'internal_id' => 'CAM-QRO-001',
            'serial_number' => 'DAH112233',
            'name' => 'Cámara Fija Quirófanos',
            'system_id' => $cctv->id,
            'location_id' => $quir->id,
            'status' => 'under_maintenance',
            'notes' => 'Lente rayado, en reparación',
            'specs' => [
                'marca_camara' => 'Dahua',
                'modelo_camara' => 'Bullet-2M',
                'resolucion' => '2MP',
                'tipo_lente' => 'Fijo 2.8mm',
            ]
        ]);

        // Switch IDF
        Equipment::create([
            'internal_id' => 'SW-IDF2-001',
            'serial_number' => 'ARU554433',
            'name' => 'Switch Distribución Piso 2',
            'system_id' => $vozip->id,
            'location_id' => $idfPiso2->id,
            'status' => 'operative',
            'notes' => 'Enlace de cobre blindado hacia cámaras.',
            'specs' => [
                'marca_switch' => 'Aruba',
                'puertos_cobre' => '48',
                'puertos_fibra' => '2',
                'velocidad_enlace' => '10/100/1000 Mbps',
            ]
        ]);
    }
}
