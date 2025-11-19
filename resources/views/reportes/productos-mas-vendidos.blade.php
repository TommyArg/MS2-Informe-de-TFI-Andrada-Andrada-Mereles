@extends('layout.base')

@section('title', 'Productos Más Vendidos')

@section('content')
<h2>Reporte de Productos Más Vendidos</h2>

<a href="{{ route('reportes.index') }}">← Volver a Reportes</a>

<br><br>

<h3>Filtros</h3>
<form method="GET" action="{{ route('reportes.productos-mas-vendidos') }}">
    <table>
        <tr>
            <td>
                <label>Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio', $fechaInicio->format('Y-m-d')) }}">
            </td>
            <td>
                <label>Fecha Fin:</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin', $fechaFin->format('Y-m-d')) }}">
            </td>
            <td>
                <button type="submit">Filtrar</button>
                <a href="{{ route('reportes.productos-mas-vendidos') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Top 10 Productos Más Vendidos 
    ({{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }})</h3>

@if($productos->count() > 0)
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr style="background: #f8f9fa;">
        <th>#</th>
        <th>Producto</th>
        <th>Marca</th>
        <th>Total Vendido</th>
        <th>Porcentaje</th>
    </tr>
    @php
        $totalVendido = $productos->sum('total_vendido');
    @endphp
    @foreach($productos as $index => $producto)
    <tr>
        <td align="center"><strong>{{ $index + 1 }}</strong></td>
        <td>{{ $producto->nombreArticulo }}</td>
        <td>{{ $producto->nombreMarca }}</td>
        <td align="center">
            <strong>{{ number_format($producto->total_vendido) }}</strong> unidades
        </td>
        <td align="center">
            @if($totalVendido > 0)
                {{ number_format(($producto->total_vendido / $totalVendido) * 100, 1) }}%
            @else
                0%
            @endif
        </td>
    </tr>
    @endforeach
    <tr style="background: #e9ecef; font-weight: bold;">
        <td colspan="3" align="right">TOTAL:</td>
        <td align="center">{{ number_format($totalVendido) }} unidades</td>
        <td align="center">100%</td>
    </tr>
</table>

@if($totalVendido > 0)
<br>
<h4>Distribución de Ventas</h4>
<div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
    @foreach($productos as $index => $producto)
        @php
            $porcentaje = ($producto->total_vendido / $totalVendido) * 100;
        @endphp
        <div style="margin-bottom: 5px;">
            <strong>{{ $index + 1 }}. {{ $producto->nombreArticulo }}</strong>
            <div style="background: #007bff; height: 20px; width: {{ $porcentaje }}%; border-radius: 3px;"></div>
            <small>{{ number_format($porcentaje, 1) }}% ({{ number_format($producto->total_vendido) }} unidades)</small>
        </div>
    @endforeach
</div>
@endif

@else
<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">
    <strong>No se encontraron ventas</strong> en el período seleccionado.
</div>
@endif

<br>

<div style="background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px;">
    <strong>Nota:</strong> Este reporte muestra los productos más vendidos en base a los movimientos de salida de stock.
</div>

@endsection