@extends('layout.base')

@section('title', 'Stock por Almacén')

@section('content')
<h2>Stock - {{ $almacen->nombreAlmacen }}</h2>

<a href="{{ route('stock.index') }}">← Volver a Stock General</a>

<br><br>

<h3>Productos en este almacén ({{ $stock->count() }})</h3>
@if($stock->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Categoría</th>
        <th>Stock Actual</th>
        <th>Última Actualización</th>
    </tr>
    @foreach($stock as $item)
    <tr>
        <td>{{ $item->articuloMarca->articulo->nombreArticulo }}</td>
        <td>{{ $item->articuloMarca->marca->nombreMarca }}</td>
        <td>{{ $item->articuloMarca->articulo->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
        <td 
            @if($item->cantidadActual < 10) 
                style="color: red; font-weight: bold;" 
            @elseif($item->cantidadActual < 20)
                style="color: orange;"
            @endif
        >
            {{ $item->cantidadActual }}
        </td>
        <td>{{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y H:i') }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay productos en este almacén.</p>
@endif
@endsection