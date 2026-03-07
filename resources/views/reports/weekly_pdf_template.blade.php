<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .page-break {
            page-break-after: always;
        }
        /* Cover Styling */
        .cover {
            text-align: center;
            padding-top: 150px;
        }
        .cover-logo {
            font-size: 40pt;
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .cover-sublogo {
            font-size: 14pt;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 100px;
        }
        .cover-title {
            font-size: 28pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .cover-system {
            font-size: 20pt;
            color: #d97706;
            font-weight: bold;
            margin-bottom: 50px;
        }
        .cover-dates {
            font-size: 12pt;
            color: #666;
        }

        /* Report Styles (Shared with Individual) */
        .report-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .title-box {
            text-align: center;
            background-color: #f3f4f6;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .section-header {
            background-color: #1e293b;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.info-table td, table.info-table th {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 8pt;
        }
        .label {
            background-color: #f9fafb;
            font-weight: bold;
            width: 25%;
        }
        .photo-row {
            margin-bottom: 10px;
            clear: both;
        }
        .photo-box {
            width: 48%;
            float: left;
            margin-right: 2%;
            border: 1px solid #eee;
            padding: 5px;
            text-align: center;
        }
        .photo-img {
            max-width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .photo-caption {
            font-size: 7pt;
            font-weight: bold;
            margin-top: 3px;
            background-color: #f3f4f6;
            padding: 2px;
        }
        .footer {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .signature-box {
            width: 50%;
            text-align: center;
            padding-top: 30px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 70%;
            margin: 0 auto;
            margin-bottom: 3px;
        }
        .signature-name {
            font-size: 8pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- 1. PORTADA -->
    <div class="cover page-break">
        @if($company_logo)
            <img src="{{ public_path('storage/' . $company_logo) }}" style="max-height: 120px; margin-bottom: 20px;">
        @else
            <div class="cover-logo">{{ $company_name }}</div>
        @endif
        <div class="cover-sublogo">{{ $company_footer }}</div>
        
        <div class="cover-title">REPORTE INTEGRADO</div>
        <div class="cover-system">INFRAESTRUCTURA TÉCNICA</div>
        
        <div style="font-size: 18pt; font-weight: bold; margin-bottom: 40px;">
            HOSPITAL CIUDAD HOSPITALARIA
        </div>

        <div class="cover-dates">
            SISTEMA: {{ strtoupper($system->name) }} <br>
            PERIODO: {{ date('d/m/Y', strtotime($start_date)) }} AL {{ date('d/m/Y', strtotime($end_date)) }}
        </div>
    </div>

    <!-- 2. LOOP DE TAREAS -->
    @foreach($tasks as $task)
    <div class="individual-report @if(!$loop->last) page-break @endif">
        <!-- Header Pequeño por página -->
        <table class="report-header">
            <tr>
                <td style="width: 120px;"><strong>{{ $company_name }}</strong></td>
                <td class="title-box">
                   <div style="font-size: 11pt; font-weight: bold;">HOJA DE REPORTE TÉCNICO</div>
                   <div style="font-size: 8pt;">FOLIO: #{{ $task->id }} | SISTEMA: {{ strtoupper($system->name) }}</div>
                </td>
            </tr>
        </table>

        <!-- Datos Area -->
        <div class="section-header">Información del Area y Equipo</div>
        <table class="info-table">
            <tr>
                <td class="label">Ubicación / Equipo:</td>
                <td>{{ $task->location_snapshot }} | <strong>{{ $task->equipment->internal_id }}</strong></td>
                <td class="label">Mantenimiento:</td>
                <td>{{ strtoupper($task->task_type) }}</td>
            </tr>
            <tr>
                <td class="label">Activo:</td>
                <td>{{ $task->equipment->name }}</td>
                <td class="label">Fecha Elaboración:</td>
                <td>{{ $task->completed_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <!-- Evaluación de Actividades -->
        @php $evaluation = $task->form_data['evaluation'] ?? []; @endphp
        @if(count($evaluation) > 0)
        <div class="section-header">Evaluación Técnica de Actividades</div>
        <table class="info-table">
            <thead>
                <tr style="background-color: #f3f4f6;">
                    <th>Actividad / Protocolo</th>
                    <th style="width: 60px; text-align: center;">Cumple</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluation as $item)
                <tr>
                    <td>{{ $item['item'] ?? 'Actividad' }}</td>
                    <td style="text-align: center; color: {{ ($item['status'] ?? '') == 'SI' ? 'green' : 'red' }};">
                        <strong>{{ $item['status'] ?? '-' }}</strong>
                    </td>
                    <td>{{ $item['comment'] ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Insumos Utilisados -->
        @php $materials = $task->form_data['materials'] ?? []; @endphp
        @if(count($materials) > 0)
        <div class="section-header">Materiales e Insumos Utilizados</div>
        <table class="info-table">
            <thead>
                <tr style="background-color: #f3f4f6;">
                    <th>Descripción del Insumo</th>
                    <th style="width: 80px; text-align: center;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $mat)
                <tr>
                    <td>{{ $mat['name'] }}</td>
                    <td style="text-align: center;">{{ $mat['qty'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Evidencia -->
        <div class="section-header">Evidencia Fotográfica</div>
        <div class="photo-row">
            @if(isset($task->form_data['photos']['before']))
            <div class="photo-box">
                <img src="{{ public_path('storage/' . $task->form_data['photos']['before']) }}" class="photo-img">
                <div class="photo-caption">SITUACIÓN INICIAL</div>
            </div>
            @endif
            @if(isset($task->form_data['photos']['after']))
            <div class="photo-box">
                <img src="{{ public_path('storage/' . $task->form_data['photos']['after']) }}" class="photo-img">
                <div class="photo-caption">TRABAJO FINALIZADO</div>
            </div>
            @endif
            <div style="clear: both;"></div>
        </div>

        @php $findings = $task->form_data['findings'] ?? []; @endphp
        @for($i = 0; $i < count($findings); $i += 2)
        <div class="photo-row">
            <div class="photo-box">
                @if(isset($findings[$i]['photo']))
                    <img src="{{ public_path('storage/' . $findings[$i]['photo']) }}" class="photo-img">
                @endif
                <div class="photo-caption">HALLAZGO: {{ $findings[$i]['caption'] ?? 'Detalle' }}</div>
            </div>
            @if(isset($findings[$i+1]))
            <div class="photo-box">
                @if(isset($findings[$i+1]['photo']))
                    <img src="{{ public_path('storage/' . $findings[$i+1]['photo']) }}" class="photo-img">
                @endif
                <div class="photo-caption">HALLAZGO: {{ $findings[$i+1]['caption'] ?? 'Detalle' }}</div>
            </div>
            @endif
            <div style="clear: both;"></div>
        </div>
        @endfor

        <!-- Resumen -->
        <div class="section-header">Resumen Ejecutivo</div>
        <div style="border: 1px solid #ddd; padding: 8px; font-size: 8pt; min-height: 60px;">
            {{ $task->description }}
        </div>

        <!-- Firmas -->
        <table class="footer">
            <tr>
                <td class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $task->assignee->name ?? 'Técnico' }}</div>
                </td>
                <td class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Luis Gálvez / Supervisor</div>
                </td>
            </tr>
        </table>
    </div>
    @endforeach

</body>
</html>
