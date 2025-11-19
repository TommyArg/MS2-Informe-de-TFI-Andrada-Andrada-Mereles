@extends('layout.base')

@section('title', 'Ver Producto')

@section('content')
<h2>Detalles del Producto</h2>

<a href="{{ route('productos.index') }}">← Volver a Productos</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <td>{{ $producto->idArticulo }}</td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td>{{ $producto->nombreArticulo }}</td>
    </tr>
    <tr>
        <th>Categoría</th>
        <td>{{ $producto->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
    </tr>
    <tr>
        <th>Marca</th>
        <td>
            @foreach($producto->articulosMarcas as $articuloMarca)
                {{ $articuloMarca->marca->nombreMarca }}
            @endforeach
        </td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td>{{ $producto->descripcionArticulo }}</td>
    </tr>
</table>

<br>

<h3>Precios Actuales</h3>
@foreach($producto->articulosMarcas as $articuloMarca)
@php
    $precioActual = $articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
@endphp
<table border="1" cellpadding="5" cellspacing="0" style="margin-bottom: 15px;">
    <tr>
        <th colspan="2" style="background: #f0f0f0;">
            {{ $articuloMarca->marca->nombreMarca }}
            <a href="{{ route('precios.editar', $articuloMarca->idArticuloMarca) }}" style="float: right; color: blue;">Editar Precio</a>
        </th>
    </tr>
    @if($precioActual)
    <tr>
        <td><strong>Precio Venta:</strong></td>
        <td>$ {{ number_format($precioActual->precioVenta, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Precio con IVA:</strong></td>
        <td>$ {{ number_format($precioActual->precioVenta * 1.21, 2) }}</td>
    </tr>
    @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
    <tr>
        <td><strong>Precio con Descuento:</strong></td>
        <td style="color: green;">$ {{ number_format($precioActual->precioDescuento, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Precio Desc. con IVA:</strong></td>
        <td style="color: green;">$ {{ number_format($precioActual->precioDescuento * 1.21, 2) }}</td>
    </tr>
    @endif
    <tr>
        <td><strong>Última Actualización:</strong></td>
        <td>{{ \Carbon\Carbon::parse($precioActual->fechaActualizacion)->format('d/m/Y H:i') }}</td>
    </tr>
    @else
    <tr>
        <td colspan="2" style="color: red; text-align: center;">
            No hay precio definido
            <br>
            <a href="{{ route('precios.editar', $articuloMarca->idArticuloMarca) }}" style="color: blue;">Definir Precio</a>
        </td>
    </tr>
    @endif
</table>
@endforeach

<br>

<h3>Stock por Almacén</h3>
@if($producto->stocks->count() > 0)
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Almacén</th>
        <th>Stock Actual</th>
        <th>Última Actualización</th>
    </tr>
    @foreach($producto->stocks as $stock)
    <tr>
        <td>{{ $stock->almacen->nombreAlmacen }}</td>
        <td>{{ $stock->cantidadActual }}</td>
        <td>{{ \Carbon\Carbon::parse($stock->ultimaActualizacion)->format('d/m/Y H:i') }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay stock registrado para este producto.</p>
@endif

<br>
<a href="{{ route('productos.edit', $producto->idArticulo) }}">Editar Producto</a>
@endsection