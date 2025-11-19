@extends('layout.base')

@section('title', 'Nueva Cotización')

@section('content')
<h2>Crear Nueva Cotización</h2>

<a href="{{ route('cotizaciones.index') }}">← Volver a Cotizaciones</a> |
<a href="{{ route('clientes.create') }}">Nuevo Cliente</a>

<br><br>

<form method="POST" action="{{ route('cotizaciones.store') }}" id="cotizacionForm">
    @csrf
    
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <h3>Información de la Cotización</h3>
                <table>
                    <tr>
                        <td><label>Cliente:</label></td>
                        <td>
                            <select name="idCliente" id="idCliente" required>
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}" {{ request('cliente') == $cliente->idCliente ? 'selected' : '' }}>
                                        {{ $cliente->razonSocial }} - {{ $cliente->cuit }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Validez (días):</label></td>
                        <td><input type="number" name="validezDias" value="30" min="1" max="365" required></td>
                    </tr>
                    <tr>
                        <td><label>Observaciones:</label></td>
                        <td><textarea name="observaciones" rows="3" cols="40" placeholder="Observaciones adicionales..."></textarea></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <h3>Productos</h3>
                <div id="productos-container">
                    <div class="producto-item">
                        <table width="100%">
                            <tr>
                                <td>
                                    <select name="articulos[0][idArticuloMarca]" class="articulo-select" required onchange="actualizarPrecio(this, 0)">
                                        <option value="">Seleccionar producto</option>
                                        @foreach($articulos as $articulo)
                                            <option value="{{ $articulo->idArticuloMarca }}" 
                                                data-precio="{{ $articulo->preciosVenta->last()->precioVenta ?? 0 }}"
                                                data-precio-iva="{{ ($articulo->preciosVenta->last()->precioVenta ?? 0) * 1.21 }}">
                                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="articulos[0][cantidad]" min="1" value="1" required placeholder="Cantidad" onchange="calcularTotales()">
                                </td>
                                <td>
                                    <input type="number" name="articulos[0][precioUnitario]" step="0.01" min="0" value="0" required placeholder="Precio" onchange="calcularTotales()">
                                </td>
                                <td>
                                    <button type="button" onclick="eliminarProducto(this)" style="color: red;">✕</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <button type="button" onclick="agregarProducto()" style="margin-top: 10px;">+ Agregar otro producto</button>
                
                <br><br>
                <h3>Totales</h3>
                <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Subtotal</th>
                        <td id="subtotal">$ 0.00</td>
                    </tr>
                    <tr>
                        <th>IVA (21%)</th>
                        <td id="iva">$ 0.00</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td id="total" style="font-weight: bold;">$ 0.00</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <button type="submit">Crear Cotización</button>
                <a href="{{ route('cotizaciones.index') }}">Cancelar</a>
            </td>
        </tr>
    </table>
</form>

<script>
let productoCount = 1;

function agregarProducto() {
    const container = document.getElementById('productos-container');
    const nuevoProducto = document.createElement('div');
    nuevoProducto.className = 'producto-item';
    nuevoProducto.innerHTML = `
        <table width="100%">
            <tr>
                <td>
                    <select name="articulos[${productoCount}][idArticuloMarca]" class="articulo-select" required onchange="actualizarPrecio(this, ${productoCount})">
                        <option value="">Seleccionar producto</option>
                        @foreach($articulos as $articulo)
                            <option value="{{ $articulo->idArticuloMarca }}" 
                                data-precio="{{ $articulo->preciosVenta->last()->precioVenta ?? 0 }}"
                                data-precio-iva="{{ ($articulo->preciosVenta->last()->precioVenta ?? 0) * 1.21 }}">
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

function actualizarPrecio(select, index) {
    const precio = select.selectedOptions[0].getAttribute('data-precio');
    const precioConIva = select.selectedOptions[0].getAttribute('data-precio-iva');
    
    const precioInput = select.closest('tr').querySelector('input[name*="precioUnitario"]');
    precioInput.value = precio;
    
    calcularTotales();
}

function calcularTotales() {
    let subtotal = 0;
    
    document.querySelectorAll('.producto-item').forEach((item, index) => {
        const cantidad = item.querySelector('input[name*="cantidad"]').value;
        const precio = item.querySelector('input[name*="precioUnitario"]').value;
        
        subtotal += cantidad * precio;
    });
    
    const iva = subtotal * 0.21;
    const total = subtotal + iva;
    
    document.getElementById('subtotal').textContent = '$ ' + subtotal.toFixed(2);
    document.getElementById('iva').textContent = '$ ' + iva.toFixed(2);
    document.getElementById('total').textContent = '$ ' + total.toFixed(2);
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