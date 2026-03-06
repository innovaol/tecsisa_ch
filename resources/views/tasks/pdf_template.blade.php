<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Técnico - {{ $task->equipment->internal_id }}</title>
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
        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .logo-box {
            width: 150px;
            text-align: left;
        }
        .title-box {
            text-align: center;
            background-color: #f3f4f6;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .title-main {
            font-weight: bold;
            font-size: 14pt;
            display: block;
        }
        .title-sub {
            font-size: 12pt;
            color: #d97706; /* Tecsisa Yellow-ish */
            font-weight: bold;
        }
        .section-header {
            background-color: #1e293b; /* Dark Blue */
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
            font-size: 9pt;
        }
        .label {
            background-color: #f9fafb;
            font-weight: bold;
            width: 30%;
        }
        .installation-box {
            border: 1px solid #ddd;
            padding: 10px;
            min-height: 50px;
            background-color: #fffbeb; /* Light orange for Required Installations */
            font-size: 9pt;
            margin-bottom: 15px;
        }
        .photo-container {
            width: 100%;
            margin-top: 10px;
        }
        .photo-row {
            margin-bottom: 15px;
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
            height: 180px;
            object-fit: cover;
        }
        .photo-caption {
            font-size: 8pt;
            font-weight: bold;
            margin-top: 5px;
            background-color: #f3f4f6;
            padding: 3px;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }
        .signature-box {
            width: 50%;
            text-align: center;
            padding-top: 40px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 80%;
            margin: 0 auto;
            margin-bottom: 5px;
        }
        .signature-name {
            font-size: 9pt;
            font-weight: bold;
        }
        .signature-title {
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header">
        <tr>
            <td class="logo-box">
                <div style="font-size: 24pt; font-weight: 900; color: #1e293b;">TECSISA</div>
                <div style="font-size: 10pt; font-weight: bold; color: #64748b;">PANAMA S.A.</div>
            </td>
            <td class="title-box">
                <span class="title-main">REPORTE TÉCNICO DE SISTEMAS ESPECIALES</span>
                <span class="title-sub">SISTEMA DE {{ strtoupper($task->equipment->system->name ?? 'GENERAL') }}</span>
            </td>
        </tr>
    </table>

    <div style="font-size: 8pt; color: #666; margin-bottom: 10px;">
        PROYECTO: HOSPITAL CIUDAD HOSPITALARIA | FECHA: {{ $task->completed_at ? $task->completed_at->format('d/m/Y') : now()->format('d/m/Y') }}
    </div>

    <!-- Area Information -->
    <div class="section-header">Información del Area</div>
    <table class="info-table">
        <tr>
            <td class="label">Ubicación / Equipo:</td>
            <td>{{ $task->location_snapshot }} | <strong>{{ $task->equipment->internal_id }}</strong></td>
            <td class="label">Tipo Mantenimiento:</td>
            <td>{{ strtoupper($task->task_type == 'maintenance' ? 'Preventivo' : ($task->task_type == 'replacement' ? 'Correctivo' : 'Instalación')) }}</td>
        </tr>
        <tr>
            <td class="label">Equipo / Activo:</td>
            <td>{{ $task->equipment->name }}</td>
            <td class="label">Estatus Final:</td>
            <td>{{ $task->final_status ?? 'Operativo' }}</td>
        </tr>
        <tr>
            <td class="label">Técnico Responsable:</td>
            <td>{{ $task->assignee->name ?? 'No asignado' }}</td>
            <td class="label">Hora de Término:</td>
            <td>{{ $task->completed_at ? $task->completed_at->format('H:i') : '--:--' }}</td>
        </tr>
    </table>

    <!-- Installations Required -->
    <div class="section-header">Instalaciones Requeridas / Pendientes</div>
    <div class="installation-box">
        {{ $task->form_data['required_installations'] ?? 'No se reportaron instalaciones pendientes.' }}
    </div>

    <!-- Summary / Observations -->
    <div class="section-header">Resumen Ejecutivo de la Labor</div>
    <div style="border: 1px solid #ddd; padding: 10px; font-size: 9pt; min-height: 80px; margin-bottom: 15px;">
        {{ $task->description ?? 'Sin observaciones adicionales.' }}
    </div>

    <!-- Photo Gallery -->
    <div class="section-header">Evidencia Fotográfica (Informe en Sitio)</div>
    <div class="photo-container">
        <!-- Main Photos (Before & After) -->
        <div class="photo-row">
            @if(isset($task->form_data['photos']['before']))
            <div class="photo-box">
                <img src="{{ public_path('storage/' . $task->form_data['photos']['before']) }}" class="photo-img">
                <div class="photo-caption">EVIDENCIA: SITUACIÓN INICIAL</div>
            </div>
            @endif
            
            @if(isset($task->form_data['photos']['after']))
            <div class="photo-box">
                <img src="{{ public_path('storage/' . $task->form_data['photos']['after']) }}" class="photo-img">
                <div class="photo-caption">EVIDENCIA: TRABAJO FINALIZADO</div>
            </div>
            @endif
            <div style="clear: both;"></div>
        </div>

        <!-- Findings Gallery -->
        @php $findings = $task->form_data['findings'] ?? []; @endphp
        @for($i = 0; $i < count($findings); $i += 2)
        <div class="photo-row">
            <!-- Left Finding -->
            <div class="photo-box">
                @if(isset($findings[$i]['photo']))
                    <img src="{{ public_path('storage/' . $findings[$i]['photo']) }}" class="photo-img">
                @endif
                <div class="photo-caption">HALLAZGO: {{ $findings[$i]['caption'] ?? 'Detalle Técnico' }}</div>
            </div>
            
            <!-- Right Finding -->
            @if(isset($findings[$i+1]))
            <div class="photo-box">
                @if(isset($findings[$i+1]['photo']))
                    <img src="{{ public_path('storage/' . $findings[$i+1]['photo']) }}" class="photo-img">
                @endif
                <div class="photo-caption">HALLAZGO: {{ $findings[$i+1]['caption'] ?? 'Detalle Técnico' }}</div>
            </div>
            @endif
            <div style="clear: both;"></div>
        </div>
        @endfor
    </div>

    <!-- Signatures -->
    <table class="footer">
        <tr>
            <td class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $task->assignee->name ?? '________________' }}</div>
                <div class="signature-title">Técnico Responsable</div>
                @if($task->assignee->phone ?? false)<div class="signature-title">{{ $task->assignee->phone }}</div>@endif
            </td>
            <td class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Luis Gálvez</div>
                <div class="signature-title">Ingeniero Supervisor</div>
            </td>
        </tr>
    </table>

</body>
</html>
