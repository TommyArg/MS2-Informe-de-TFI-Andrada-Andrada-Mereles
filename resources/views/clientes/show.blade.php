@extends('layout.base')

@section('title', 'Ver Cliente')

@section('content')
<h2>Detalles del Cliente</h2>

<a href="{{ route('clientes.index') }}">← Volver a Clientes</a> |
<a href="{{ route('cotizaciones.create') }}?cliente={{ $cliente->idCliente }}">Nueva Cotización</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <td>{{ $cliente->idCliente }}</td>
    </tr>
    <tr>
        <th>Razón Social</th>
        <td>{{ $cliente->razonSocial }}</td>
    </tr>
    <tr>
        <th>CUIT</th>
        <td>{{ $cliente->cuit }}</td>
    </tr>
    <tr>
        <th>Dirección</th>
        <td>{{ $cliente->direccion }}</td>
    </tr>
    <tr>
        <th>Teléfono</th>
        <td>{{ $cliente->telefono }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $cliente->correo }}</td>
    </tr>
    <tr>
        <th>Fecha Alta</th>
        <td>{{ \Carbon\Carbon::parse($cliente->fechaAlta)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <th>Estado</th>
        <td>
            @if($cliente->estado == 'ACTIVO')
                <span style="color: green;">ACTIVO</span>
            @else
                <span style="color: red;">INACTIVO</span>
            @endif
        </td>
    </tr>
</table>

<br>

<h3>Cotizaciones del Cliente</h3>
@if($cliente->cotizaciones->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Número</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    @foreach($cliente->cotizaciones as $cotizacion)
    <tr>
        <td>{{ $cotizacion->numeroCotizacion }}</td>
        <td>{{ \Carbon\Carbon::parse($cotizacion->fechaCotizacion)->format('d/m/Y') }}</td>
        <td>$ {{ number_format($cotizacion->total, 2) }}</td>
        <td>{{ $cotizacion->estado }}</td>
        <td>
            <a href="{{ route('cotizaciones.show', $cotizacion->idCotizacion) }}">Ver</a>
        </td>
    </tr>
    @endforeach
</table>
@else
<p>Este cliente no tiene cotizaciones.</p>
@endif

<br>
<a href="{{ route('clientes.edit', $cliente->idCliente) }}">Editar Cliente</a>
@endsection