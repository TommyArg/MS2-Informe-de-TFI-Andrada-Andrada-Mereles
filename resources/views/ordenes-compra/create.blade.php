@extends('layout.base')

@section('title', 'Nueva Orden de Compra')

@section('content')
<h2>Crear Nueva Orden de Compra</h2>

<a href="{{ route('ordenes-compra.index') }}">← Volver a Órdenes</a> |
<a href="{{ route('proveedores.create') }}">Nuevo Proveedor</a>

<br><br>

<form method="POST" action="{{ route('ordenes-compra.store') }}" id="ordenForm">
    @csrf
    
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <h3>Información de la Orden</h3>
                <table>
                    <tr>
                        <td><label>Proveedor:</label></td>
                        <td>
                            <select name="idProveedor" required style="width: 300px;">
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idProveedor }}">
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
                </table>
            </td>
            <td width="50%" valign="top">
                <h3>Productos a Solicitar</h3>
                <div id="productos-container">
                    <div class="producto-item">
                        <table width="100%" border="1" cellpadding="8" cellspacing="0">
                            <tr>
                                <th>Producto</th>
                                <th width="100">Cantidad</th>
                                <th width="120">Precio Unit.</th>
                                <th width="120">Subtotal</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="articulos[0][idArticuloMarca]" class="articulo-select" required style="width: 100%;" onchange="cargarPrecio(this, 0)">
                                        <option value="">Seleccionar producto</option>
                                        @foreach($articulos as $articulo)
                                            @php
                                                $precioActual = $articulo->preciosVenta->sortByDesc('fechaActualizacion')->first();
                                                $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
                                            @endphp
                                            <option value="{{ $articulo->idArticuloMarca }}" data-precio="{{ $precio }}">
                                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                                @if($precio > 0)
                                                    ($ {{ number_format($precio, 2) }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="articulos[0][cantidad]" min="1" value="1" required placeholder="Cant" style="width: 100%;" onchange="calcularSubtotal(0)">
                                </td>
                                <td>
                                    <input type="number" name="articulos[0][precioUnitario]" step="0.01" min="0" value="0" required placeholder="Precio" style="width: 100%;" onchange="calcularSubtotal(0)">
                                    <div id="precio-info-0" style="font-size: 0.8em; color: #666;"></div>
                                </td>
                                <td>
                                    <span id="subtotal-0">$ 0.00</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <button type="button" onclick="agregarProducto()" style="margin-top: 10px; padding: 5px 10px;">+ Agregar otro producto</button>
                
                <br><br>
                <h3>Totales de la Orden</h3>
                <table border="1" cellpadding="10" cellspacing="0" width="300">
                    <tr>
                        <th width="60%">Subtotal</th>
                        <td width="40%" align="right" id="subtotal-total">$ 0.00</td>
                    </tr>
                    <tr>
                        <th><strong>TOTAL</strong></th>
                        <td align="right" id="total-orden"><strong>$ 0.00</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <button type="submit" style="padding: 10px 20px; border: none; cursor: pointer; font-size: 16px;">
                    Crear Orden de Compra
                </button>
                <a href="{{ route('ordenes-compra.index') }}" style="padding: 10px 20px; text-decoration: none; display: inline-block;">
                    Cancelar
                </a>
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
        <table width="100%" border="1" cellpadding="8" cellspacing="0" style="margin-top: 10px;">
            <tr>
                <td>
                    <select name="articulos[${productoCount}][idArticuloMarca]" class="articulo-select" required style="width: 100%;" onchange="cargarPrecio(this, ${productoCount})">
                        <option value="">Seleccionar producto</option>
                        @foreach($articulos as $articulo)
                            @php
                                $precioActual = $articulo->preciosVenta->sortByDesc('fechaActualizacion')->first();
                                $precio = $precioActual ? ($precioActual->tieneDescuento && $precioActual->precioDescuento ? $precioActual->precioDescuento : $precioActual->precioVenta) : 0;
                            @endphp
                            <option value="{{ $articulo->idArticuloMarca }}" data-precio="{{ $precio }}">
                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                @if($precio > 0)
                                    ($ {{ number_format($precio, 2) }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="articulos[${productoCount}][cantidad]" min="1" value="1" required placeholder="Cant" style="width: 100%;" onchange="calcularSubtotal(${productoCount})">
                </td>
                <td>
                    <input type="number" name="articulos[${productoCount}][precioUnitario]" step="0.01" min="0" value="0" required placeholder="Precio" style="width: 100%;" onchange="calcularSubtotal(${productoCount})">
                    <div id="precio-info-${productoCount}" style="font-size: 0.8em; color: #666;"></div>
                </td>
                <td>
                    <span id="subtotal-${productoCount}">$ 0.00</span>
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

async function cargarPrecio(selectElement, index) {
    const idArticuloMarca = selectElement.value;
    const precioInput = document.querySelector(`input[name="articulos[${index}][precioUnitario]"]`);
    const precioInfo = document.getElementById(`precio-info-${index}`);
    
    if (idArticuloMarca) {
        // Mostrar loading
        precioInfo.innerHTML = '<span>Cargando precio...</span>';
        
        try {
            const response = await fetch(`/ordenes-compra/precio/${idArticuloMarca}`);
            const data = await response.json();
            
            if (data.success) {
                precioInput.value = data.precio;
                if (data.tieneDescuento && data.precioDescuento) {
                    precioInfo.innerHTML = `<span>Precio con descuento (Original: $${data.precioOriginal})</span>`;
                } else {
                    precioInfo.innerHTML = '<span>Precio actual cargado</span>';
                }
            } else {
                precioInput.value = 0;
                precioInfo.innerHTML = `<span>${data.message}</span>`;
            }
        } catch (error) {
            console.error('Error:', error);
            precioInput.value = 0;
            precioInfo.innerHTML = '<span>Error al cargar precio</span>';
        }
        
        // Calcular subtotal después de cargar el precio
        calcularSubtotal(index);
    } else {
        precioInput.value = 0;
        precioInfo.innerHTML = '';
        calcularSubtotal(index);
    }
}

function calcularSubtotal(index) {
    const cantidadInput = document.querySelector(`input[name="articulos[${index}][cantidad]"]`);
    const precioInput = document.querySelector(`input[name="articulos[${index}][precioUnitario]"]`);
    const subtotalSpan = document.getElementById(`subtotal-${index}`);
    
    const cantidad = parseFloat(cantidadInput.value) || 0;
    const precio = parseFloat(precioInput.value) || 0;
    const subtotal = cantidad * precio;
    
    subtotalSpan.textContent = '$ ' + subtotal.toFixed(2);
    calcularTotales();
}

function calcularTotales() {
    let subtotalTotal = 0;
    
    document.querySelectorAll('.producto-item').forEach((item, index) => {
        const cantidad = parseFloat(item.querySelector('input[name*="cantidad"]').value) || 0;
        const precio = parseFloat(item.querySelector('input[name*="precioUnitario"]').value) || 0;
        subtotalTotal += cantidad * precio;
    });
    
    document.getElementById('subtotal-total').textContent = '$ ' + subtotalTotal.toFixed(2);
    document.getElementById('total-orden').innerHTML = '<strong>$ ' + subtotalTotal.toFixed(2) + '</strong>';
}

// Calcular totales al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    calcularTotales();
    
    // Cargar precios iniciales si hay productos seleccionados
    document.querySelectorAll('.articulo-select').forEach((select, index) => {
        if (select.value) {
            setTimeout(() => cargarPrecio(select, index), 500);
        }
    });
});

// Validación antes de enviar el formulario
document.getElementById('ordenForm').addEventListener('submit', function(e) {
    let hasErrors = false;
    let errorMessage = '';
    
    // Verificar que al menos un producto tenga precio > 0
    const precios = document.querySelectorAll('input[name*="precioUnitario"]');
    let preciosValidos = false;
    
    precios.forEach(input => {
        if (parseFloat(input.value) > 0) {
            preciosValidos = true;
        }
    });
    
    if (!preciosValidos) {
        errorMessage += 'Al menos un producto debe tener un precio mayor a cero.\n';
        hasErrors = true;
    }
    
    // Verificar que las cantidades sean válidas
    const cantidades = document.querySelectorAll('input[name*="cantidad"]');
    cantidades.forEach(input => {
        if (parseInt(input.value) < 1) {
            errorMessage += 'Todas las cantidades deben ser al menos 1.\n';
            hasErrors = true;
        }
    });
    
    if (hasErrors) {
        e.preventDefault();
        alert(errorMessage);
    }
});
</script>

<style>
.producto-item {
    margin-bottom: 10px;
}

.articulo-select, input[type="number"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

button {
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    cursor: pointer;
}

button:hover {
    background-color: #f8f9fa;
}
</style>

@if($errors->any())
    <div style="color: red; padding: 15px; margin: 10px 0;">
        <strong>Errores encontrados:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection