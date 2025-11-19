@extends('layout.base')

@section('title', 'Cotizaciones')

@section('content')
<h2>Gestión de Cotizaciones</h2>

<a href="{{ route('cotizaciones.create') }}">Nueva Cotización</a> |
<a href="{{ route('clientes.index') }}">Clientes</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('cotizaciones.index') }}">
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
                    <option value="APROBADA" {{ request('estado') == 'APROBADA' ? 'selected' : '' }}>Aprobadas</option>
                    <option value="RECHAZADA" {{ request('estado') == 'RECHAZADA' ? 'selected' : '' }}>Rechazadas</option>
                </select>
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('cotizaciones.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Cotizaciones ({{ $cotizaciones->total() }})</h3>
@if($cotizaciones->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Validez</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    @foreach($cotizaciones as $cotizacion)
    <tr>
        <td>{{ $cotizacion->numeroCotizacion }}</td>
        <td>{{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->format('d/m/Y') }}</td>
        <td>{{ $cotizacion->cliente->razonSocial }}</td>
        <td>{{ $cotizacion->validezDias }} días</td>
        <td>$ {{ number_format($cotizacion->total, 2) }}</td>
        <td>
            @if($cotizacion->estado == 'PENDIENTE')
                <span style="color: orange;">PENDIENTE</span>
            @elseif($cotizacion->estado == 'APROBADA')
                <span style="color: green;">APROBADA</span>
            @else
                <span style="color: red;">RECHAZADA</span>
            @endif
        </td>
        <td>
            <a href="{{ route('cotizaciones.show', $cotizacion->idCotizacion) }}">Ver</a> |
            <a href="{{ route('cotizaciones.print', $cotizacion->idCotizacion) }}" target="_blank">Imprimir</a> |
            <a href="{{ route('cotizaciones.edit', $cotizacion->idCotizacion) }}">Editar</a> |
            <form action="{{ route('cotizaciones.destroy', $cotizacion->idCotizacion) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar esta cotización?')">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $cotizaciones->links() }}
</div>
@else
<p>No se encontraron cotizaciones.</p>
@endif
@endsection