<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System;

class CoreSystemsSeeder extends Seeder
{
    public function run(): void
    {
        $systems = [
            // FILA 1: CONECTIVIDAD E INFRAESTRUCTURA (Voz y Data)
            [
                'slug' => 'DISP-RED',
                'name' => 'Dispositivos de Red',
                'specs' => [
                    ['label' => 'Marca / Fabricante', 'type' => 'text'],
                    ['label' => 'Modelo / Serie', 'type' => 'text'],
                    ['label' => 'Puertos (RJ45/SFP)', 'type' => 'number'],
                    ['label' => 'IP / VLAN / ID', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN DE ESTADO FISICO Y VENTILADORES',
                    'LIMPIEZA DE EQUIPOS Y ASPIRADO DE FILTROS',
                    'VERIFICACIÓN DE CONECTIVIDAD Y ESTADO DE LINK',
                    'REVISIÓN DE ETIQUETADO DE ACTIVO (TAG)',
                    'PRUEBAS DE OPERATIVIDAD EN RED'
                ],
                'requires_certification' => false
            ],
            [
                'slug' => 'FIBRA-OPTICA',
                'name' => 'Fibra Óptica',
                'specs' => [
                    ['label' => 'Tipo (OM3/OM4/OS2)', 'type' => 'text'],
                    ['label' => 'Nro de Hilos', 'type' => 'number'],
                    ['label' => 'Conectores (LC/SC/ST)', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN DE TRAMO Y ETIQUETADO DE FO',
                    'LIMPIEZA DE TERMINALES Y ADAPTADORES',
                    'PRUEBA DE CONTINUIDAD Y ATENUACIÓN',
                    'CERTIFICACIÓN CON EQUIPO FLUKE (ANNEX)'
                ],
                'requires_certification' => true
            ],
            [
                'slug' => 'PUNTOS-RED',
                'name' => 'Puntos de Red',
                'specs' => [
                    ['label' => 'ID de Toma / Nodo', 'type' => 'text'],
                    ['label' => 'Tipo de Jack (Cat6/6A)', 'type' => 'text'],
                    ['label' => 'Ubicación Específica', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN FISICA DE CAJILLA Y JACK',
                    'VERIFICACIÓN DE ETIQUETADO EN FACEPLATE',
                    'PRUEBA DE CONEXIÓN CON EQUIPO FINAL',
                    'CERTIFICACIÓN DE PUNTO (PASS/FAIL)'
                ],
                'requires_certification' => true
            ],
            [
                'slug' => 'ENLACES-RED',
                'name' => 'Enlaces de Red (Patching)',
                'specs' => [
                    ['label' => 'Capacidad Patch Panel', 'type' => 'number'],
                    ['label' => 'Nro de Rack / Gabinete', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN DE ETIQUETADO DE PUERTOS EN PANEL',
                    'PEINADO Y ORGANIZACIÓN DE PATCH CORDS',
                    'LIMPIEZA DE CONTACTOS FRONTALES',
                    'VERIFICACIÓN DE TOMA DE TIERRA EN RACK'
                ],
                'requires_certification' => false
            ],

            // FILA 2: SISTEMAS HOSPITALARIOS Y SEGURIDAD
            [
                'slug' => 'LLAMADO-ENFERMERA',
                'name' => 'Llamado de Enfermera',
                'specs' => [
                    ['label' => 'Modelo de Terminal', 'type' => 'text'],
                    ['label' => 'ID de Estación / Consola', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN DE CABLEADO UTP Y CONTROL',
                    'PRUEBAS DE PULSADOR PERA Y TIRADOR BAÑO',
                    'VERIFICACIÓN DE LUZ CUATRICOLOR POR SALA',
                    'OPERATIVIDAD DE CONSOLA CENTRAL EN ENFERMERÍA',
                    'PRUEBAS DE ALERTA DE EMERGENCIA MÉDICA'
                ],
                'requires_certification' => true
            ],
            [
                'slug' => 'VIDEOVIGILANCIA',
                'name' => 'Videovigilancia',
                'specs' => [
                    ['label' => 'Modelo de Cámara / VMS', 'type' => 'text'],
                    ['label' => 'IP / Grabador NVR', 'type' => 'text'],
                    ['label' => 'Almacenamiento (Días)', 'type' => 'number'],
                ],
                'checklist' => [
                    'LIMPIEZA DE LENTES Y DOMOS PROTECTORES',
                    'VERIFICACIÓN DE ÁNGULO DE VISIÓN Y ENFOQUE',
                    'REVISIÓN DE GRABACIÓN DISPONIBLE EN NVR',
                    'PRUEBA DE CONECTIVIDAD POE / VOLTAJE',
                    'AJUSTE DE SOPORTES Y ANCLAJES'
                ],
                'requires_certification' => false
            ],
            [
                'slug' => 'CTRL-ACCESO',
                'name' => 'Control de Acceso',
                'specs' => [
                    ['label' => 'Modelo de Lector / Biomético', 'type' => 'text'],
                    ['label' => 'Fuerza de Magneto (lb)', 'type' => 'number'],
                    ['label' => 'ID de Controladora / Puerta', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN DE ESTADO FISICO DE LECTORES',
                    'PRUEBA DE APERTURA (TARJETA / BIOMÉTRICO)',
                    'VERIFICACIÓN DE CIERRE (MAGNETOS / STRIKES)',
                    'PRUEBA DE BOTÓN DE SALIDA DE EMERGENCIA',
                    'REVISIÓN DE FUENTES DE PODER Y BATERÍAS'
                ],
                'requires_certification' => false
            ],
            [
                'slug' => 'INCENDIO',
                'name' => 'Sistema de Incendio',
                'specs' => [
                    ['label' => 'Modelo de Panel Central', 'type' => 'text'],
                    ['label' => 'ID de Lazo / Cantidad Dispositivos', 'type' => 'number'],
                ],
                'checklist' => [
                    'PRUEBA DE SENSORES DE HUMO Y CALOR',
                    'REVISIÓN FISICA DE ESTACIONES MANUALES',
                    'PRUEBA DE SIRENAS Y LUCES ESTROBOSCÓPICAS',
                    'VERIFICACIÓN DE BATERÍAS Y PANEL FRONTAL',
                    'LIMPIEZA DE SENSORES CON AIRE'
                ],
                'requires_certification' => false
            ],

            // FILA 3: SERVICIOS Y ENERGÍA
            [
                'slug' => 'SONIDO',
                'name' => 'Sistema de Sonido',
                'specs' => [
                    ['label' => 'Modelo de Bocinas / Zonas', 'type' => 'text'],
                    ['label' => 'Amplificación (Watts)', 'type' => 'number'],
                ],
                'checklist' => [
                    'REVISIÓN DE CABLEADO Y ETIQUETADO DE BOCINAS',
                    'LIMPIEZA E INSPECCIÓN DE CORROSIÓN EN PARRILLAS',
                    'LIMPIEZA Y VENTILACIÓN DE AMPLIFICADORES',
                    'PRUEBAS DE AUDICIÓN Y VOZ POR ZONA',
                    'REVISIÓN DE ATENUADORES DE VOLUMEN'
                ],
                'requires_certification' => true
            ],
            [
                'slug' => 'RELOJES-DIGITALES',
                'name' => 'Relojes Digitales',
                'specs' => [
                    ['label' => 'Modelo de Reloj', 'type' => 'text'],
                    ['label' => 'Método de Sincronización', 'type' => 'text'],
                ],
                'checklist' => [
                    'LIMPIEZA FISICA DEL RELOJ Y ANCLAJE',
                    'SINCRONIZACIÓN DE HORA EXACTA (NTP/SERVER)',
                    'VERIFICACIÓN DE ALIMENTACIÓN Y BATERÍA INTERNA',
                    'PRUEBA DE OPERATIVIDAD DEL SISTEMA'
                ],
                'requires_certification' => false
            ],
            [
                'slug' => 'ENERGIA-UPS',
                'name' => 'UPS y Energía',
                'specs' => [
                    ['label' => 'KVA / Potencia Nominal', 'type' => 'number'],
                    ['label' => 'Voltajes In / Out (V)', 'type' => 'text'],
                    ['label' => 'Nro. de Baterías / Tipo', 'type' => 'text'],
                ],
                'checklist' => [
                    'LIMPIEZA DE EQUIPOS Y SOPLADO DE POLVO',
                    'MEDICIÓN DE VOLTAJES Y AMPERAJES',
                    'REVISIÓN DE ESTADO FÍSICO DE BATERÍAS (SULFATO)',
                    'PRUEBA DE TRANSFERENCIA / BYPASS',
                    'LOG DE ALARMAS EN PANTALLA LCD'
                ],
                'requires_certification' => false
            ],
            [
                'slug' => 'CANALIZACION',
                'name' => 'Infraestructura y Canalización',
                'specs' => [
                    ['label' => 'Tipo (Bandeja/Tubería/EMT)', 'type' => 'text'],
                    ['label' => 'Tramo / Área Cubierta', 'type' => 'text'],
                ],
                'checklist' => [
                    'REVISIÓN DE SUJECIÓN Y SOPORTES',
                    'LIMPIEZA DE BANDEJAS Y CABLES EN PASILLOS',
                    'VERIFICACIÓN DE RADIOS DE CURVATURA',
                    'REVISIÓN DE ARRIOSTRES Y TAPAS DE REGISTRO',
                    'ORGANIZACIÓN GENERAL DEL CABLEADO EXTERNO'
                ],
                'requires_certification' => false
            ],
        ];

        foreach ($systems as $data) {
            $sys = System::where('slug', $data['slug'])->first() 
                   ?? System::where('name', $data['name'])->first() 
                   ?? new System();
            
            $sys->name = $data['name'];
            $sys->slug = $data['slug'];
            $sys->is_core = true;
            $sys->form_schema = [
                'specs' => $data['specs'],
                'checklist' => $data['checklist'],
                'features' => ['requires_certification' => $data['requires_certification']]
            ];
            $sys->save();
        }
    }
}
