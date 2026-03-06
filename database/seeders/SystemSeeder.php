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
        // 1. VOZ Y DATOS
        System::create([
            'name' => 'Voz y Datos',
            'form_schema' => [
                'checklist' => [
                    'CABLEADO (UTP): REVISIÓN DE ESTADO FISICO, ETIQUETADO Y CERTIFICACIÓN',
                    'CABLEADO (FO): REVISIÓN DE ESTADO FISICO, ETIQUETADO, LIMPIEZA Y CERTIFICACIÓN',
                    'CANALIZACION: REVISION DE LA SUJECCION, LIMPIEZA Y ORGANIZACIÓN',
                    'GABINETES (RACKS): LIMPIEZA Y REVISION DEL ESTADO DE LOS ELEMENTOS DEL GABINETE',
                    'GABINETES (RACKS): LIMPIEZA Y REVISION DE VENTILADORES Y PDU',
                    'PANEL DE CONEXIÓN (PATCH PANEL): INSPECCION DE LOS PUNTOS DE TOMA DE DATOS',
                    'GABINETES (RACKS): VERIFICAR EL APRIETE EN LAS TOMAS DE LA BARRA DE TIERRA',
                    'REEMPLAZO FISICO DE SWITCHES DE 48 Y 24 PUERTOS',
                    'REEMPLAZO FISICO DE TELEFONOS IP',
                    'REEMPLAZO FISICO DE ACCESS POINT (AP)'
                ],
                'specs' => [
                    ['name' => 'marca_switch', 'label' => 'Marca del Switch/Router', 'type' => 'text'],
                    ['name' => 'puertos_cobre', 'label' => 'Puertos de Cobre (RJ45)', 'type' => 'number'],
                    ['name' => 'puertos_fibra', 'label' => 'Puertos de Fibra (SFP/SFP+)', 'type' => 'number'],
                ]
            ]
        ]);

        // 2. CONTROL DE ACCESO
        System::create([
            'name' => 'Control de Acceso',
            'form_schema' => [
                'checklist' => [
                    'REMOCIÓN DE EQUIPOS DE CONTROL DE ACCESO EXISTENTES',
                    'CABLEADO: REVISION DE ESTADO FISICO Y FUNCIONAMIENTO',
                    'CAMBIO DE LECTORES DE PROXIMIDAD (ENTRADAS Y SALIDAS)',
                    'INSTALACIÓN DE MAGNETOS SIMPLES DE 600LB (INCLUYE BRACKETS)',
                    'INSTALACIÓN DE MAGNETOS DOBLES DE 600LB (INCLUYE BRACKETS)',
                    'INSTALACIÓN DE BOTÓN DE PULSAR PARA SALIDA DE PUERTA',
                    'INSTALACIÓN DE BOTÓN DE SALIDA DE PULSAR PARA ESCRITORIO',
                    'INSTALACIÓN DE PANEL DE CONTROL DE ACCESO DE 4 PUERTAS',
                    'INSTALACIÓN DE FUENTES DE PODER'
                ]
            ]
        ]);

        // 3. SONIDO
        System::create([
            'name' => 'Sonido',
            'form_schema' => [
                'checklist' => [
                    'CABLEADO: REVISION DE ESTADO FISICO, ETIQUETADO Y CERTIFICACIÓN',
                    'VERIFICACIÓN, LIMPIEZA E IDENTIFICACIÓN DE BOCINAS',
                    'VERIFICACIÓN, LIMPIEZA E IDENTIFICACIÓN DE AMPLIFICADORES Y CONTROLADORAS.',
                    'INSTALACIÓN DE CABLEADO PARA BOCINAS',
                    'INSTALACIÓN O REEMPLAZO DE BOCINAS',
                    'REALIZAR PRUEBAS DE OPERATIVIDAD DEL SISTEMA DE SONIDO'
                ]
            ]
        ]);

        // 4. LLAMADO DE ENFERMERA
        System::create([
            'name' => 'Llamado de Enfermera',
            'form_schema' => [
                'checklist' => [
                    'CABLEADO (UTP): REVISIÓN DE ESTADO FISICO, ETIQUETADO Y CERTIFICACIÓN',
                    'INSTALACION Y ARMADO DE CABLEADO DE CONTROL DE 4 NÚCLEOS',
                    'INSTALACIÓN Y ARMADO DE TERMINAL CON PANTALLA + PULSADOR PERA',
                    'INSTALACIÓN Y ARMADO DE TIRADOR DE ALARMA PARA BAÑO + CORDON',
                    'INSTALACIÓN Y ARMADO DE LUZ CUATRICOLOR',
                    'INSTALACIÓN DE CONSOLA DE ESTACIÓN DE ENFERMERÍA',
                    'DESINSTALACIÓN DE EQUIPOS DE LLAMADO DE ENFERMERA',
                    'PRUEBAS DE OPERATIVIDAD DEL SISTEMA DE LLAMADO DE ENFERMERA'
                ]
            ]
        ]);

        // 5. RELOJES (TIEMPO)
        System::create([
            'name' => 'Relojes / Tiempo',
            'form_schema' => [
                'checklist' => [
                    'VERIFICACIÓN, LIMPIEZA E IDENTIFICACIÓN DE RELOJES',
                    'SINCRONIZACIÓN DE HORA EN LOS RELOJES',
                    'PRUEBA DE OPERATIVIDAD DEL SISTEMA DE TIEMPO'
                ]
            ]
        ]);
    }
}
