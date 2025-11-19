@extends('layout.base')

@section('title', 'Movimientos')

@section('content')
<h2>Historial de Movimientos</h2>

<a href="{{ route('movimientos.entrada') }}">Nueva Entrada</a> |
<a href="{{ route('movimientos.salida') }}">Nueva Salida</a> |
<a href="{{ route('remitos.index') }}">Ver Remitos</a>

<br><br>

<h3>Últimos Movimientos</h3>
@if($movimientos->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Fecha</th>
        <th>Almacén</th>
        <th>Usuario</th>
        <th>Productos</th>
        <th>Observaciones</th>
        <th>Acciones</th>
    </tr>
    @foreach($movimientos as $movimiento)
    <tr>
        <td>{{ $movimiento->idMovimiento }}</td>
        <td>
            @if($movimiento->tipoMovimiento == 'ENTRADA')
                <span style="color: green;">ENTRADA</span>
            @else
                <span style="color: red;">SALIDA</span>
            @endif
        </td>
        <td>{{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y H:i') }}</td>
        <td>{{ $movimiento->nombreAlmacen }}</td>
        <td>{{ $movimiento->usuario }}</td>
        <td>{{ $movimiento->detalles->count() }} productos</td>
        <td>{{ $movimiento->observaciones }}</td>
        <td>
            <a href="{{ route('movimientos.show', $movimiento->idMovimiento) }}">Ver</a>
            @if($movimiento->idRemito)
                | <a href="{{ route('remitos.show', $movimiento->idRemito) }}">Remito</a>
            @endif
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $movimientos->links() }}
</div>
@else
<p>No hay movimientos registrados.</p>
@endif
@endsection