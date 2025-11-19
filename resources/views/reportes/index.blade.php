@extends('layout.base')

@section('title', 'Reportes')

@section('content')
<h2>Sistema de Reportes</h2>

<a href="{{ route('dashboard') }}">← Volver al Dashboard</a>

<br><br>

<h3>Reportes Disponibles</h3>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <td width="50%" valign="top">
            <h4>📊 Reporte de Ventas</h4>
            <p>Ventas realizadas en un período específico con detalles de facturas.</p>
            <a href="{{ route('reportes.ventas') }}">Ver Reporte de Ventas</a>
        </td>
        <td width="50%" valign="top">
            <h4>📦 Reporte de Stock</h4>
            <p>Estado actual del inventario con alertas de stock bajo.</p>
            <a href="{{ route('reportes.stock') }}">Ver Reporte de Stock</a>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <h4>🔄 Reporte de Movimientos</h4>
            <p>Historial completo de entradas y salidas de stock.</p>
            <a href="{{ route('reportes.movimientos') }}">Ver Reporte de Movimientos</a>
        </td>
        <td valign="top">
            <h4>🏆 Productos Más Vendidos</h4>
            <p>Top 10 de productos más vendidos en un período.</p>
            <a href="{{ route('reportes.productos-mas-vendidos') }}">Ver Productos Más Vendidos</a>
        </td>
    </tr>
</table>

<br>

<h3>Estadísticas Rápidas</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Total Productos</th>
        <th>Clientes Activos</th>
        <th>Proveedores</th>
        <th>Stock Total</th>
        <th>Ventas del Mes</th>
    </tr>
    <tr>
        <td align="center">{{ \App\Models\Articulo::count() }}</td>
        <td align="center">{{ \App\Models\Cliente::where('estado', 'ACTIVO')->count() }}</td>
        <td align="center">{{ \App\Models\Proveedor::count() }}</td>
        <td align="center">{{ \App\Models\StockPorAlmacen::sum('cantidadActual') }}</td>
        <td align="center">$ {{ number_format(\App\Models\Factura::where('estado', 'PAGADA')->whereMonth('fechaFactura', now()->month)->sum('total'), 2) }}</td>
    </tr>
</table>
@endsection