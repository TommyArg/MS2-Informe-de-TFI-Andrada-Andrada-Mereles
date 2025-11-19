@extends('layout.base')

@section('title', 'Remitos')

@section('content')
<h2>Gestión de Remitos</h2>

<a href="{{ route('movimientos.index') }}">← Volver a Movimientos</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('remitos.index') }}">
    <table>
        <tr>
            <td>
                <label>Tipo:</label>
                <select name="tipo">
                    <option value="">Todos los tipos</option>
                    <option value="ENTRADA" {{ request('tipo') == 'ENTRADA' ? 'selected' : '' }}>Entrada</option>
                    <option value="SALIDA" {{ request('tipo') == 'SALIDA' ? 'selected' : '' }}>Salida</option>
                </select>
            </td>
            <td>
                <label>Fecha desde:</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}">
            </td>
            <td>
                <label>Fecha hasta:</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('remitos.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Remitos ({{ $remitos->total() }})</h3>
@if($remitos->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número Remito</th>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>Almacén</th>
        <th>Movimiento</th>
        <th>Observaciones</th>
        <th>Acciones</th>
    </tr>
    @foreach($remitos as $remito)
    <tr>
        <td>{{ $remito->numeroRemito }}</td>
        <td>
            @if($remito->tipoRemito == 'ENTRADA')
                <span style="color: green;">ENTRADA</span>
            @else
                <span style="color: red;">SALIDA</span>
            @endif
        </td>
        <td>{{ \Carbon\Carbon::parse($remito->fechaRemito)->format('d/m/Y') }}</td>
        <td>{{ $remito->nombreAlmacen }}</td>
        <td>#{{ $remito->idMovimiento }}</td>
        <td>{{ $remito->observaciones ?? 'Ninguna' }}</td>
        <td>
            <a href="{{ route('remitos.show', $remito->idRemito) }}">Ver</a> |
            <a href="{{ route('remitos.print', $remito->idRemito) }}" target="_blank">Imprimir</a>
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $remitos->links() }}
</div>
@else
<p>No se encontraron remitos.</p>
@endif
@endsection