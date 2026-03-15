<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{{ $title }}</title>
<style>
/* ======================================================
   BASE — @page margin 0, manejamos padding manualmente
   Letra: 215.9mm × 279.4mm
   ====================================================== */
@page             { margin: 0; size: letter portrait; }
@page map-page    { margin: 0; size: letter landscape; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 8.5pt; color: #16213e; background: #fff; line-height: 1.4; }
.page-break { page-break-after: always; }
.no-break   { page-break-inside: avoid; }
.clearfix::after { content:''; display:block; clear:both; }

/* ── Página de CONTENIDO NORMAL (fichas, tablas) ── */
.p-content {
    padding: 18mm 18mm 14mm 18mm;
    page-break-after: always;
}
/* ── Página de FOTOS ── */
.p-photos {
    padding: 12mm 18mm 12mm 18mm;
    page-break-after: always;
}

/* ======================================================
   PORTADA — ocupa exactamente una hoja letter
   ====================================================== */
.cover {
    width:  215.9mm;
    height: 279.4mm;
    background: #fff;
    position: relative;
    overflow: hidden;
    page-break-after: always;
}
.cover-bar-top    { background:#FFCC00; height:10px; width:100%; }
.cover-bar-bottom { background:#16213e; height:10px; width:100%; position:absolute; bottom:0; left:0; }
.cover-edge-left  { position:absolute; left:0; top:0; bottom:0; width:6px; background:#16213e; }
.cover-inner {
    padding: 50px 55px 40px 60px;
    text-align: center;
    /* NO usar height calc() — DomPDF no lo soporta bien */
}
.cover-logo-wrap  { margin-bottom: 40px; }
.cover-logo-wrap img { max-height: 70px; }
.cover-logo-text  { font-size:26pt; font-weight:900; letter-spacing:5px; color:#16213e; }
.cover-logo-sub   { font-size:8pt; color:#888; letter-spacing:3px; text-transform:uppercase; margin-top:4px; }
.cover-divider    { width:50px; height:4px; background:#FFCC00; margin:28px auto; }
.cover-pre        { font-size:7.5pt; color:#aaa; letter-spacing:4px; text-transform:uppercase; margin-bottom:10px; }
.cover-title      { font-size:28pt; font-weight:900; color:#16213e; line-height:1.15; text-transform:uppercase; margin-bottom:8px; }
.cover-city       { font-size:10.5pt; font-weight:bold; color:#777; text-transform:uppercase; letter-spacing:2px; margin-bottom:32px; }
.cover-box {
    display:inline-block;
    border:2px solid #16213e;
    padding:12px 30px;
    margin-bottom:38px;
    text-align:center;
}
.cover-box-lbl { font-size:6.5pt; color:#aaa; text-transform:uppercase; letter-spacing:3px; margin-bottom:5px; }
.cover-box-val { font-size:13pt; font-weight:900; color:#16213e; text-transform:uppercase; }
.cover-stats { text-align:center; }
.c-stat  { display:inline-block; margin:0 20px; text-align:center; border-top:3px solid #FFCC00; padding-top:9px; min-width:55px; }
.c-num   { font-size:22pt; font-weight:900; color:#16213e; line-height:1; }
.c-lbl   { font-size:5.5pt; color:#aaa; text-transform:uppercase; letter-spacing:2px; margin-top:3px; }

/* ======================================================
   SEPARADOR DE SISTEMA — full-bleed oscuro
   ====================================================== */
.sep-page {
    width:  215.9mm;
    height: 279.4mm;
    background: #16213e;
    text-align: center;
    position: relative;
    overflow: hidden;
    page-break-after: always;
}
.sep-inner   { padding: 180px 40px 40px; }
.sep-pre     { font-size:7pt; color:rgba(255,255,255,0.4); letter-spacing:5px; text-transform:uppercase; margin-bottom:18px; }
.sep-title   { font-size:34pt; font-weight:900; color:#FFCC00; text-transform:uppercase; line-height:1.1; }
.sep-count   { font-size:7.5pt; color:rgba(255,255,255,0.35); letter-spacing:3px; text-transform:uppercase; margin-top:22px; }
.sep-accent  { position:absolute; bottom:0; left:0; right:0; height:6px; background:#FFCC00; }

/* ======================================================
   FICHA TÉCNICA — encabezado
   ====================================================== */
.rpt-hdr { width:100%; border-collapse:collapse; margin-bottom:0; }
.rpt-hdr td { border:1.5px solid #16213e; padding:0; vertical-align:middle; }
.rph-logo { width:27%; text-align:center; padding:10px 8px; border-right:1.5px solid #16213e; vertical-align:middle; }
.rph-logo img { max-height:55px; max-width:90%; display:block; margin:0 auto; }
.rph-logo-t { font-size:15pt; font-weight:900; color:#16213e; letter-spacing:3px; text-align:center; }
.rph-title  { width:73%; background:#FFCC00; }
.rph-t1 { font-size:9.5pt; font-weight:900; color:#16213e; padding:7px 13px 3px; text-transform:uppercase; }
.rph-t2 { font-size:7.5pt; color:#333; padding:0 13px 7px; }

/* ======================================================
   SECCIONES
   ====================================================== */
.sec-y {
    background:#FFCC00; color:#16213e;
    font-size:7.5pt; font-weight:900;
    padding:4px 9px;
    text-transform:uppercase; letter-spacing:1.2px;
    border:1.5px solid #16213e; border-top:0;
}
.sec-d {
    background:#16213e; color:#fff;
    font-size:7.5pt; font-weight:900;
    padding:4px 9px;
    text-transform:uppercase; letter-spacing:1.2px;
    border:1.5px solid #16213e; border-top:0;
}
.sec-s { display:inline-block; font-size:7pt; font-weight:900; padding:2px 8px; border-radius:3px; text-transform:uppercase; }
.s-completed  { background:#d1fae5; color:#065f46; }
.s-verified   { background:#dbeafe; color:#1e40af; }
.s-in_review  { background:#ede9fe; color:#5b21b6; }
.s-approval   { background:#ede9fe; color:#5b21b6; }
.s-in_progress{ background:#fef3c7; color:#92400e; }
.s-active     { background:#d1fae5; color:#065f46; }
.s-pending    { background:#fee2e2; color:#991b1b; }
.s-draft      { background:#f3f4f6; color:#6b7280; }

/* ======================================================
   TABLAS DE DATOS
   ====================================================== */
.dt { width:100%; border-collapse:collapse; border:1.5px solid #16213e; border-top:0; }
.dt td { border:1px solid #9ca3af; padding:4px 8px; font-size:8pt; vertical-align:middle; }
.dt .l { background:#f9fafb; font-weight:700; color:#374151; font-size:7.5pt; text-transform:uppercase; }
.dt .v { background:#fff; color:#111; }
.chk {
    display:inline-block; width:12px; height:12px;
    border:1.5px solid #16213e;
    margin-right:3px; vertical-align:middle;
    text-align:center; line-height:10px; font-size:9pt; font-weight:900;
}
.txt {
    border:1.5px solid #16213e; border-top:0;
    padding:8px 10px; min-height:36px;
    font-size:8.5pt; background:#fff; color:#222; line-height:1.55;
}

/* ======================================================
   CHECKLIST / ACTIVIDADES
   ====================================================== */
.act-tbl { width:100%; border-collapse:collapse; border:1.5px solid #16213e; border-top:0; }
.act-tbl th { background:#16213e; color:#fff; font-size:7pt; font-weight:700; padding:4px 8px; border:1px solid #374151; text-transform:uppercase; }
.act-tbl td { border:1px solid #9ca3af; padding:3.5px 8px; font-size:8pt; }
.act-tbl tr:nth-child(even) td { background:#f9fafb; }
.b-si { color:#065f46; font-weight:900; }
.b-no { color:#991b1b; font-weight:900; }

/* ======================================================
   MATERIALES
   ====================================================== */
.mat-tbl { width:100%; border-collapse:collapse; border:1.5px solid #16213e; border-top:0; }
.mat-tbl th { background:#FFCC00; color:#16213e; font-size:7pt; font-weight:900; padding:4px 8px; border:1px solid #16213e; text-transform:uppercase; }
.mat-tbl td { border:1px solid #9ca3af; padding:3.5px 8px; font-size:8pt; }

/* ======================================================
   FIRMAS
   ====================================================== */
.sign-wrap { margin-top:12px; width:100%; }
.sign-b { width:47%; border:1.5px solid #16213e; display:inline-block; vertical-align:top; }
.sign-b + .sign-b { float:right; }
.sign-hdr  { background:#FFCC00; color:#16213e; font-size:7pt; font-weight:900; padding:3px 9px; border-bottom:1.5px solid #16213e; text-transform:uppercase; }
.sign-body { padding:6px 10px 4px; font-size:8pt; min-height:52px; }
.sign-line { border-top:1px solid #9ca3af; margin-top:22px; padding-top:3px; text-align:center; font-size:6.5pt; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:2px; }

/* ── Fotos en grilla 2 columnas con tabla — altura adaptable ── */
.photo-page-hdr {
    background:#16213e; color:#FFCC00;
    font-size:9pt; font-weight:900;
    padding:8px 14px;
    text-transform:uppercase; letter-spacing:1.5px;
    border:1.5px solid #16213e;
    margin-bottom:8px;
}
.photo-lbl {
    background:#FFCC00; color:#16213e;
    font-size:7.5pt; font-weight:900;
    padding:4px 8px; text-transform:uppercase; letter-spacing:1px;
    border-bottom:1.5px solid #16213e;
}
/* El contenedor de foto se adapta a la imagen (no tiene altura fija) */
.photo-bg { background:#f3f4f6; text-align:center; padding:3px; }
.photo-bg img { max-width:100%; height:auto; display:block; margin:0 auto; }

/* ======================================================
   PÁGINA COMPLETA — CERTIFICACIÓN portrait
   Encabezado + imagen en el mismo div irrompible
   ====================================================== */
.fullpage-img {
    width:  215.9mm;
    height: 279.4mm;
    background: #fff;
    page-break-after: always;
    page-break-inside: avoid;
    overflow: hidden;
    position: relative;
}
.fullpage-img-hdr {
    background:#16213e; color:#FFCC00;
    font-size:9pt; font-weight:900;
    padding:10px 18mm;
    text-transform:uppercase; letter-spacing:1.5px;
}
.fullpage-img-sub {
    background:#FFCC00; color:#16213e;
    font-size:7.5pt; font-weight:700;
    padding:4px 18mm;
    border-bottom:2px solid #16213e;
}
/* Imagen centrada, altura = hoja − encabezados − padding */
.fullpage-img-body {
    padding: 10mm 18mm;
    text-align:center;
}
.fullpage-img-body img {
    max-width:  100%;
    max-height: 210mm;
    object-fit: contain;
    display: block;
    margin: 0 auto;
    text-align: center;
}

/* ======================================================
   PÁGINA COMPLETA — PLANO
   DomPDF no soporta el CSS `page` de forma confiable;
   usamos portrait con la imagen centrada y grande.
   ====================================================== */
.fullpage-map {
    width:  215.9mm;
    height: 279.4mm;
    background: #fff;
    page-break-after: always;
    page-break-inside: avoid;
    overflow: hidden;
    position: relative;
}
.fullpage-map-hdr {
    background:#FFCC00; color:#16213e;
    font-size:9pt; font-weight:900;
    padding:9px 18mm;
    text-transform:uppercase; letter-spacing:1.5px;
    border-bottom:2px solid #16213e;
}
.fullpage-map-body {
    padding: 8mm 12mm;
    text-align: center;   /* centrado por texto, no por margin auto */
}
.fullpage-map-body img {
    max-width:  100%;
    max-height: 215mm;   /* 279.4 - hdr 14mm - padding 16mm - margen = ~215mm */
    object-fit: contain;
}

/* ======================================================
   TABLA DE PRODUCCIÓN
   ====================================================== */
.prod-hdr {
    background:#16213e; color:#fff;
    font-size:9pt; font-weight:900;
    padding:8px 13px;
    text-transform:uppercase; letter-spacing:1px;
    border:1.5px solid #16213e; border-bottom:0;
    text-align:center;
}
.prod-tbl { width:100%; border-collapse:collapse; border:1.5px solid #16213e; }
.prod-tbl th { background:#374151; color:#fff; font-size:6.5pt; font-weight:700; padding:4.5px 4px; border:1px solid #4b5563; text-align:center; text-transform:uppercase; }
.prod-tbl td { border:1px solid #9ca3af; padding:3.5px 4px; font-size:7.5pt; text-align:center; vertical-align:middle; }
.prod-tbl tr:nth-child(even) td { background:#f9fafb; }
.prod-tbl .tag { font-weight:900; text-align:left; font-size:7pt; }
.prod-tbl .sys { text-align:left; font-size:7pt; }
.p-si   { color:#065f46; font-weight:900; }
.p-no   { color:#9ca3af; }
.p-pass { color:#065f46; font-weight:900; }
.p-pend { color:#92400e; }

/* ======================================================
   RESUMEN MATERIALES
   ====================================================== */
.mat-res-tbl { width:100%; border-collapse:collapse; border:1.5px solid #16213e; }
.mat-res-tbl th { background:#16213e; color:#fff; font-size:7pt; font-weight:700; padding:4px 9px; border:1px solid #374151; text-transform:uppercase; }
.mat-res-tbl td { border:1px solid #9ca3af; padding:3.5px 9px; font-size:8pt; }
.mat-res-tbl tr:nth-child(even) td { background:#f9fafb; }

.mt-8  { margin-top:8px; }
.mt-12 { margin-top:12px; }
.center{ text-align:center; }
</style>
</head>
<body>

@php
    use Illuminate\Support\Str;
    $imgExts = ['jpg','jpeg','png','gif','webp'];

    // Resuelve ruta física para DomPDF — Windows safe
    $resolveImg = function($relPath) use ($imgExts) {
        if (empty($relPath)) return null;
        $abs = str_replace('\\', '/', storage_path('app/public/' . ltrim($relPath, '/')));
        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        return (file_exists($abs) && in_array($ext, $imgExts)) ? $abs : null;
    };

    $logo        = \App\Models\Setting::getValue('company_logo');
    $logoPath    = $logo ? $resolveImg($logo) : null;
    $companyName = $company_name ?? 'TECSISA';
    $weekNum     = $start_date->weekOfYear;

    // ── Datos globales del proyecto (Settings) ──
    $engineerName   = \App\Models\Setting::getValue('engineer_name', '');
    $engineerCargo  = \App\Models\Setting::getValue('engineer_cargo', 'Ingeniero Responsable de Obra');
    $projectName    = \App\Models\Setting::getValue('project_name', 'Hospital Anita Moreno — Sistemas Especiales');
    $contractNumber = \App\Models\Setting::getValue('contract_number', '');

    $statusLabels = [
        'draft'       => 'Borrador',
        'pending'     => 'Pendiente',
        'in_progress' => 'Activa',
        'active'      => 'Activa',
        'in_review'   => 'Aprobación',
        'approval'    => 'Aprobación',
        'completed'   => 'Completada',
        'verified'    => 'Verificada',
    ];
@endphp

{{-- =====================================================
     PORTADA
     ===================================================== --}}
<div class="cover">
    <div class="cover-bar-top"></div>
    <div class="cover-edge-left"></div>
    <div class="cover-inner">
        <div class="cover-logo-wrap">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="{{ $companyName }}">
            @else
                <div class="cover-logo-text">{{ strtoupper($companyName) }}</div>
                <div class="cover-logo-sub">Sistemas Especiales</div>
            @endif
        </div>
        <div class="cover-divider"></div>
        <div class="cover-pre">Informe Técnico de Operaciones</div>
        <div class="cover-title">Reporte<br>Hospital Anita Moreno<br>Sistemas Especiales</div>
        <div class="cover-city">Ciudad Hospitalaria Dr. Enrique Tejera</div>
        <div class="cover-box">
            <div class="cover-box-lbl">Período &mdash; Semana {{ $weekNum }}</div>
            <div class="cover-box-val">
                {{ $start_date->translatedFormat('d \d\e F') }}
                &mdash;
                {{ $end_date->translatedFormat('d \d\e F \d\e Y') }}
            </div>
        </div>
        <div class="cover-stats">
            <div class="c-stat"><div class="c-num">{{ $stats['total_tasks'] }}</div><div class="c-lbl">Tareas</div></div>
            <div class="c-stat"><div class="c-num">{{ $stats['certified'] }}</div><div class="c-lbl">Certificadas</div></div>
            <div class="c-stat"><div class="c-num">{{ $stats['new_cable'] }}</div><div class="c-lbl">Cable Nuevo</div></div>
            <div class="c-stat"><div class="c-num">{{ $stats['new_jack'] }}</div><div class="c-lbl">Jack Nuevo</div></div>
        </div>
    </div>
    <div class="cover-bar-bottom"></div>
</div>

{{-- =====================================================
     SECCIONES POR SISTEMA
     ===================================================== --}}
@php
    $bySystem = $tasks->groupBy(function($t) {
        return $t->system->name ?? $t->equipment->system->name ?? 'GENERAL';
    });
@endphp

@foreach($bySystem as $sysName => $sysTasks)

{{-- SEPARADOR DE SISTEMA --}}
<div class="sep-page">
    <div class="sep-inner">
        <div class="sep-pre">Sistemas Especiales &mdash; Detalle Técnico</div>
        <div class="sep-title">{{ strtoupper($sysName) }}</div>
        <div class="sep-count">
            {{ $sysTasks->count() }} {{ $sysTasks->count() == 1 ? 'intervención' : 'intervenciones' }} registrada{{ $sysTasks->count() == 1 ? '' : 's' }} en el período
        </div>
    </div>
    <div class="sep-accent"></div>
</div>

@foreach($sysTasks as $task)
@php
    $fd          = $task->form_data ?? [];
    $evaluation  = $fd['evaluation'] ?? [];
    $materials   = $fd['materials']  ?? [];
    $findings    = $fd['findings']   ?? [];
    $taskDate    = $task->completed_at ?? $task->updated_at;
    $statusKey   = $task->status ?? 'draft';
    $statusLabel = $statusLabels[$statusKey] ?? ucfirst($statusKey);
    $tipos       = ['maintenance'=>'Mantenimiento Preventivo','replacement'=>'Sustitución','installation'=>'Instalación','inspection'=>'Inspección'];

    // ── TODAS las fotos van en grilla 2 columnas juntas: antes, después, hallazgos
    $gridPhotos = [];
    if ($p = $resolveImg($fd['photos']['before']  ?? null))
        $gridPhotos[] = ['path'=>$p, 'label'=>'SITUACIÓN INICIAL — ANTES'];
    if ($p = $resolveImg($fd['photos']['after']   ?? null))
        $gridPhotos[] = ['path'=>$p, 'label'=>'TRABAJO FINALIZADO — DESPUÉS'];
    foreach ($findings as $f) {
        if ($p = $resolveImg($f['photo'] ?? null))
            $gridPhotos[] = ['path'=>$p, 'label'=>'HALLAZGO TÉCNICO', 'caption' => $f['caption'] ?? ''];
    }

    // ── Certificaciones en página completa: Fluke + archivos cert
    $fullCerts = [];
    if ($p = $resolveImg($fd['photos']['fluke_screen'] ?? null))
        $fullCerts[] = ['path'=>$p, 'label'=>'CERTIFICACIÓN FLUKE NETWORKS',
                        'sub' => 'Tag: '.($task->equipment->internal_id ?? '—').' | '.($fd['building'] ?? '').($fd['floor'] ? ' / '.$fd['floor'] : '')];
    foreach ($fd['files']['certs'] ?? [] as $cf) {
        if ($p = $resolveImg($cf))
            $fullCerts[] = ['path'=>$p, 'label'=>'CERTIFICADO ANEXO',
                            'sub' => 'Tag: '.($task->equipment->internal_id ?? '—').' | '.($fd['building'] ?? '')];
    }

    // ── Planos en página completa horizontal
    $fullMaps = [];
    foreach ($fd['files']['plans'] ?? [] as $pf) {
        if ($p = $resolveImg($pf))
            $fullMaps[] = ['path'=>$p, 'label'=>'PLANO / MAPA DE RED',
                           'sub' => 'Tag: '.($task->equipment->internal_id ?? '—').' | '.($fd['building'] ?? '').($fd['floor'] ? ' / '.$fd['floor'] : '')];
    }
@endphp

{{-- ── FICHA TÉCNICA ── --}}
<div class="p-content">

    {{-- Encabezado --}}
    <table class="rpt-hdr">
        <tr>
            <td class="rph-logo">
                @if($logoPath)<img src="{{ $logoPath }}" alt="">
                @else<div class="rph-logo-t">{{ strtoupper(substr($companyName,0,7)) }}</div>@endif
                {{-- Solo logo, sin texto debajo --}}
            </td>
            <td class="rph-title">
                <div class="rph-t1">Reporte Técnico &mdash; Sistemas Especiales</div>
                <div class="rph-t2">{{ strtoupper($sysName) }} &nbsp;|&nbsp; Ciudad Hospitalaria Dr. Enrique Tejera</div>
            </td>
        </tr>
    </table>

    {{-- DATOS GENERALES --}}
    <div class="sec-y mt-8">&#9632; Datos Generales de la Intervención</div>
    <table class="dt">
        <tr>
            <td class="l" style="width:16%;">Tag / Activo:</td>
            <td class="v" style="width:22%;font-weight:900;">{{ $task->equipment->internal_id ?? '—' }}</td>
            <td class="l" style="width:14%;">Fecha:</td>
            <td class="v" style="width:18%;">{{ $taskDate?->format('d/m/Y') ?? '—' }}</td>
            <td class="l" style="width:14%;">Reporte N°:</td>
            <td class="v">{{ str_pad($task->id,4,'0',STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="l" style="width:22%;">Tipo Mant.:</td>
            <td class="v" style="width:12%;" colspan="2">
                @php
                    $maintType = $fd['maint_type'] ?? $task->task_type ?? '';
                    $isPreventivo = in_array($maintType, ['maintenance','preventivo','preventive']);
                @endphp
                @if($isPreventivo)
                    <span style="background:#d1fae5;color:#065f46;font-weight:900;font-size:7pt;padding:2px 7px;border-radius:3px;">&#9632; PREVENTIVO</span>
                @else
                    <span style="background:#fee2e2;color:#991b1b;font-weight:900;font-size:7pt;padding:2px 7px;border-radius:3px;">&#9632; CORRECTIVO</span>
                @endif
            </td>
            <td class="l" style="width:14%;">Estatus Inicial:</td>
            <td class="v" style="width:22%;" colspan="2">{{ $task->initial_status ?: '—' }}</td>
        </tr>
        <tr>
            <td class="l">Equipo:</td>
            <td class="v" colspan="3">{{ $task->equipment->name ?? '&mdash;' }}</td>
            <td class="l">Estado:</td>
            <td class="v"><span class="sec-s s-{{ $statusKey }}">{{ $statusLabel }}</span></td>
        </tr>
        <tr>
            <td class="l">Técnico:</td>
            <td class="v" colspan="2"><strong>{{ $task->assignee->name ?? '—' }}</strong></td>
            <td class="l">Cédula:</td>
            <td class="v">{{ $task->assignee->cedula ?? '—' }}</td>
            <td class="v" style="font-size:7pt;">{{ $fd['start_time'] ?? '' }}{{ !empty($fd['start_time'])&&!empty($fd['end_time']) ? ' — ' : '' }}{{ $fd['end_time'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="l">Edificio / Bloque:</td>
            <td class="v">{{ $fd['building'] ?? '—' }}</td>
            <td class="l">Nivel / Piso:</td>
            <td class="v">{{ $fd['floor'] ?? '—' }}</td>
            <td class="l">Prioridad:</td>
            <td class="v">{{ strtoupper($task->priority ?? '—') }}</td>
        </tr>
        <tr>
            <td class="l">Área:</td>
            <td class="v">{{ $fd['specific_area'] ?? '—' }}</td>
            <td class="l">Sección:</td>
            <td class="v">{{ $fd['section'] ?? '—' }}</td>
            <td class="l">Título:</td>
            <td class="v" style="font-size:7.5pt;">{{ $task->title ?? '—' }}</td>
        </tr>
    </table>

    {{-- INSTALACIONES REQUERIDAS — estilo "pape  l rayado" (líneas separadas) --}}
    <div class="sec-y">&#9632; Instalaciones Requeridas</div>
    @php
        // Dividir por salto de línea, asegurando mínimo 4 filas visibles
        $instLines = array_filter(explode("\n", $task->required_installations ?? ''), fn($l) => trim($l) !== '');
        $minRows   = max(4, count($instLines));
        // Rellenar con vacios si hay menos de 4
        while (count($instLines) < 4) { $instLines[] = ''; }
    @endphp
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #16213e;border-top:0;">
        @foreach($instLines as $idx => $line)
        <tr>
        <td style="padding:5px 10px;font-size:8pt;color:#222;border-top:1px solid #e5e7eb;min-height:22px;">{{ trim($line) ?: '' }}&nbsp;</td>
        </tr>
        @endforeach
    </table>

    {{-- OBSERVACIONES --}}
    <div class="sec-y">&#9632; Observaciones del Técnico</div>
    <div class="txt">{{ $task->description ?: 'Sin observaciones adicionales.' }}</div>

    {{-- NOTAS ADMIN --}}
    @if(!empty($task->admin_notes))
    <div class="sec-d mt-8">&#9632; Notas del Administrador</div>
    <div class="txt" style="background:#fff7ed;border-color:#f97316;">{{ $task->admin_notes }}</div>
    @endif

    {{-- CHECKLIST --}}
    <div class="sec-y mt-8">&#9632; Evaluación Técnica de Actividades</div>
    <table class="act-tbl">
        <thead><tr>
            <th style="text-align:left;width:57%;">Actividad / Punto Evaluado</th>
            <th style="width:10%;">Estado</th>
            <th>Comentarios</th>
        </tr></thead>
        <tbody>
        @forelse($evaluation as $e)
        <tr>
            <td>{{ $e['item'] ?? '—' }}</td>
            <td class="center">
                @if(($e['status']??'') === 'SI')<span class="b-si">SI ✓</span>
                @else<span class="b-no">NO ✗</span>@endif
            </td>
            <td style="font-size:7.5pt;color:#555;">{{ $e['comment'] ?? '' }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="color:#aaa;font-style:italic;text-align:center;padding:8px;">Sin evaluación de checklist registrada.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{-- MATERIALES --}}
    @if(count($materials) > 0)
    <div class="sec-y mt-8">&#9632; Materiales e Insumos Utilizados</div>
    <table class="mat-tbl">
        <thead><tr>
            <th style="text-align:left;width:68%;">Material / Insumo</th>
            <th style="text-align:center;">Cantidad</th>
        </tr></thead>
        <tbody>
        @foreach($materials as $m)
        @if(!empty($m['name']))
        <tr>
            <td>{{ $m['name'] }}</td>
            <td class="center" style="font-weight:900;">{{ $m['qty'] ?? '1' }}</td>
        </tr>
        @endif
        @endforeach
        </tbody>
    </table>
    @endif

    {{-- COMPONENTES --}}
    <div class="sec-y mt-8">&#9632; Componentes Instalados / Certificación</div>
    @php
        // Helper para renderizar badges limpios sin checkboxes
        $yesNo = function($val, $yesText='SÍ', $noText='NO') {
            if ($val) {
                return '<span style="background:#d1fae5;color:#065f46;font-weight:900;font-size:7pt;padding:2px 7px;border-radius:3px;letter-spacing:.5px;">'.$yesText.'</span>';
            }
            return '<span style="background:#f3f4f6;color:#9ca3af;font-size:7pt;padding:2px 7px;border-radius:3px;">'.$noText.'</span>';
        };
    @endphp

    {{-- Fila 1: componentes físicos --}}
    <table style="width:100%;border-collapse:collapse;border:1.5px solid #16213e;border-top:0;">
        <tr>
            <td class="l" style="width:22%;border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Cable Nuevo</td>
            <td class="v" style="width:11%;border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo($task->has_new_cable) !!}</td>
            <td class="l" style="width:22%;border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Jack Nuevo</td>
            <td class="v" style="width:11%;border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo($task->has_new_jack) !!}</td>
            <td class="l" style="width:22%;border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Faceplate Nuevo</td>
            <td class="v" style="width:12%;border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo($task->has_new_faceplate) !!}</td>
        </tr>
        <tr>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Certificación Fluke</td>
            <td class="v" style="border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo($task->is_certified, 'PASS', 'PEND') !!}</td>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">
                @if(!empty($fd['fluke_margin'])) Margen dB / Long. @else Mant. Limpieza @endif
            </td>
            <td class="v" style="border:1px solid #9ca3af;padding:5px 8px;text-align:center;">
                @if(!empty($fd['fluke_margin']))
                    <strong>{{ $fd['fluke_margin'] }}</strong> dB / {{ $fd['fluke_length']??'—' }} m
                @else
                    {!! $yesNo(!empty($fd['maint_clean'])) !!}
                @endif
            </td>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Ajuste Conectores</td>
            <td class="v" style="border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo(!empty($fd['maint_cables'])) !!}</td>
        </tr>
        <tr>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Anexo Fotos</td>
            <td class="v" style="border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo(!empty($fd['annex_photos'])) !!}</td>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Planos de Red</td>
            <td class="v" style="border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo(!empty($fd['annex_plans'])) !!}</td>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">Certificaciones</td>
            <td class="v" style="border:1px solid #9ca3af;padding:5px 8px;text-align:center;">{!! $yesNo(!empty($fd['annex_cert'])) !!}</td>
        </tr>
        @if(!empty($fd['new_serial']))
        <tr>
            <td class="l" style="border:1px solid #9ca3af;padding:5px 8px;background:#f9fafb;font-size:7.5pt;font-weight:700;text-transform:uppercase;">S/N Equipo Nuevo</td>
            <td colspan="5" style="border:1px solid #9ca3af;padding:5px 8px;font-family:monospace;font-weight:700;font-size:8.5pt;">{{ $fd['new_serial'] }}</td>
        </tr>
        @endif
    </table>

    {{-- FIRMAS --}}
    <div class="sign-wrap mt-12 clearfix">
        <div class="sign-b">
            <div class="sign-hdr">Técnico Responsable</div>
            <div class="sign-body">
                Nombre: <strong>{{ $task->assignee->name ?? '—' }}</strong><br>
                Cargo: {{ $task->assignee->cargo ?? 'Técnico Especialista' }}<br>
                Cédula: {{ $task->assignee->cedula ?? '—' }}
                <div class="sign-line">Firma del Técnico</div>
            </div>
        </div>
        <div class="sign-b">
            <div class="sign-hdr">Ingeniero Responsable</div>
            <div class="sign-body">
                Nombre: <strong>{{ $engineerName ?: '—' }}</strong><br>
                Cargo: {{ $engineerCargo }}<br>
                Empresa: {{ $companyName }}
                <div class="sign-line">Ingeniero Responsable de Obra</div>
            </div>
        </div>
    </div>

</div>{{-- fin .p-content --}}

{{-- ── FOTOS EN GRILLA (ANTES / DESPUÉS + HALLAZGOS) ── --}}
@if(count($gridPhotos) > 0)
<div class="p-photos">
    {{-- Sub-encabezado estilo referencia: Cliente / Contrato / Fecha / Proyecto --}}
    <table width="100%" style="border-collapse:collapse; margin-bottom:6px; border:1.5px solid #16213e;">
        <tr>
            @if($logoPath)
            <td style="width:18%; padding:5px 8px; border-right:1.5px solid #16213e; text-align:center; vertical-align:middle;">
                <img src="{{ $logoPath }}" style="max-height:30px; max-width:90%; display:block; margin:0 auto;" alt="">
            </td>
            @endif
            <td style="padding:4px 9px; font-size:7pt; border-right:1px solid #d1d5db;">
                <span style="font-weight:700; text-transform:uppercase; font-size:6pt; color:#6b7280;">Cliente &mdash;</span> {{ $companyName }}
            </td>
            @if($contractNumber)
            <td style="padding:4px 9px; font-size:7pt; border-right:1px solid #d1d5db;">
                <span style="font-weight:700; text-transform:uppercase; font-size:6pt; color:#6b7280;">Contrato &mdash;</span> {{ $contractNumber }}
            </td>
            @endif
            <td style="padding:4px 9px; font-size:7pt; text-align:right;">
                <span style="font-weight:700; text-transform:uppercase; font-size:6pt; color:#6b7280;">Fecha &mdash;</span> {{ $taskDate?->format('d/m/Y') ?? $start_date->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding:3px 9px; font-size:6.5pt; color:#374151; border-top:1px solid #e5e7eb;">
                <strong>Proyecto:</strong> {{ $projectName }}
            </td>
        </tr>
    </table>

    <div class="photo-page-hdr">
        &#128247; Registro Fotográfico &mdash; {{ $task->equipment->internal_id ?? '' }}
        @if(!empty($fd['building'])) &nbsp;|&nbsp; {{ $fd['building'] }}@if(!empty($fd['floor'])) / {{ $fd['floor'] }}@endif @endif
    </div>

    @php
        // Obtener dimensiones reales de cada foto para calcular alturas óptimas por fila
        $photoDims = [];
        foreach ($gridPhotos as $k => $gpv) {
            $dims = @getimagesize($gpv['path']);
            $photoDims[$k] = $dims ? ['w'=>$dims[0],'h'=>$dims[1]] : ['w'=>800,'h'=>600];
        }

        // Ancho disponible por columna en mm (p-photos padding 10mm*2, espacio central 3%, col=48%)
        // Ancho total ⋅ 0.48 = col: (215.9-20) ⋅ 0.48 ≈ 93.7mm
        $colMm = 92.0;

        // Para cada par (fila de 2), calcular la altura óptima
        // = mínimo entre la altura natural (colMm/aspectRatio) y 105mm
        $pairsCount = ceil(count($gridPhotos) / 2);
        $rowHeights = [];
        for ($ri = 0; $ri < count($gridPhotos); $ri += 2) {
            $d1 = $photoDims[$ri];
            $h1mm = ($d1['w'] > 0) ? ($colMm * $d1['h'] / $d1['w']) : 70;
            $h2mm = isset($photoDims[$ri+1]) ? ($colMm * $photoDims[$ri+1]['h'] / $photoDims[$ri+1]['w']) : $h1mm;
            // La fila tendrá la altura de la más alta, pero cap a 105mm
            $rowHeights[$ri] = min(max($h1mm, $h2mm), 105);
        }
    @endphp

    @foreach($gridPhotos as $pi => $ph)
    @if($pi % 2 === 0)
    @php
        $rowH   = round($rowHeights[$pi], 1);
        $ph2    = $gridPhotos[$pi+1] ?? null;
    @endphp
    {{-- Tabla para la fila de 2 fotos: garantiza mismo alto sin floats --}}
    <table width="100%" style="border-collapse:collapse; margin-bottom:6px;">
        <tr>
            <td width="48%" style="border:1.5px solid #16213e; padding:0; vertical-align:top;">
                <div class="photo-lbl">Fotografía N°{{ ($pi) + 1 }} &mdash; {{ $ph['label'] }}</div>
                <div class="photo-bg" style="height:{{ $rowH }}mm; overflow:hidden; text-align:center;">
                    <img src="{{ $ph['path'] }}" style="max-width:100%; max-height:{{ $rowH }}mm; height:auto; display:block; margin:0 auto;" alt="">
                </div>
                @if(!empty($ph['caption']))
                <div style="padding:3px 7px;font-size:6.5pt;color:#555;background:#fafafa;border-top:1px solid #e5e7eb;">{{ $ph['caption'] }}</div>
                @endif
            </td>
            <td width="4%" style="padding:0;"></td>
            @if($ph2)
            <td width="48%" style="border:1.5px solid #16213e; padding:0; vertical-align:top;">
                <div class="photo-lbl">Fotografía N°{{ ($pi) + 2 }} &mdash; {{ $ph2['label'] }}</div>
                <div class="photo-bg" style="height:{{ $rowH }}mm; overflow:hidden; text-align:center;">
                    <img src="{{ $ph2['path'] }}" style="max-width:100%; max-height:{{ $rowH }}mm; height:auto; display:block; margin:0 auto;" alt="">
                </div>
                @if(!empty($ph2['caption']))
                <div style="padding:3px 7px;font-size:6.5pt;color:#555;background:#fafafa;border-top:1px solid #e5e7eb;">{{ $ph2['caption'] }}</div>
                @endif
            </td>
            @else
            <td width="48%"></td>
            @endif
        </tr>
    </table>
    @if($pi % 4 === 2 && ($pi + 2) < count($gridPhotos))
    {{-- Solo insertar salto si AÚN HAY más fotos --}}
    <div style="page-break-after:always;"></div>
    <div class="photo-page-hdr">&#128247; Registro Fotográfico (cont.) &mdash; {{ $task->equipment->internal_id ?? '' }}</div>
    @endif
    @endif
    @endforeach
</div>
@endif

{{-- ── CERTIFICACIONES / FLUKE (página completa portrait) ── --}}
@foreach($fullCerts as $cert)
<div class="fullpage-img">
    <div class="fullpage-img-hdr">&#9654; {{ $cert['label'] }}</div>
    <div class="fullpage-img-sub">{{ $cert['sub'] }}</div>
    <div class="fullpage-img-body">
        <img src="{{ $cert['path'] }}" alt="">
    </div>
</div>
@endforeach

{{-- ── PLANOS / MAPAS (página landscape) ── --}}
@foreach($fullMaps as $map)
<div class="fullpage-map">
    <div class="fullpage-map-hdr">&#9660; {{ $map['label'] }} &nbsp;|&nbsp; {{ $map['sub'] }}</div>
    <div class="fullpage-map-body">
        <img src="{{ $map['path'] }}" alt="">
    </div>
</div>
@endforeach

@endforeach{{-- fin foreach tarea --}}
@endforeach{{-- fin foreach sistema --}}

{{-- =====================================================
     RESUMEN DE MATERIALES
     ===================================================== --}}
@if(count($consolidatedMaterials) > 0)
<div class="p-content">
    <div class="prod-hdr">Resumen de Materiales Utilizados &mdash; Período Completo</div>
    <table class="mat-res-tbl">
        <thead><tr>
            <th style="width:8%;text-align:center;">#</th>
            <th style="text-align:left;width:72%;">Material / Insumo</th>
            <th style="width:20%;text-align:center;">Total</th>
        </tr></thead>
        <tbody>
        @foreach($consolidatedMaterials as $mn => $mq)
        <tr>
            <td class="center" style="color:#aaa;">{{ $loop->iteration }}</td>
            <td>{{ $mn }}</td>
            <td class="center" style="font-weight:900;">{{ $mq }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- =====================================================
     TABLA DE CONTROL DE PRODUCCIÓN
     ===================================================== --}}
<div class="p-content" style="page-break-after:auto;">
    <div class="prod-hdr">Control de Producción Estimada &mdash; Ciudad Hospitalaria Dr. Enrique Tejera &mdash; Semana {{ $weekNum }}</div>
    <table class="prod-tbl">
        <thead><tr>
            <th style="width:4%;">#</th>
            <th class="tag" style="width:10%;text-align:left;">Tag</th>
            <th class="sys" style="width:17%;text-align:left;">Sistema</th>
            <th style="text-align:left;width:21%;">Edificio / Área</th>
            <th style="width:9%;">Estado</th>
            <th style="width:6%;">Cable</th>
            <th style="width:6%;">Jack</th>
            <th style="width:6%;">FP</th>
            <th style="width:8%;">Fluke</th>
            <th style="width:6%;">Ficha</th>
            <th style="width:7%;">Fecha</th>
        </tr></thead>
        <tbody>
        @foreach($tasks as $idx => $t)
        @php
            $tfd  = $t->form_data ?? [];
            $tsk  = $t->status ?? 'draft';
            $tlbl = $statusLabels[$tsk] ?? ucfirst($tsk);
        @endphp
        <tr>
            <td class="center" style="color:#aaa;">{{ $idx+1 }}</td>
            <td class="tag">{{ $t->equipment->internal_id ?? '—' }}</td>
            <td class="sys">{{ $t->system->name ?? ($t->equipment->system->name ?? '—') }}</td>
            <td style="text-align:left;font-size:7pt;">{{ $tfd['building'] ?? '—' }}@if(!empty($tfd['floor'])) / {{ $tfd['floor'] }}@endif</td>
            <td><span class="sec-s s-{{ $tsk }}" style="font-size:6pt;">{{ $tlbl }}</span></td>
            <td>@if($t->has_new_cable)<span class="p-si">SI</span>@else<span class="p-no">&mdash;</span>@endif</td>
            <td>@if($t->has_new_jack)<span class="p-si">SI</span>@else<span class="p-no">&mdash;</span>@endif</td>
            <td>@if($t->has_new_faceplate)<span class="p-si">SI</span>@else<span class="p-no">&mdash;</span>@endif</td>
            <td>@if($t->is_certified)<span class="p-pass">PASS</span>@else<span class="p-pend">PEND</span>@endif</td>
            <td style="font-size:7pt;">{{ str_pad($t->id,4,'0',STR_PAD_LEFT) }}</td>
            <td style="font-size:6.5pt;">{{ $t->completed_at?->format('d/m/Y') ?? ($t->updated_at?->format('d/m') ?? '—') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:10px;font-size:7pt;color:#6b7280;border-top:1px solid #e5e7eb;padding-top:6px;">
        <strong>{{ $companyName }}</strong> &mdash;
        Generado: {{ now()->timezone('America/Panama')->translatedFormat('d \d\e F \d\e Y, H:i') }} &mdash;
        Período: {{ $start_date->format('d/m/Y') }} — {{ $end_date->format('d/m/Y') }}
    </div>
</div>

</body>
</html>
