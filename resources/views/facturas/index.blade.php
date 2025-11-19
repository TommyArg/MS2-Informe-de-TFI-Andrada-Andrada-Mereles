@extends('layout.base')

@section('title', 'Facturas')

@section('content')
<h2>Gestión de Facturas</h2>

<a href="{{ route('facturas.create') }}">Nueva Factura</a> |
<a href="{{ route('reportes.ventas') }}">Reporte de Ventas</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('facturas.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Número o cliente">
            </td>
            <td>
                <label>Estado:</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendientes</option>
                    <option value="PAGADA" {{ request('estado') == 'PAGADA' ? 'selected' : '' }}>Pagadas</option>
                    <option value="CANCELADA" {{ request('estado') == 'CANCELADA' ? 'selected' : '' }}>Canceladas</option>
                </select>
            </td>
            <td>
                <label>Tipo:</label>
                <select name="tipo">
                    <option value="">Todos</option>
                    <option value="A" {{ request('tipo') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ request('tipo') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ request('tipo') == 'C' ? 'selected' : '' }}>C</option>
                </select>
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('facturas.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Facturas ({{ $facturas->total() }})</h3>
@if($facturas->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Tipo</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    @foreach($facturas as $factura)
    <tr>
        <td>{{ $factura->numeroFactura }}</td>
        <td>{{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}</td>
        <td>{{ $factura->cliente->razonSocial }}</td>
        <td>{{ $factura->tipoFactura }}</td>
        <td>$ {{ number_format($factura->total, 2) }}</td>
        <td>
            @if($factura->estado == 'PENDIENTE')
                <span style="color: orange;">PENDIENTE</span>
            @elseif($factura->estado == 'PAGADA')
                <span style="color: green;">PAGADA</span>
            @else
                <span style="color: red;">CANCELADA</span>
            @endif
        </td>
        <td>
            <a href="{{ route('facturas.show', $factura->idFactura) }}">Ver</a> |
            <a href="{{ route('facturas.print', $factura->idFactura) }}" target="_blank">Imprimir</a> |
            <a href="{{ route('facturas.edit', $factura->idFactura) }}">Editar</a> |
            <form action="{{ route('facturas.destroy', $factura->idFactura) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar esta factura?')">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $facturas->links() }}
</div>
@else
<p>No se encontraron facturas.</p>
@endif
@endsection