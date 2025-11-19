@extends('layout.base')

@section('title', 'Ver Proveedor')

@section('content')
<h2>Detalles del Proveedor</h2>

<a href="{{ route('proveedores.index') }}">← Volver a Proveedores</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <td>{{ $proveedor->idProveedor }}</td>
    </tr>
    <tr>
        <th>Razón Social</th>
        <td>{{ $proveedor->razonSocialProveedor }}</td>
    </tr>
    <tr>
        <th>CUIT</th>
        <td>{{ $proveedor->cuitProveedor }}</td>
    </tr>
    <tr>
        <th>Dirección</th>
        <td>{{ $proveedor->direccionProveedor }}</td>
    </tr>
    <tr>
        <th>Teléfono</th>
        <td>{{ $proveedor->telefonoProveedor }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $proveedor->correoProveedor }}</td>
    </tr>
    <tr>
        <th>Web</th>
        <td>{{ $proveedor->webProveedor }}</td>
    </tr>
    <tr>
        <th>Observaciones</th>
        <td>{{ $proveedor->observaciones }}</td>
    </tr>
    <tr>
        <th>Fecha Alta</th>
        <td>{{ \Carbon\Carbon::parse($proveedor->fechaAltaProveedor)->format('d/m/Y') }}</td>
    </tr>
</table>

<br>

<h3>Órdenes de Compra</h3>
@if($proveedor->ordenesCompra->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Comprobante</th>
        <th>Fecha</th>
        <th>Estado</th>
    </tr>
    @foreach($proveedor->ordenesCompra as $orden)
    <tr>
        <td>{{ $orden->comprobanteOC }}</td>
        <td>{{ \Carbon\Carbon::parse($orden->fechaOC)->format('d/m/Y') }}</td>
        <td>{{ $orden->estado }}</td>
    </tr>
    @endforeach
</table>
@else
<p>Este proveedor no tiene órdenes de compra.</p>
@endif

<br>
<a href="{{ route('proveedores.edit', $proveedor->idProveedor) }}">Editar Proveedor</a>
@endsection