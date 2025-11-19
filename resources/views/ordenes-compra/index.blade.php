@extends('layout.base')

@section('title', 'Órdenes de Compra')

@section('content')
<h2>Gestión de Órdenes de Compra</h2>

<a href="{{ route('ordenes-compra.create') }}">Nueva Orden de Compra</a> |
<a href="{{ route('proveedores.index') }}">Proveedores</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('ordenes-compra.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Comprobante o proveedor">
            </td>
            <td>
                <label>Estado:</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                    <option value="RECIBIDA" {{ request('estado') == 'RECIBIDA' ? 'selected' : '' }}>Recibida</option>
                    <option value="CANCELADA" {{ request('estado') == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('ordenes-compra.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Órdenes de Compra ({{ $ordenes->total() }})</h3>
@if($ordenes->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Comprobante</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    @foreach($ordenes as $orden)
    <tr>
        <td>{{ $orden->comprobanteOC }}</td>
        <td>{{ \Carbon\Carbon::parse($orden->fechaOC)->format('d/m/Y') }}</td>
        <td>{{ $orden->proveedor->razonSocialProveedor }}</td>
        <td>
            @if($orden->estado == 'PENDIENTE')
                <span style="color: orange;">PENDIENTE</span>
            @elseif($orden->estado == 'RECIBIDA')
                <span style="color: green;">RECIBIDA</span>
            @else
                <span style="color: red;">CANCELADA</span>
            @endif
        </td>
        <td>
            <a href="{{ route('ordenes-compra.show', $orden->idOrdenCompra) }}">Ver</a> |
            <a href="{{ route('ordenes-compra.edit', $orden->idOrdenCompra) }}">Editar</a> |
            <a href="{{ route('ordenes-compra.print', $orden->idOrdenCompra) }}" target="_blank">Imprimir</a>
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $ordenes->links() }}
</div>
@else
<p>No se encontraron órdenes de compra.</p>
@endif
@endsection