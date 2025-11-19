@extends('layout.base')

@section('title', 'Productos')

@section('content')
<h2>Gestión de Productos</h2>

<a href="{{ route('productos.create') }}">Nuevo Producto</a> |
<a href="{{ route('marcas.index') }}">Marcas</a> |
<a href="{{ route('categorias.index') }}">Categorías</a> |
<a href="{{ route('precios.actualizar') }}">Actualizar Precios</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('productos.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar producto:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto">
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
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('productos.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Productos ({{ $productos->count() }})</h3>
@if($productos->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Marca</th>
        <th>Precio Actual</th>
        <th>Precio con IVA</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    @foreach($productos as $producto)
    @php
        $precioActual = null;
        $articuloMarca = $producto->articulosMarcas->first();
        if ($articuloMarca) {
            $precioActual = $articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
        }
    @endphp
    <tr>
        <td>{{ $producto->idArticulo }}</td>
        <td>{{ $producto->nombreArticulo }}</td>
        <td>{{ $producto->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
        <td>
            @foreach($producto->articulosMarcas as $articuloMarca)
                {{ $articuloMarca->marca->nombreMarca }}
            @endforeach
        </td>
        <td>
            @if($precioActual)
                $ {{ number_format($precioActual->precioVenta, 2) }}
                @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                    <br><small style="color: green;">Desc: $ {{ number_format($precioActual->precioDescuento, 2) }}</small>
                @endif
            @else
                <span style="color: red; font-size: 0.9em;">No definido</span>
            @endif
        </td>
        <td>
            @if($precioActual)
                @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
                    $ {{ number_format($precioActual->precioDescuento * 1.21, 2) }}
                @else
                    $ {{ number_format($precioActual->precioVenta * 1.21, 2) }}
                @endif
            @else
                -
            @endif
        </td>
        <td>{{ $producto->descripcionArticulo }}</td>
        <td>
            <a href="{{ route('productos.show', $producto->idArticulo) }}">Ver</a> |
            <a href="{{ route('productos.edit', $producto->idArticulo) }}">Editar</a> |
            <form action="{{ route('productos.destroy', $producto->idArticulo) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@else
<p>No se encontraron productos.</p>
@endif
@endsection