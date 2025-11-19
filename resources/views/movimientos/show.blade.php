@extends('layout.base')

@section('title', 'Ver Movimiento')

@section('content')
<h2>Detalles del Movimiento #{{ $movimiento->idMovimiento }}</h2>

<a href="{{ route('movimientos.index') }}">← Volver a Movimientos</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID Movimiento</th>
        <td>{{ $movimiento->idMovimiento }}</td>
    </tr>
    <tr>
        <th>Tipo</th>
        <td>
            @if($movimiento->tipoMovimiento == 'ENTRADA')
                <span style="color: green; font-weight: bold;">ENTRADA</span>
            @else
                <span style="color: red; font-weight: bold;">SALIDA</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Fecha</th>
        <td>{{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Almacén</th>
        <td>{{ $movimiento->nombreAlmacen }}</td> {{-- Cambiado --}}
    </tr>
    <tr>
        <th>Usuario</th>
        <td>{{ $movimiento->usuario }}</td> {{-- Cambiado --}}
    </tr>
    <tr>
        <th>Observaciones</th>
        <td>{{ $movimiento->observaciones ?? 'Ninguna' }}</td>
    </tr>
    @if($movimiento->idRemito)
    <tr>
        <th>Remito</th>
        <td>
            <a href="{{ route('remitos.show', $movimiento->idRemito) }}">
                {{ $movimiento->numeroRemito }}
            </a>
        </td>
    </tr>
    @endif
</table>

<br>

<h3>Productos del Movimiento</h3>
@if($detalles->count() > 0) {{-- Cambiado --}}
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Cantidad</th>
    </tr>
    @foreach($detalles as $detalle) {{-- Cambiado --}}
    <tr>
        <td>{{ $detalle->nombreArticulo }}</td> {{-- Cambiado --}}
        <td>{{ $detalle->nombreMarca }}</td> {{-- Cambiado --}}
        <td>{{ $detalle->cantidad }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay productos en este movimiento.</p>
@endif
@endsection