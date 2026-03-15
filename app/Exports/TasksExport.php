<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TasksExport implements FromQuery, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID REPORTE',
            'SISTEMA',
            'FECHA FINALIZACION',
            'EDIFICIO',
            'ÁREA ESPECÍFICA',
            'SECCIÓN',
            'RACK',
            'TAG ACTIVO',
            'DESCRIPCIÓN',
            'TECNICO ASIGNADO',
            'ESTADO',
            'CABLE NUEVO',
            'JACK NUEVO',
            'FACEPLATE NUEVO',
            'CERTIFICADO',
            'OBSERVACIONES TECNICAS'
        ];
    }

    public function map($task): array
    {
        return [
            $task->id,
            $task->system->name ?? 'GENERAL',
            $task->completed_at ? $task->completed_at->format('d/m/Y') : 'PENDIENTE',
            $task->form_data['building'] ?? 'N/A',
            $task->form_data['specific_area'] ?? 'N/A',
            $task->form_data['section'] ?? 'N/A',
            $task->equipment->rack->name ?? 'N/A',
            $task->equipment->internal_id ?? 'N/A',
            $task->title,
            $task->assignee->name ?? 'N/A',
            $this->getStatusLabel($task->status),
            $task->has_new_cable ? 'SI' : 'NO',
            $task->has_new_jack ? 'SI' : 'NO',
            $task->has_new_faceplate ? 'SI' : 'NO',
            $task->is_certified ? 'SI' : 'NO',
            $task->form_data['technician_notes'] ?? ''
        ];
    }

    protected function getStatusLabel($status)
    {
        switch ($status) {
            case 'pending':
            case 'in_progress':
                return 'ACTIVA';
            case 'in_review':
                return 'APROBACIÓN';
            case 'completed':
                return 'FINALIZADA';
            case 'verified':
                return 'APROBADA';
            default:
                return strtoupper($status);
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text with background color
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'] // Tailwind blue-800
                ]
            ],
        ];
    }
}
