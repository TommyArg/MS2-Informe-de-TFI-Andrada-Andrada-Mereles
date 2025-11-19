@extends('layout.base')

@section('title', 'Ver Factura')

@section('content')
<h2>Factura: {{ $factura->numeroFactura }}</h2>

<a href="{{ route('facturas.index') }}">← Volver a Facturas</a> |
<a href="{{ route('facturas.print', $factura->idFactura) }}" target="_blank">Imprimir</a> |
<a href="{{ route('facturas.edit', $factura->idFactura) }}">Editar</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número de Factura</th>
        <td>{{ $factura->numeroFactura }}</td>
    </tr>
    <tr>
        <th>Fecha</th>
        <td>{{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <th>Cliente</th>
        <td>{{ $factura->cliente->razonSocial }}</td>
    </tr>
    <tr>
        <th>CUIT Cliente</th>
        <td>{{ $factura->cliente->cuit }}</td>
    </tr>
    <tr>
        <th>Tipo</th>
        <td>{{ $factura->tipoFactura }}</td>
    </tr>
    <tr>
        <th>Movimiento</th>
        <td>#{{ $factura->movimiento->idMovimiento }} - {{ $factura->movimiento->almacen->nombreAlmacen }}</td>
    </tr>
    <tr>
        <th>Estado</th>
        <td>
            @if($factura->estado == 'PENDIENTE')
                <span style="color: orange; font-weight: bold;">PENDIENTE</span>
            @elseif($factura->estado == 'PAGADA')
                <span style="color: green; font-weight: bold;">PAGADA</span>
            @else
                <span style="color: red; font-weight: bold;">CANCELADA</span>
            @endif
        </td>
    </tr>
</table>

<br>

<h3>Detalle de Productos</h3>
@if($factura->movimiento->detalles->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
    </tr>
    @foreach($factura->movimiento->detalles as $detalle)
    @php
        // Usar el método del controlador de cotizaciones para obtener el precio
        $precioVenta = app('App\Http\Controllers\CotizacionController')->getPrecioActual($detalle->idArticuloMarca);
        $subtotal = $detalle->cantidad * $precioVenta;
    @endphp
    <tr>
        <td>{{ $detalle->articuloMarca->articulo->nombreArticulo }}</td>
        <td>{{ $detalle->articuloMarca->marca->nombreMarca }}</td>
        <td>{{ $detalle->cantidad }}</td>
        <td>$ {{ number_format($precioVenta, 2) }}</td>
        <td>$ {{ number_format($subtotal, 2) }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4" align="right"><strong>Subtotal:</strong></td>
        <td><strong>$ {{ number_format($factura->subtotal + $factura->descuentoEfectivo, 2) }}</strong></td>
    </tr>
    @if($factura->descuentoEfectivo > 0)
    <tr>
        <td colspan="4" align="right"><strong>Descuento en Efectivo:</strong></td>
        <td><strong style="color: green;">- $ {{ number_format($factura->descuentoEfectivo, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="4" align="right"><strong>Subtotal con Descuento:</strong></td>
        <td><strong>$ {{ number_format($factura->subtotal, 2) }}</strong></td>
    </tr>
    @endif
    <tr>
        <td colspan="4" align="right"><strong>IVA (21%):</strong></td>
        <td><strong>$ {{ number_format($factura->iva, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="4" align="right"><strong>TOTAL:</strong></td>
        <td><strong style="font-size: 1.2em;">$ {{ number_format($factura->total, 2) }}</strong></td>
    </tr>
</table>
@else
<p>No hay productos en esta factura.</p>
@endif

<br>

@if($factura->estado == 'PENDIENTE')
<h3>Cambiar Estado</h3>
<form action="{{ route('facturas.estado', [$factura->idFactura, 'PAGADA']) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" style="background: green; color: white;">Marcar como Pagada</button>
</form>
<form action="{{ route('facturas.estado', [$factura->idFactura, 'CANCELADA']) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" style="background: red; color: white;">Cancelar Factura</button>
</form>
@endif
@endsection