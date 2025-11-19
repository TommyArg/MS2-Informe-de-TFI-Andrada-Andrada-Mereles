@extends('layout.base')

@section('title', 'Actualizar Precios')

@section('content')
<h2>Actualización Masiva de Precios</h2>

<a href="{{ route('precios.index') }}">← Volver a Precios</a>

<br><br>

<form method="POST" action="{{ route('precios.store') }}">
    @csrf
    
    <h3>Lista de Productos para Actualizar</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <tr>
            <th>Producto</th>
            <th>Marca</th>
            <th>Precio Actual</th>
            <th>Nuevo Precio Venta</th>
            <th>Descuento</th>
            <th>Precio con Desc.</th>
        </tr>
        @foreach($articulos as $index => $articulo)
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
                <input type="hidden" name="precios[{{ $index }}][idArticuloMarca]" value="{{ $articulo->idArticuloMarca }}">
                <input type="number" name="precios[{{ $index }}][precioVenta]" 
                       step="0.01" min="0" 
                       value="{{ $precioActual ? $precioActual->precioVenta : 0 }}" 
                       required style="width: 100px;">
            </td>
            <td>
                <label>
                    <input type="checkbox" name="precios[{{ $index }}][tieneDescuento]" value="1" 
                           {{ $precioActual && $precioActual->tieneDescuento ? 'checked' : '' }}>
                    Aplicar descuento
                </label>
            </td>
            <td>
                <input type="number" name="precios[{{ $index }}][precioDescuento]" 
                       step="0.01" min="0" 
                       value="{{ $precioActual && $precioActual->tieneDescuento ? $precioActual->precioDescuento : 0 }}" 
                       style="width: 100px;" 
                       placeholder="Precio descuento">
            </td>
        </tr>
        @endforeach
    </table>

    <br>
    <button type="submit">Actualizar Todos los Precios</button>
    <a href="{{ route('precios.index') }}">Cancelar</a>
</form>

<br>
<p><strong>Nota:</strong> Esta acción creará nuevos registros de precios manteniendo el historial.</p>

@if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection