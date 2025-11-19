@extends('layout.base')

@section('title', 'Reporte de Movimientos')

@section('content')
<h2>Reporte de Movimientos</h2>

<a href="{{ route('reportes.index') }}">← Volver a Reportes</a> |
<a href="{{ route('movimientos.index') }}">Ver Movimientos</a>

<br><br>

<h3>Filtros del Reporte</h3>
<form method="GET" action="{{ route('reportes.movimientos') }}">
    <table>
        <tr>
            <td><label>Fecha desde:</label></td>
            <td><input type="date" name="fecha_inicio" value="{{ $fechaInicio->format('Y-m-d') }}"></td>
        </tr>
        <tr>
            <td><label>Fecha hasta:</label></td>
            <td><input type="date" name="fecha_hasta" value="{{ $fechaFin->format('Y-m-d') }}"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Generar Reporte</button>
                <a href="{{ route('reportes.movimientos') }}">Restablecer</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Movimientos del Período: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}</h3>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Resumen</th>
        <td><strong>Entradas:</strong> {{ $entradas }}</td>
        <td><strong>Salidas:</strong> {{ $salidas }}</td>
        <td><strong>Total:</strong> {{ $movimientos->count() }}</td>
    </tr>
</table>

<br>

@if($movimientos->count() > 0)
@foreach($movimientos as $movimiento)
<div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">
    <table width="100%">
        <tr>
            <td><strong>Movimiento #{{ $movimiento->idMovimiento }}</strong></td>
            <td><strong>Tipo:</strong> 
                @if($movimiento->tipoMovimiento == 'ENTRADA')
                    <span style="color: green;">ENTRADA</span>
                @else
                    <span style="color: red;">SALIDA</span>
                @endif
            </td>
            <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y H:i') }}</td>
            <td><strong>Almacén:</strong> {{ $movimiento->nombreAlmacen }}</td>
        </tr>
        <tr>
            <td colspan="4"><strong>Usuario:</strong> {{ $movimiento->usuario }}</td>
        </tr>
        @if($movimiento->observaciones)
        <tr>
            <td colspan="4"><strong>Observaciones:</strong> {{ $movimiento->observaciones }}</td>
        </tr>
        @endif
    </table>
    
    @if($movimiento->detalles->count() > 0)
    <table border="1" cellpadding="3" cellspacing="0" width="100%" style="margin-top: 5px;">
        <tr style="background-color: #f0f0f0;">
            <th>Producto</th>
            <th>Marca</th>
            <th>Cantidad</th>
        </tr>
        @foreach($movimiento->detalles as $detalle)
        <tr>
            <td>{{ $detalle->nombreArticulo }}</td>
            <td>{{ $detalle->nombreMarca }}</td>
            <td>{{ $detalle->cantidad }}</td>
        </tr>
        @endforeach
    </table>
    @endif
</div>
@endforeach
@else
<p>No hay movimientos en el período seleccionado.</p>
@endif
@endsection