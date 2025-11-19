@extends('layout.base')

@section('title', 'Reporte de Ventas')

@section('content')
<h2>Reporte de Ventas</h2>

<a href="{{ route('reportes.index') }}">← Volver a Reportes</a> |
<a href="{{ route('facturas.index') }}">Ver Facturas</a>

<br><br>

<h3>Filtros del Reporte</h3>
<form method="GET" action="{{ route('reportes.ventas') }}">
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
                <a href="{{ route('reportes.ventas') }}">Restablecer</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Ventas del Período: {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}</h3>

@if($ventas->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Factura</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Subtotal</th>
        <th>IVA</th>
        <th>Descuento</th>
        <th>Total</th>
    </tr>
    @foreach($ventas as $venta)
    <tr>
        <td>{{ $venta->numeroFactura }}</td>
        <td>{{ \Carbon\Carbon::parse($venta->fechaFactura)->format('d/m/Y') }}</td>
        <td>{{ $venta->razonSocial }}</td>
        <td>$ {{ number_format($venta->subtotal, 2) }}</td>
        <td>$ {{ number_format($venta->iva, 2) }}</td>
        <td>$ {{ number_format($venta->descuentoEfectivo, 2) }}</td>
        <td><strong>$ {{ number_format($venta->total, 2) }}</strong></td>
    </tr>
    @endforeach
    <tr style="background-color: #f0f0f0; font-weight: bold;">
        <td colspan="3" align="right">TOTALES:</td>
        <td>$ {{ number_format($ventas->sum('subtotal'), 2) }}</td>
        <td>$ {{ number_format($totalIva, 2) }}</td>
        <td>$ {{ number_format($totalDescuentos, 2) }}</td>
        <td>$ {{ number_format($totalVentas, 2) }}</td>
    </tr>
</table>

<br>
<p><strong>Resumen:</strong> {{ $ventas->count() }} ventas - Total IVA: $ {{ number_format($totalIva, 2) }} - Total Descuentos: $ {{ number_format($totalDescuentos, 2) }}</p>
@else
<p>No hay ventas en el período seleccionado.</p>
@endif
@endsection