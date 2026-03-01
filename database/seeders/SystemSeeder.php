<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        System::create([
            'name' => 'CCTV',
            'form_schema' => [
                ['name' => 'marca_camara', 'label' => 'Marca de Cámara', 'type' => 'text', 'required' => true],
                ['name' => 'modelo_camara', 'label' => 'Modelo', 'type' => 'text', 'required' => true],
                ['name' => 'resolucion', 'label' => 'Resolución', 'type' => 'select', 'options' => ['2MP', '4MP', '8MP (4K)'], 'required' => false],
                ['name' => 'tipo_lente', 'label' => 'Tipo de Lente', 'type' => 'text', 'required' => false],
            ]
        ]);

        System::create([
            'name' => 'Voz y Datos',
            'form_schema' => [
                ['name' => 'marca_switch', 'label' => 'Marca del Switch/Router', 'type' => 'text', 'required' => true],
                ['name' => 'puertos_cobre', 'label' => 'Puertos de Cobre (RJ45)', 'type' => 'number', 'required' => true],
                ['name' => 'puertos_fibra', 'label' => 'Puertos de Fibra (SFP/SFP+)', 'type' => 'number', 'required' => true],
                ['name' => 'velocidad_enlace', 'label' => 'Velocidad de Enlace', 'type' => 'select', 'options' => ['10/100/1000 Mbps', '10 Gbps', '40 Gbps'], 'required' => true],
            ]
        ]);

        System::create([
            'name' => 'Control de Acceso',
            'form_schema' => [
                ['name' => 'tipo_lectora', 'label' => 'Tipo de Lectora', 'type' => 'select', 'options' => ['Biométrica', 'Tarjeta RFID', 'Teclado numérico', 'Mixta'], 'required' => true],
                ['name' => 'marca', 'label' => 'Marca del Panel/Lectora', 'type' => 'text', 'required' => true],
            ]
        ]);

        System::create([
            'name' => 'Detección de Incendios',
            'form_schema' => [
                ['name' => 'tipo_sensor', 'label' => 'Tipo de Sensor', 'type' => 'select', 'options' => ['Humo', 'Calor', 'Multicriterio'], 'required' => true],
                ['name' => 'direccionable', 'label' => 'Es direccionable?', 'type' => 'boolean', 'required' => true],
            ]
        ]);
    }
}
