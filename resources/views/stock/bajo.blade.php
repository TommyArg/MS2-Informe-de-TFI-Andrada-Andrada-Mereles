@extends('layout.base')

@section('title', 'Stock Bajo')

@section('content')
<h2>Stock Bajo (Alerta)</h2>

<a href="{{ route('stock.index') }}">← Volver a Stock General</a> |
<a href="{{ route('movimientos.entrada') }}">Nueva Entrada</a>

<br><br>

<h3>Productos con Stock Bajo ({{ $stockBajo->count() }})</h3>
@if($stockBajo->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Almacén</th>
        <th>Producto</th>
        <th>Marca</th>
        <th>Categoría</th>
        <th>Stock Actual</th>
        <th>Última Actualización</th>
    </tr>
    @foreach($stockBajo as $item)
    <tr>
        <td>{{ $item->almacen->nombreAlmacen }}</td>
        <td>{{ $item->articuloMarca->articulo->nombreArticulo }}</td>
        <td>{{ $item->articuloMarca->marca->nombreMarca }}</td>
        <td>{{ $item->articuloMarca->articulo->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
        <td style="color: red; font-weight: bold;">{{ $item->cantidadActual }}</td>
        <td>{{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y H:i') }}</td>
    </tr>
    @endforeach
</table>
@else
<p style="color: green; font-weight: bold;">✅ No hay productos con stock bajo.</p>
@endif
@endsection