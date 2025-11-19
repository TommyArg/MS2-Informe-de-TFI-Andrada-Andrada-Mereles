@extends('layout.base')

@section('title', 'Precios')

@section('content')
<h2>Gestión de Precios</h2>

<a href="{{ route('precios.actualizar') }}">Actualizar Precios</a> |
<a href="{{ route('productos.index') }}">Productos</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('precios.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar producto:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto">
            </td>
            <td>
                <label>
                    <input type="checkbox" name="con_descuento" value="1" {{ request('con_descuento') ? 'checked' : '' }}>
                    Solo productos con descuento
                </label>
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('precios.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Precios ({{ $articulos->total() }} productos)</h3>
@if($articulos->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Precio Venta</th>
        <th>Precio con IVA</th>
        <th>Descuento</th>
        <th>Precio con Desc.</th>
        <th>Última Actualización</th>
        <th>Acciones</th>
    </tr>
    @foreach($articulos as $articulo)
    @php
        $precioActual = $articulo->preciosVenta->last();
    @endphp
    <tr>
        <td>{{ $articulo->articulo->nombreArticulo }}</td>
        <td>{{ $articulo->marca->nombreMarca }}</td>
        <td>
            @if($precioActual)
                $ {{ number_format($precioActual->precioVenta, 2) }}
            @else
                <span style="color: red;">No definido</span>
            @endif
        </td>
        <td>
            @if($precioActual)
                $ {{ number_format($precioActual->precioVenta * 1.21, 2) }}
            @else
                -
            @endif
        </td>
        <td>
            @if($precioActual && $precioActual->tieneDescuento)
                <span style="color: green;">✓ Activo</span><br>
                $ {{ number_format($precioActual->precioDescuento, 2) }}
            @else
                <span style="color: #666;">No</span>
            @endif
        </td>
        <td>
            @if($precioActual && $precioActual->tieneDescuento)
                $ {{ number_format($precioActual->precioDescuento * 1.21, 2) }}
            @else
                -
            @endif
        </td>
        <td>
            @if($precioActual)
                {{ \Carbon\Carbon::parse($precioActual->fechaActualizacion)->format('d/m/Y H:i') }}
            @else
                -
            @endif
        </td>
        <td>
            @if($precioActual && !$precioActual->tieneDescuento)
                <form action="{{ route('precios.aplicar-descuento', $articulo->idArticuloMarca) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="number" name="precioDescuento" step="0.01" min="0" placeholder="Precio desc." required style="width: 80px;">
                    <button type="submit">Aplicar Desc.</button>
                </form>
            @elseif($precioActual && $precioActual->tieneDescuento)
                <form action="{{ route('precios.quitar-descuento', $articulo->idArticuloMarca) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Quitar Desc.</button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $articulos->links() }}
</div>
@else
<p>No se encontraron productos.</p>
@endif
@endsection