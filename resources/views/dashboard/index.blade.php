@extends('layout.base')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard Principal</h2>

<h3>Estadísticas</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Productos</th>
        <th>Clientes</th>
        <th>Proveedores</th>
        <th>Stock Total</th>
        <th>Ventas del Mes</th>
        <th>Órdenes Pendientes</th>
    </tr>
    <tr>
        <td align="center">{{ $stats['total_productos'] }}</td>
        <td align="center">{{ $stats['total_clientes'] }}</td>
        <td align="center">{{ $stats['total_proveedores'] }}</td>
        <td align="center">{{ $stats['stock_total'] }}</td>
        <td align="center">$ {{ number_format($stats['ventas_mes'], 2) }}</td>
        <td align="center">{{ $stats['ordenes_pendientes'] }}</td>
    </tr>
</table>

<br>

<h3>Acciones Rápidas</h3>
<a href="{{ route('movimientos.entrada') }}">Nueva Entrada</a> |
<a href="{{ route('movimientos.salida') }}">Nueva Salida</a> |
<a href="{{ route('cotizaciones.create') }}">Nueva Cotización</a> |
<a href="{{ route('facturas.create') }}">Nueva Factura</a> |
<a href="{{ route('ordenes-compra.create') }}">Nueva Orden Compra</a>

<br><br>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
    <div>
        <h3>Movimientos Recientes</h3>
        @if($movimientos_recientes->count() > 0)
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <th>Tipo</th>
                <th>Almacén</th>
                <th>Fecha</th>
                <th>Usuario</th>
            </tr>
            @foreach($movimientos_recientes as $movimiento)
            <tr>
                <td>
                    @if($movimiento->tipoMovimiento == 'ENTRADA')
                        <span style="color: green;">ENTRADA</span>
                    @else
                        <span style="color: red;">SALIDA</span>
                    @endif
                </td>
                <td>{{ $movimiento->nombreAlmacen }}</td>
                <td>{{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y H:i') }}</td>
                <td>{{ $movimiento->usuario }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>No hay movimientos recientes.</p>
        @endif
    </div>

    <div>
        <h3>Stock Bajo</h3>
        @if($stock_bajo->count() > 0)
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <th>Producto</th>
                <th>Almacén</th>
                <th>Stock</th>
            </tr>
            @foreach($stock_bajo as $stock)
            <tr>
                <td>{{ $stock->nombreArticulo }}</td>
                <td>{{ $stock->nombreAlmacen }}</td>
                <td style="color: red;"><strong>{{ $stock->cantidadActual }}</strong></td>
            </tr>
            @endforeach
        </table>
        @else
        <p>✅ Todo el stock está en niveles normales.</p>
        @endif
    </div>
</div>

<br>

<h3>Productos Más Vendidos del Mes</h3>
@if($productos_mas_vendidos->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Unidades Vendidas</th>
    </tr>
    @foreach($productos_mas_vendidos as $producto)
    <tr>
        <td>{{ $producto->nombreArticulo }}</td>
        <td>{{ $producto->total_vendido }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay datos de ventas este mes.</p>
@endif

<br>

<h3>Módulos del Sistema</h3>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <td align="center">
            <strong>📦 Inventario</strong><br>
            <a href="{{ route('stock.index') }}">Stock</a> |
            <a href="{{ route('movimientos.index') }}">Movimientos</a> |
            <a href="{{ route('productos.index') }}">Productos</a>
        </td>
        <td align="center">
            <strong>💰 Ventas</strong><br>
            <a href="{{ route('clientes.index') }}">Clientes</a> |
            <a href="{{ route('cotizaciones.index') }}">Cotizaciones</a> |
            <a href="{{ route('facturas.index') }}">Facturas</a>
        </td>
        <td align="center">
            <strong>🛒 Compras</strong><br>
            <a href="{{ route('proveedores.index') }}">Proveedores</a> |
            <a href="{{ route('ordenes-compra.index') }}">Órdenes Compra</a>
        </td>
        <td align="center">
            <strong>📊 Reportes</strong><br>
            <a href="{{ route('reportes.index') }}">Ver Reportes</a> |
            <a href="{{ route('precios.index') }}">Precios</a>
        </td>
    </tr>
</table>
@endsection