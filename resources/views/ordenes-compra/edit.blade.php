@extends('layout.base')

@section('title', 'Editar Orden de Compra')

@section('content')
<h2>Editar Orden de Compra: {{ $orden->comprobanteOC }}</h2>

<a href="{{ route('ordenes-compra.index') }}">← Volver a Órdenes</a>

<br><br>

<form method="POST" action="{{ route('ordenes-compra.update', $orden->idOrdenCompra) }}" id="ordenForm">
    @csrf
    @method('PUT')
    
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <h3>Información de la Orden</h3>
                <table>
                    <tr>
                        <td><label>Proveedor:</label></td>
                        <td>
                            <select name="idProveedor" required>
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idProveedor }}" {{ $orden->idProveedor == $proveedor->idProveedor ? 'selected' : '' }}>
                                        {{ $proveedor->razonSocialProveedor }} - {{ $proveedor->cuitProveedor }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @if($empresa)
                    <tr>
                        <td><label>Empresa:</label></td>
                        <td>{{ $empresa->razonSocial }} - CUIT: {{ $empresa->cuit }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><label>Estado:</label></td>
                        <td>{{ $orden->estado }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <h3>Productos</h3>
                <div id="productos-container">
                    @foreach($orden->detalles as $index => $detalle)
                    <div class="producto-item">
                        <table width="100%">
                            <tr>
                                <td>
                                    <select name="articulos[{{ $index }}][idArticuloMarca]" class="articulo-select" required>
                                        <option value="">Seleccionar producto</option>
                                        @foreach($articulos as $articulo)
                                            <option value="{{ $articulo->idArticuloMarca }}" {{ $detalle->idArticuloMarca == $articulo->idArticuloMarca ? 'selected' : '' }}>
                                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="articulos[{{ $index }}][cantidad]" min="1" value="{{ $detalle->cantidad }}" required placeholder="Cantidad" onchange="calcularTotales()">
                                </td>
                                <td>
                                    <input type="number" name="articulos[{{ $index }}][precioUnitario]" step="0.01" min="0" value="{{ $detalle->precioUnitario }}" required placeholder="Precio" onchange="calcularTotales()">
                                </td>
                                <td>
                                    <button type="button" onclick="eliminarProducto(this)" style="color: red;">✕</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarProducto()" style="margin-top: 10px;">+ Agregar otro producto</button>
                
                <br><br>
                <h3>Totales</h3>
                <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Subtotal</th>
                        <td id="subtotal">$ {{ number_format($orden->detalles->sum(function($detalle) { return $detalle->cantidad * $detalle->precioUnitario; }), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <button type="submit">Actualizar Orden de Compra</button>
                <a href="{{ route('ordenes-compra.show', $orden->idOrdenCompra) }}">Cancelar</a>
            </td>
        </tr>
    </table>
</form>

<script>
let productoCount = {{ $orden->detalles->count() }};

function agregarProducto() {
    const container = document.getElementById('productos-container');
    const nuevoProducto = document.createElement('div');
    nuevoProducto.className = 'producto-item';
    nuevoProducto.innerHTML = `
        <table width="100%">
            <tr>
                <td>
                    <select name="articulos[${productoCount}][idArticuloMarca]" class="articulo-select" required>
                        <option value="">Seleccionar producto</option>
                        @foreach($articulos as $articulo)
                            <option value="{{ $articulo->idArticuloMarca }}">
                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="articulos[${productoCount}][cantidad]" min="1" value="1" required placeholder="Cantidad" onchange="calcularTotales()">
                </td>
                <td>
                    <input type="number" name="articulos[${productoCount}][precioUnitario]" step="0.01" min="0" value="0" required placeholder="Precio" onchange="calcularTotales()">
                </td>
                <td>
                    <button type="button" onclick="eliminarProducto(this)" style="color: red;">✕</button>
                </td>
            </tr>
        </table>
    `;
    container.appendChild(nuevoProducto);
    productoCount++;
}

function eliminarProducto(button) {
    if (document.querySelectorAll('.producto-item').length > 1) {
        button.closest('.producto-item').remove();
        calcularTotales();
    } else {
        alert('Debe haber al menos un producto.');
    }
}

function calcularTotales() {
    let subtotal = 0;
    
    document.querySelectorAll('.producto-item').forEach((item, index) => {
        const cantidad = item.querySelector('input[name*="cantidad"]').value;
        const precio = item.querySelector('input[name*="precioUnitario"]').value;
        
        subtotal += cantidad * precio;
    });
    
    document.getElementById('subtotal').textContent = '$ ' + subtotal.toFixed(2);
}

// Calcular totales al cargar la página
document.addEventListener('DOMContentLoaded', calcularTotales);
</script>

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