@extends('layout.base')

@section('title', 'Editar Precio')

@section('content')
<h2>Editar Precio - {{ $articuloMarca->articulo->nombreArticulo }} ({{ $articuloMarca->marca->nombreMarca }})</h2>

<a href="{{ route('precios.index') }}">← Volver a Precios</a>

<br><br>

@if($precioActual)
<div style="background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-left: 4px solid #007bff;">
    <strong>Precio Actual:</strong> 
    $ {{ number_format($precioActual->precioVenta, 2) }}
    @if($precioActual->tieneDescuento && $precioActual->precioDescuento)
        <br><strong>Precio con Descuento:</strong> 
        $ {{ number_format($precioActual->precioDescuento, 2) }}
        <span style="color: green;">({{ number_format((1 - $precioActual->precioDescuento/$precioActual->precioVenta) * 100, 1) }}% descuento)</span>
    @endif
    <br><small>Última actualización: {{ $precioActual->fechaActualizacion->format('d/m/Y H:i') }}</small>
</div>
@endif

<form method="POST" action="{{ route('precios.actualizar-individual', $articuloMarca->idArticuloMarca) }}">
    @csrf
    
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <td width="30%"><strong>Producto:</strong></td>
            <td>{{ $articuloMarca->articulo->nombreArticulo }}</td>
        </tr>
        <tr>
            <td><strong>Marca:</strong></td>
            <td>{{ $articuloMarca->marca->nombreMarca }}</td>
        </tr>
        <tr>
            <td><strong>Categoría:</strong></td>
            <td>{{ $articuloMarca->articulo->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
        </tr>
        <tr>
            <td><strong>Precio de Venta *</strong></td>
            <td>
                <input type="number" name="precioVenta" 
                       value="{{ old('precioVenta', $precioActual->precioVenta ?? '') }}" 
                       step="0.01" min="0" required style="width: 200px;">
                @error('precioVenta')
                    <br><span style="color: red;">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td><strong>Aplicar Descuento</strong></td>
            <td>
                <label>
                    <input type="checkbox" name="tieneDescuento" value="1" 
                           {{ old('tieneDescuento', $precioActual->tieneDescuento ?? false) ? 'checked' : '' }}
                           id="tieneDescuentoCheckbox">
                    Activar descuento
                </label>
            </td>
        </tr>
        <tr id="precioDescuentoRow" style="display: none;">
            <td><strong>Precio con Descuento *</strong></td>
            <td>
                <input type="number" name="precioDescuento" 
                       value="{{ old('precioDescuento', $precioActual->precioDescuento ?? '') }}" 
                       step="0.01" min="0" style="width: 200px;">
                @error('precioDescuento')
                    <br><span style="color: red;">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">
                    Actualizar Precio
                </button>
                <a href="{{ route('precios.index') }}" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none;">
                    Cancelar
                </a>
            </td>
        </tr>
    </table>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descuentoCheckbox = document.getElementById('tieneDescuentoCheckbox');
    const descuentoRow = document.getElementById('precioDescuentoRow');
    
    function toggleDescuentoField() {
        if (descuentoCheckbox.checked) {
            descuentoRow.style.display = '';
        } else {
            descuentoRow.style.display = 'none';
        }
    }
    
    // Estado inicial
    toggleDescuentoField();
    
    // Cambiar cuando se modifique el checkbox
    descuentoCheckbox.addEventListener('change', toggleDescuentoField);
});
</script>

@endsection