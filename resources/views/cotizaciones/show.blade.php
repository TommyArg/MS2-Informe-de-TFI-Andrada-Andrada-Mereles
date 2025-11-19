@extends('layout.base')

@section('title', 'Ver Cotización')

@section('content')
<h2>Cotización: {{ $cotizacion->numeroCotizacion }}</h2>

<a href="{{ route('cotizaciones.index') }}">← Volver a Cotizaciones</a> |
<a href="{{ route('cotizaciones.print', $cotizacion->idCotizacion) }}" target="_blank">Imprimir</a> |
<a href="{{ route('cotizaciones.edit', $cotizacion->idCotizacion) }}">Editar</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número de Cotización</th>
        <td>{{ $cotizacion->numeroCotizacion }}</td>
    </tr>
    <tr>
        <th>Fecha</th>
        <td>{{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <th>Cliente</th>
        <td>{{ $cotizacion->cliente->razonSocial }}</td>
    </tr>
    <tr>
        <th>CUIT Cliente</th>
        <td>{{ $cotizacion->cliente->cuit }}</td>
    </tr>
    <tr>
        <th>Validez</th>
        <td>{{ $cotizacion->validezDias }} días (hasta {{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->addDays($cotizacion->validezDias)->format('d/m/Y') }})</td>
    </tr>
    <tr>
        <th>Estado</th>
        <td>
            @if($cotizacion->estado == 'PENDIENTE')
                <span style="color: orange; font-weight: bold;">PENDIENTE</span>
            @elseif($cotizacion->estado == 'APROBADA')
                <span style="color: green; font-weight: bold;">APROBADA</span>
            @else
                <span style="color: red; font-weight: bold;">RECHAZADA</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Observaciones</th>
        <td>{{ $cotizacion->observaciones }}</td>
    </tr>
</table>

<br>

<h3>Detalle de Productos</h3>
@if($cotizacion->detalles->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
    </tr>
    @foreach($cotizacion->detalles as $detalle)
    <tr>
        <td>{{ $detalle->articuloMarca->articulo->nombreArticulo }}</td>
        <td>{{ $detalle->articuloMarca->marca->nombreMarca }}</td>
        <td>{{ $detalle->cantidad }}</td>
        <td>$ {{ number_format($detalle->precioUnitario, 2) }}</td>
        <td>$ {{ number_format($detalle->cantidad * $detalle->precioUnitario, 2) }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4" align="right"><strong>Subtotal:</strong></td>
        <td><strong>$ {{ number_format($cotizacion->subtotal, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="4" align="right"><strong>IVA (21%):</strong></td>
        <td><strong>$ {{ number_format($cotizacion->iva, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="4" align="right"><strong>TOTAL:</strong></td>
        <td><strong style="font-size: 1.2em;">$ {{ number_format($cotizacion->total, 2) }}</strong></td>
    </tr>
</table>
@else
<p>No hay productos en esta cotización.</p>
@endif

<br>

@if($cotizacion->estado == 'PENDIENTE')
<h3>Cambiar Estado</h3>
<form action="{{ route('cotizaciones.estado', [$cotizacion->idCotizacion, 'APROBADA']) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" style="background: green; color: white;">Aprobar Cotización</button>
</form>
<form action="{{ route('cotizaciones.estado', [$cotizacion->idCotizacion, 'RECHAZADA']) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" style="background: red; color: white;">Rechazar Cotización</button>
</form>
@endif
@endsection