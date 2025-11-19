@extends('layout.base')

@section('title', 'Ver Remito')

@section('content')
<h2>Remito: {{ $remito->numeroRemito }}</h2>

<a href="{{ route('remitos.index') }}">← Volver a Remitos</a> |
<a href="{{ route('remitos.print', $remito->idRemito) }}" target="_blank">Imprimir</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número de Remito</th>
        <td>{{ $remito->numeroRemito }}</td>
    </tr>
    <tr>
        <th>Tipo</th>
        <td>
            @if($remito->tipoRemito == 'ENTRADA')
                <span style="color: green; font-weight: bold;">ENTRADA</span>
            @else
                <span style="color: red; font-weight: bold;">SALIDA</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Fecha</th>
        <td>{{ \Carbon\Carbon::parse($remito->fechaRemito)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <th>Almacén</th>
        <td>{{ $remito->nombreAlmacen }}</td>
    </tr>
    <tr>
        <th>Movimiento Asociado</th>
        <td>#{{ $remito->idMovimiento }}</td>
    </tr>
    <tr>
        <th>Usuario</th>
        <td>{{ $remito->usuario }}</td>
    </tr>
    <tr>
        <th>Observaciones</th>
        <td>{{ $remito->observaciones }}</td>
    </tr>
</table>

<br>

<h3>Productos</h3>
@if($detalles->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Cantidad</th>
    </tr>
    @foreach($detalles as $detalle)
    <tr>
        <td>{{ $detalle->nombreArticulo }}</td>
        <td>{{ $detalle->nombreMarca }}</td>
        <td>{{ $detalle->cantidad }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay productos en este remito.</p>
@endif
@endsection