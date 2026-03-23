<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CERTIFICADO DE RACK - {{ $rack->name }}</title>
    <style>
        @page { margin: 1.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 9pt; color: #333; line-height: 1.4; }
        .bg-tecsisa { background-color: #16213e; color: white; }
        .bg-accent { background-color: #FFCC00; color: #16213e; }
        .border-all { border: 1pt solid #16213e; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15pt; }
        td, th { border: 1pt solid #ccc; padding: 6pt; text-align: left; }
        .header-title { font-weight: bold; font-size: 14pt; padding: 10pt; text-align: center; border: 2pt solid #16213e; }
        .section-name { background-color: #f3f4f6; font-weight: bold; padding: 5pt; border: 1pt solid #16213e; text-transform: uppercase; margin-bottom: 5pt; }
        
        /* Rack Visual Style */
        .rack-container { width: 100%; border: 4pt solid #333; background: #eee; padding: 2pt; margin-top: 10pt; }
        .rack-unit { 
            height: 18pt; 
            border: 1pt solid #999; 
            margin-bottom: 1pt; 
            position: relative;
            background: #fff;
            font-size: 7pt;
            display: table;
            width: 100%;
        }
        .u-number { 
            display: table-cell; 
            width: 30pt; 
            background: #333; 
            color: #fff; 
            text-align: center; 
            vertical-align: middle; 
            font-weight: bold;
        }
        .u-content { 
            display: table-cell; 
            vertical-align: middle; 
            padding-left: 10pt;
            font-weight: bold;
        }
        .u-occupied { background: #FFCC00; border-left: 5pt solid #16213e; }
        .u-free { color: #aaa; font-style: italic; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #999; border-top: 1pt solid #eee; padding-top: 5pt; }
    </style>
</head>
<body>

    <!-- CABECERA -->
    <table style="border: none;">
        <tr>
            <td style="width: 30%; border: none;">
                <h1 style="font-size: 20pt; margin: 0; color: #16213e;">TECSISA</h1>
                <p style="font-size: 7pt; margin: 0; text-transform: uppercase; letter-spacing: 2pt;">Sistemas Especiales</p>
            </td>
            <td style="width: 70%; border: none; text-align: right;">
                <div class="header-title bg-accent">REPORTE TÉCNICO DE GABINETE (RACK)</div>
            </td>
        </tr>
    </table>

    <div class="section-name">Información del Rack</div>
    <table>
        <tr>
            <th style="background: #f9fafb; width: 20%;">Nombre del Rack:</th>
            <td style="width: 30%;">{{ $rack->name }}</td>
            <th style="background: #f9fafb; width: 20%;">Ubicación:</th>
            <td>{{ $rack->location->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th style="background: #f9fafb;">Capacidad Total:</th>
            <td>{{ $rack->total_units }} Unidades (U)</td>
            <th style="background: #f9fafb;">Estado:</th>
            <td>{{ strtoupper($rack->status) }}</td>
        </tr>
        <tr>
            <th style="background: #f9fafb;">Fecha Reporte:</th>
            <td>{{ date('d/m/Y') }}</td>
            <th style="background: #f9fafb;">Unidades Ocupadas:</th>
            <td>{{ $rack->units->count() }} U</td>
        </tr>
    </table>

    <div class="section-name">Layout Físico (Vista Frontal)</div>
    <div class="rack-container">
        @php
            $occupiedUnits = $rack->units->keyBy('unit_number');
        @endphp
        
        @for($i = $rack->total_units; $i >= 1; $i--)
            @php 
                $uDat = $occupiedUnits->get($i); 
                $isOcu = !empty($uDat);
            @endphp
            <div class="rack-unit {{ $isOcu ? 'u-occupied' : 'u-free' }}">
                <div class="u-number">{{ $i }}U</div>
                <div class="u-content">
                    @if($isOcu)
                        {{ $uDat->equipment->name }} — [{{ $uDat->equipment->internal_id }}]
                        <span style="font-size: 6pt; float: right; margin-right: 5pt; color: #16213e;">{{ $uDat->equipment->system->name ?? '' }}</span>
                    @else
                        Disponible
                    @endif
                </div>
            </div>
        @endfor
    </div>

    <div style="page-break-before: always;"></div>

    <div class="section-name">Detalle de Equipos Instalados</div>
    <table style="width: 100%;">
        <thead class="bg-tecsisa">
            <tr>
                <th style="color: white; border: 1pt solid #fff; width: 10%;">U. Pos</th>
                <th style="color: white; border: 1pt solid #fff; width: 20%;">Tag Equipo</th>
                <th style="color: white; border: 1pt solid #fff; width: 40%;">Descripción / Nombre</th>
                <th style="color: white; border: 1pt solid #fff; width: 20%;">Sistema</th>
                <th style="color: white; border: 1pt solid #fff; width: 10%;">Altura (U)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units->sortBy('unit_number') as $u)
            <tr>
                <td style="text-align: center; font-weight: bold;">{{ $u->unit_number }}U</td>
                <td style="font-family: monospace;">{{ $u->equipment->internal_id }}</td>
                <td>{{ $u->equipment->name }}</td>
                <td>{{ $u->equipment->system->name ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $u->equipment->u_height }}U</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($rack->notes)
    <div class="section-name">Observaciones Adicionales</div>
    <div style="border: 1pt solid #ccc; padding: 10pt; background: #fff;">
        {{ $rack->notes }}
    </div>
    @endif

    <div class="footer">
        Este documento es un registro oficial de la topología de red de TECSISA Ciudad Hospitalaria.<br>
        Generado el {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>
