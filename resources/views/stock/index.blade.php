@extends('layout.base')

@section('title', 'Stock')

@section('content')
<h2>Gestión de Stock</h2>

<a href="{{ route('stock.bajo') }}">Ver Stock Bajo</a> |
<a href="{{ route('movimientos.entrada') }}">Nueva Entrada</a> |
<a href="{{ route('movimientos.salida') }}">Nueva Salida</a> |
<a href="{{ route('precios.actualizar') }}">Actualizar Precios</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('stock.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar producto:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto">
            </td>
            <td>
                <label>Almacén:</label>
                <select name="almacen">
                    <option value="">Todos los almacenes</option>
                    @foreach($almacenes as $almacen)
                        <option value="{{ $almacen->idAlmacen }}" {{ request('almacen') == $almacen->idAlmacen ? 'selected' : '' }}>
                            {{ $almacen->nombreAlmacen }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <label>Categoría:</label>
                <select name="categoria">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->idCatArticulo }}" {{ request('categoria') == $categoria->idCatArticulo ? 'selected' : '' }}>
                            {{ $categoria->nombreCatArticulo }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="checkbox" name="stock_bajo" value="1" {{ request('stock_bajo') ? 'checked' : '' }}>
                    Solo stock bajo (< 10)
                </label>
            </td>
            <td colspan="2">
                <button type="submit">Buscar</button>
                <a href="{{ route('stock.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Stock Total ({{ $stock->count() }} productos)</h3>
@if($stock->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Almacén</th>
        <th>Producto</th>
        <th>Marca</th>
        <th>Categoría</th>
        <th>Stock Actual</th>
        <th>Precio Actual</th>
        <th>Valor Total</th>
    </tr>
    @foreach($stock as $item)
    @php
        $precioActual = $item->articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
        $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
        $valorTotal = $item->cantidadActual * $precio;
    @endphp
    <tr>
        <td>{{ $item->almacen->nombreAlmacen }}</td>
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
        <td>
            @if($precioActual)
                $ {{ number_format($precio, 2) }}
                @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                    <br><small style="color: green;">(Con descuento)</small>
                @endif
            @else
                <span style="color: red; font-size: 0.9em;">No definido</span>
            @endif
        </td>
        <td><strong>$ {{ number_format($valorTotal, 2) }}</strong></td>
    </tr>
    @endforeach
</table>

<br>
<h4>Valor Total del Inventario: $ {{ number_format($stock->sum(function($item) {
    $precioActual = $item->articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
    $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
    return $item->cantidadActual * $precio;
}), 2) }}</h4>
@else
<p>No se encontró stock con los filtros aplicados.</p>
@endif

<br>

<h3>Stock por Almacén</h3>
@foreach($almacenes as $almacen)
    <a href="{{ route('stock.almacen', $almacen->idAlmacen) }}">{{ $almacen->nombreAlmacen }}</a> |
@endforeach
@endsection