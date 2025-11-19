@extends('layout.base')

@section('title', 'Ver Orden de Compra')

@section('content')
<h2>Orden de Compra: {{ $orden->comprobanteOC }}</h2>

<a href="{{ route('ordenes-compra.index') }}">← Volver a Órdenes</a> |
<a href="{{ route('ordenes-compra.print', $orden->idOrdenCompra) }}" target="_blank">Imprimir</a> |
<a href="{{ route('ordenes-compra.edit', $orden->idOrdenCompra) }}">Editar</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Comprobante</th>
        <td>{{ $orden->comprobanteOC }}</td>
    </tr>
    <tr>
        <th>Fecha</th>
        <td>{{ \Carbon\Carbon::parse($orden->fechaOC)->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Proveedor</th>
        <td>{{ $orden->proveedor->razonSocialProveedor }}</td>
    </tr>
    <tr>
        <th>CUIT Proveedor</th>
        <td>{{ $orden->proveedor->cuitProveedor }}</td>
    </tr>
    <tr>
        <th>Empresa</th>
        <td>{{ $orden->empresa->razonSocial ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Estado</th>
        <td>
            @if($orden->estado == 'PENDIENTE')
                <span style="color: orange; font-weight: bold;">PENDIENTE</span>
            @elseif($orden->estado == 'RECIBIDA')
                <span style="color: green; font-weight: bold;">RECIBIDA</span>
            @else
                <span style="color: red; font-weight: bold;">CANCELADA</span>
            @endif
        </td>
    </tr>
</table>

<br>

<h3>Detalle de Productos</h3>
@if($orden->detalles->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
    </tr>
    @php
        $subtotalTotal = 0;
    @endphp
    @foreach($orden->detalles as $detalle)
    @php
        $subtotal = $detalle->cantidad * $detalle->precioUnitario;
        $subtotalTotal += $subtotal;
    @endphp
    <tr>
        <td>{{ $detalle->articuloMarca->articulo->nombreArticulo }}</td>
        <td>{{ $detalle->articuloMarca->marca->nombreMarca }}</td>
        <td>{{ $detalle->cantidad }}</td>
        <td>$ {{ number_format($detalle->precioUnitario, 2) }}</td>
        <td>$ {{ number_format($subtotal, 2) }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4" align="right"><strong>TOTAL:</strong></td>
        <td><strong style="font-size: 1.2em;">$ {{ number_format($subtotalTotal, 2) }}</strong></td>
    </tr>
</table>
@else
<p>No hay productos en esta orden de compra.</p>
@endif

<br>

@if($orden->estado == 'PENDIENTE')
<h3>Cambiar Estado</h3>
<form action="{{ route('ordenes-compra.estado', [$orden->idOrdenCompra, 'RECIBIDA']) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" style="background: green; color: white;">Marcar como Recibida</button>
</form>
<form action="{{ route('ordenes-compra.estado', [$orden->idOrdenCompra, 'CANCELADA']) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" style="background: red; color: white;">Cancelar Orden</button>
</form>
@endif
@endsection