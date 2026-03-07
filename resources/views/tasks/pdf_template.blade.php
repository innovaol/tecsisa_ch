<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>REPORTE TÉCNICO - {{ $task->equipment->internal_id }}</title>
    <style>
        @page { margin: 0.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 8pt; color: #000; line-height: 1.2; }
        .bg-tecsisa-yellow { background-color: #FFD200; }
        .border-all { border: 0.5pt solid #000; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2pt; }
        td, th { border: 0.5pt solid #000; padding: 3pt; vertical-align: middle; }
        .header-title { font-weight: bold; font-size: 10pt; text-align: center; }
        .section-name { background-color: #DDEBF7; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 8pt; }
        .label { background-color: #F2F2F2; font-weight: bold; width: 18%; }
        .value { width: 32%; }
        .checklist-table td { padding: 2pt 4pt; }
        .check-box { width: 12pt; height: 12pt; border: 0.5pt solid #000; display: inline-block; text-align: center; line-height: 12pt; font-weight: bold; margin-right: 2pt; }
        .photo-grid { margin-top: 5pt; }
        .photo-box { border: 0.5pt solid #000; padding: 2pt; margin-bottom: 5pt; }
        .photo-title { background-color: #1e293b; color: white; font-weight: bold; padding: 2pt; font-size: 7pt; }
        .photo-img { width: 100%; height: 160pt; object-fit: cover; }
        .footer-table td { border: none; padding: 10pt; }
        .sig-line { border-top: 1pt solid #000; width: 80%; margin: 0 auto; padding-top: 2pt; }
    </style>
</head>
<body>

    <!-- CABECERA PRINCIPAL -->
    <table>
        <tr>
            <td rowspan="2" style="width: 20%; text-align: center; padding: 5pt; vertical-align: middle;">
                @if($company_logo)
                    <img src="{{ public_path('storage/' . $company_logo) }}" style="max-height: 40pt; max-width: 60pt;">
                @else
                    <div style="font-size: 14pt; font-weight: 900; letter-spacing: -1pt;">{{ $company_name }}</div>
                @endif
                <div style="font-size: 5pt; font-weight: bold; margin-top: 2pt;">{{ $company_footer }}</div>
            </td>
            <td class="header-title" style="width: 80%; background-color: #F2F2F2;">
                REPORTE TECNICO DE SISTEMAS ESPECIALES<br>
                SISTEMA DE {{ strtoupper($task->equipment->system->name ?? 'GENERAL') }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: left; padding-left: 10pt;">
                PROYECTO: HOSPITAL CIUDAD HOSPITALARIA
            </td>
        </tr>
    </table>

    <!-- INFORMACION DEL AREA -->
    <div class="section-name">Informacion del Area</div>
    <table>
        <tr>
            <td colspan="4" class="label" style="text-align: center; background-color: #D9D9D9;">TIPO DE MANTENIMIENTO: 
                <span class="check-box" style="margin-left: 20pt">{{ $task->task_type == 'maintenance' ? 'X' : '' }}</span> PREVENTIVO
                <span class="check-box" style="margin-left: 20pt">{{ $task->task_type != 'maintenance' ? 'X' : '' }}</span> CORRECTIVO
            </td>
        </tr>
        <tr>
            <td class="label">Edificio o Bloque</td><td class="value">{{ $task->form_data['building'] ?? 'N/A' }}</td>
            <td class="label">Fecha</td><td class="value">{{ $task->completed_at ? $task->completed_at->format('d/m/Y') : now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Nivel o Piso</td><td class="value">{{ $task->form_data['floor'] ?? 'N/A' }}</td>
            <td class="label">Hora</td><td class="value">{{ $task->form_data['start_time'] ?? '--:--' }} - {{ $task->form_data['end_time'] ?? '--:--' }}</td>
        </tr>
        <tr>
            <td class="label">Área</td><td class="value">{{ $task->form_data['specific_area'] ?? 'N/A' }}</td>
            <td class="label">Estatus Inicial</td><td class="value">Operativo</td>
        </tr>
        <tr>
            <td class="label">Sección</td><td class="value">{{ $task->form_data['section'] ?? 'N/A' }}</td>
            <td class="label">Técnico Responsable</td><td class="value">{{ $task->assignee->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- INSTALACIONES REQUERIDAS -->
    <div class="section-name">Instalaciones Requeridas</div>
    <div class="border-all" style="min-height: 40pt; padding: 5pt; margin-bottom: 5pt;">
        {{ $task->form_data['required_installations'] ?? 'No se requiere personal o material adicional.' }}
    </div>

    <!-- COMENTARIOS DEL INSPECTOR -->
    <div class="section-name">Comentarios del Inspector</div>
    <div class="border-all" style="min-height: 30pt; padding: 5pt; margin-bottom: 5pt;">
        {{ $task->description ?? 'Sin observaciones.' }}
    </div>

    <!-- ANEXOS -->
    <div class="section-name">Anexos al Informe</div>
    <table>
        <tr style="text-align: center; font-weight: bold;">
            <td><span class="check-box">{{ isset($task->form_data['annex_photos']) ? 'X' : '' }}</span> REGISTRO FOTOGRÁFICO</td>
            <td><span class="check-box">{{ isset($task->form_data['annex_plans']) ? 'X' : '' }}</span> PLANOS</td>
            <td><span class="check-box">{{ isset($task->form_data['annex_cert']) ? 'X' : '' }}</span> CERTIFICACIÓN</td>
        </tr>
    </table>

    <!-- CHECKLIST TÉCNICO -->
    <div class="section-name" style="margin-top: 5pt;">Detalle de la Actividad Realizada</div>
    <table class="checklist-table">
        <thead style="background-color: #F2F2F2;">
            <tr>
                <th style="width: 50%;">DETALLE DE LA ACTIVIDAD</th>
                <th style="width: 15%; text-align: center;">REALIZADO</th>
                <th style="width: 35%;">COMENTARIOS</th>
            </tr>
        </thead>
        <tbody>
            @php $evaluation = $task->form_data['evaluation'] ?? []; @endphp
            @foreach($evaluation as $item)
            <tr>
                <td>{{ $item['item'] ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    <span class="check-box">{{ ($item['status'] ?? '') == 'SI' ? 'X' : '' }}</span> SI 
                    <span class="check-box">{{ ($item['status'] ?? '') == 'NO' ? 'X' : '' }}</span> NO
                </td>
                <td>{{ $item['comment'] ?? '' }}</td>
            </tr>
            @endforeach
            @if(count($evaluation) == 0)
            <tr><td colspan="3" style="text-align: center; color: #999;">No hay checklist definido para este sistema.</td></tr>
            @endif
        </tbody>
    </table>

    <div style="font-size: 6pt; font-style: italic; margin-bottom: 10pt;">*TODAS LAS RESPUESTAS SELECCIONADAS COMO "NO" DEBEN EXPLICARSE EN LA COLUMNA DE COMENTARIOS*</div>

    <!-- FIRMAS -->
    <table class="footer-table" style="margin-top: 30pt;">
        <tr style="text-align: center; font-weight: bold;">
            <td style="width: 20%; padding: 0;">Información</td>
            <td style="width: 40%; padding: 0;">Técnico Responsable</td>
            <td style="width: 40%; padding: 0;">Ingeniero Responsable</td>
        </tr>
        <tr>
            <td style="padding: 10pt 0;">
                <strong>Nombre:</strong><br><br>
                <strong>Cargo:</strong><br><br>
                <strong>Firma:</strong>
            </td>
            <td style="text-align: center; vertical-align: bottom;">
                <div style="margin-bottom: 2pt;">
                    <div style="font-weight: bold; font-size: 9pt;">{{ $task->assignee->name ?? 'Admin Tecsisa' }}</div>
                    <div style="font-size: 7pt; color: #333;">Técnico Nivel 2</div>
                </div>
                <div style="border-top: 1pt solid #000; width: 90%; margin: 0 auto;"></div>
            </td>
            <td style="text-align: center; vertical-align: bottom;">
                <div style="margin-bottom: 2pt;">
                    <div style="font-weight: bold; font-size: 9pt;">Luis Gálvez</div>
                    <div style="font-size: 7pt; color: #333;">Ing. Supervisor de Obra</div>
                </div>
                <div style="border-top: 1pt solid #000; width: 90%; margin: 0 auto;"></div>
            </td>
        </tr>
    </table>

    <!-- INFORME FOTOGRÁFICO (PÁGINA APARTE O CONTINUACIÓN) -->
    <div style="page-break-before: always;"></div>
    <div class="section-name" style="background-color: #1e293b; color: white;">Informe Fotográfico</div>
    
    <div class="photo-grid">
        @if(isset($task->form_data['photos']['before']) || isset($task->form_data['photos']['after']))
        <table>
            <tr>
                <td style="width: 50%; border: none;">
                    @if(isset($task->form_data['photos']['before']))
                    <div class="photo-box">
                        <div class="photo-title">Fotografía N°1: Situación Inicial</div>
                        <img src="{{ public_path('storage/' . $task->form_data['photos']['before']) }}" class="photo-img">
                        <div style="font-size: 7pt; padding: 2pt;"><strong>Ubicación:</strong> {{ $task->form_data['building'] ?? 'N/A' }}</div>
                    </div>
                    @endif
                </td>
                <td style="width: 50%; border: none;">
                    @if(isset($task->form_data['photos']['after']))
                    <div class="photo-box">
                        <div class="photo-title">Fotografía N°2: Trabajo Finalizado</div>
                        <img src="{{ public_path('storage/' . $task->form_data['photos']['after']) }}" class="photo-img">
                        <div style="font-size: 7pt; padding: 2pt;"><strong>Ubicación:</strong> {{ $task->form_data['building'] ?? 'N/A' }}</div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
        @endif

        @php $findings = $task->form_data['findings'] ?? []; @endphp
        @for($i = 0; $i < count($findings); $i += 2)
        <table>
            <tr>
                <td style="width: 50%; border: none;">
                    <div class="photo-box">
                        <div class="photo-title">Fotografía N°{{ $i + 3 }}: Hallazgo Técnico</div>
                        @if(isset($findings[$i]['photo']))
                            <img src="{{ public_path('storage/' . $findings[$i]['photo']) }}" class="photo-img">
                        @endif
                        <div style="font-size: 7pt; padding: 2pt;"><strong>Detalle:</strong> {{ $findings[$i]['caption'] ?? 'N/A' }}</div>
                    </div>
                </td>
                <td style="width: 50%; border: none;">
                    @if(isset($findings[$i+1]))
                    <div class="photo-box">
                        <div class="photo-title">Fotografía N°{{ $i + 4 }}: Hallazgo Técnico</div>
                        @if(isset($findings[$i+1]['photo']))
                            <img src="{{ public_path('storage/' . $findings[$i+1]['photo']) }}" class="photo-img">
                        @endif
                        <div style="font-size: 7pt; padding: 2pt;"><strong>Detalle:</strong> {{ $findings[$i+1]['caption'] ?? 'N/A' }}</div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
        @endfor
    </div>

</body>
</html>
