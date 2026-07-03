<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; background:#fff; }
  .header { background: #1a4f00; color: #fff; padding: 18px 24px; display:flex; justify-content:space-between; align-items:center; }
  .header h1 { font-size: 18px; font-weight: 700; }
  .header p { font-size: 10px; opacity: .8; }
  .section { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; }
  .section-title { font-size: 13px; font-weight: 700; color: #39A900; border-bottom: 2px solid #39A900; padding-bottom: 4px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: .05em; }
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }
  .info-item { margin-bottom: 5px; }
  .info-label { font-weight: 700; color: #6b7280; font-size: 9px; text-transform: uppercase; }
  .info-value { font-size: 11px; }
  table { width: 100%; border-collapse: collapse; font-size: 10px; }
  th { background: #f3f4f6; padding: 5px 8px; text-align: left; font-size: 9px; text-transform: uppercase; color: #6b7280; border: 1px solid #e5e7eb; }
  td { padding: 5px 8px; border: 1px solid #e5e7eb; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: 700; }
  .badge-green { background: #d1fae5; color: #065f46; }
  .badge-blue { background: #dbeafe; color: #1e40af; }
  .badge-yellow { background: #fef3c7; color: #92400e; }
  .badge-red { background: #fee2e2; color: #991b1b; }
  .stat-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; text-align: center; }
  .stat-num { font-size: 22px; font-weight: 700; color: #39A900; }
  .stat-lbl { font-size: 9px; color: #6b7280; text-transform: uppercase; }
  .progress-wrap { background: #e5e7eb; border-radius: 6px; height: 8px; width: 100%; margin-top: 3px; }
  .progress-bar { height: 8px; border-radius: 6px; }
  .footer { padding: 10px 24px; background: #f9fafb; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
</style>
</head>
<body>

<div class="header">
    <div>
        <h1>SENA — Hoja de Vida Académica</h1>
        <p>Sistema de Seguimiento al Aprendiz (SSA) • Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <div style="text-align:right;">
        <div style="font-size:16px; font-weight:700;">{{ $aprendiz->ficha }}</div>
        <div style="font-size:10px; opacity:.8;">Ficha de Formación</div>
    </div>
</div>

{{-- Datos personales --}}
<div class="section">
    <div class="section-title">Datos del Aprendiz</div>
    <div class="grid-2">
        <div>
            <div class="info-item"><div class="info-label">Nombre Completo</div><div class="info-value" style="font-size:14px; font-weight:700;">{{ $aprendiz->user->name }}</div></div>
            <div class="info-item"><div class="info-label">Documento</div><div class="info-value">{{ $aprendiz->documento }}</div></div>
            <div class="info-item"><div class="info-label">Correo Electrónico</div><div class="info-value">{{ $aprendiz->user->email }}</div></div>
            <div class="info-item"><div class="info-label">Teléfono</div><div class="info-value">{{ $aprendiz->telefono ?? $aprendiz->user->telefono ?? 'N/A' }}</div></div>
        </div>
        <div>
            <div class="info-item"><div class="info-label">Programa de Formación</div><div class="info-value" style="font-weight:700;">{{ $aprendiz->programa_formacion }}</div></div>
            <div class="info-item"><div class="info-label">Número de Ficha</div><div class="info-value">{{ $aprendiz->ficha }}</div></div>
            <div class="info-item"><div class="info-label">Fecha de Inicio</div><div class="info-value">{{ $aprendiz->fecha_inicio->format('d/m/Y') }}</div></div>
            <div class="info-item"><div class="info-label">Estado</div><div class="info-value">{{ ucfirst($aprendiz->estado) }}</div></div>
        </div>
    </div>
</div>

{{-- Resumen académico --}}
<div class="section">
    <div class="section-title">Resumen Académico</div>
    <div class="grid-3" style="margin-bottom:4px;">
        <div class="stat-box">
            <div class="stat-num">{{ number_format($definitiva,1) }}</div>
            <div class="stat-lbl">Definitiva Final</div>
        </div>
        <div class="stat-box">
            <div class="stat-num" style="color:#f59e0b;">{{ number_format($indiceIntegral,1) }}</div>
            <div class="stat-lbl">Índice Integral</div>
        </div>
        <div class="stat-box">
            <div class="stat-num" style="color:#3b82f6;">{{ $aprendiz->calificaciones->count() }}</div>
            <div class="stat-lbl">Actividades Calificadas</div>
        </div>
    </div>
</div>

{{-- Calificaciones --}}
@if($aprendiz->calificaciones->isNotEmpty())
<div class="section">
    <div class="section-title">Calificaciones por Actividad</div>
    <table>
        <thead><tr><th>Actividad</th><th>Nota</th><th>Instructor</th><th>Observación</th></tr></thead>
        <tbody>
        @foreach($aprendiz->calificaciones as $cal)
        <tr>
            <td>{{ $cal->actividad->titulo }}</td>
            <td style="text-align:center; font-weight:700;">{{ number_format($cal->nota,1) }}</td>
            <td>{{ $cal->instructor->name }}</td>
            <td style="color:#6b7280;">{{ $cal->observacion ?? '—' }}</td>
        </tr>
        @endforeach
        <tr style="background:#f9fafb; font-weight:700;">
            <td colspan="3">Promedio (Definitiva)</td>
            <td style="text-align:center; color:#39A900; font-size:14px;">{{ number_format($definitiva,1) }}</td>
        </tr>
        </tbody>
    </table>
</div>
@endif

{{-- Evaluación Integral --}}
@if(!empty(array_filter($promediosCriterios)))
<div class="section">
    <div class="section-title">Evaluación Integral — Índice: {{ number_format($indiceIntegral,1) }}/10</div>
    <div class="grid-3">
    @foreach($criteriosLabels as $key => $label)
    @php $val = $promediosCriterios[$key] ?? 0; $pct = $val * 10; @endphp
    <div style="margin-bottom:8px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
            <span style="font-size:10px; font-weight:700;">{{ $label }}</span>
            <span style="font-size:10px;">{{ number_format($val,1) }}/10</span>
        </div>
        <div class="progress-wrap">
            <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $pct>=70?'#39A900':($pct>=40?'#f59e0b':'#ef4444') }};"></div>
        </div>
    </div>
    @endforeach
    </div>
</div>
@endif

{{-- Observaciones --}}
@if($aprendiz->observaciones->isNotEmpty())
<div class="section">
    <div class="section-title">Observaciones</div>
    @foreach($aprendiz->observaciones as $obs)
    <div style="margin-bottom:8px; padding:6px; background:#f9fafb; border-left:3px solid {{ $obs->tipo=='academica'?'#3b82f6':($obs->tipo=='disciplinaria'?'#ef4444':'#9ca3af') }};">
        <div style="font-size:9px; color:#6b7280; margin-bottom:2px;">
            <strong>{{ strtoupper($obs->tipo) }}</strong> — {{ $obs->instructor->name }} — {{ $obs->created_at->format('d/m/Y') }}
        </div>
        <div style="font-size:10px;">{{ $obs->contenido }}</div>
    </div>
    @endforeach
</div>
@endif

<div class="footer">
    SENA — Servicio Nacional de Aprendizaje | Sistema de Seguimiento al Aprendiz (SSA) | Documento generado automáticamente
</div>
</body>
</html>
