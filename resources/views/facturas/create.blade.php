@extends('layout.base')

@section('title', 'Nueva Factura')

@section('content')
<h2>Crear Nueva Factura</h2>

<a href="{{ route('facturas.index') }}">← Volver a Facturas</a> |
<a href="{{ route('movimientos.salida') }}">Nueva Salida</a>

<br><br>

<form method="POST" action="{{ route('facturas.store') }}" id="facturaForm">
    @csrf
    
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <h3>Información de la Factura</h3>
                <table>
                    <tr>
                        <td><label>Movimiento de Salida:</label></td>
                        <td>
                            <select name="idMovimiento" id="idMovimiento" required onchange="cargarDetallesMovimiento()">
                                <option value="">Seleccionar movimiento</option>
                                @foreach($movimientos as $movimiento)
                                    <option value="{{ $movimiento->idMovimiento }}">
                                        #{{ $movimiento->idMovimiento }} - {{ $movimiento->almacen->nombreAlmacen }} - {{ \Carbon\Carbon::parse($movimiento->fechaMovimiento)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Cliente:</label></td>
                        <td>
                            <select name="idCliente" id="idCliente" required>
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}">
                                        {{ $cliente->razonSocial }} - {{ $cliente->cuit }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Tipo Factura:</label></td>
                        <td>
                            <select name="tipoFactura" required>
                                <option value="A">A</option>
                                <option value="B" selected>B</option>
                                <option value="C">C</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Descuento en Efectivo:</label></td>
                        <td>
                            <input type="number" name="descuentoEfectivo" id="descuentoEfectivo" step="0.01" min="0" value="0" onchange="calcularTotales()" placeholder="0.00">
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <h3>Detalles de la Factura</h3>
                <div id="detalles-container">
                    <p>Seleccione un movimiento de salida para cargar los productos.</p>
                </div>
                
                <br>
                <h3>Totales</h3>
                <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Subtotal</th>
                        <td id="subtotal">$ 0.00</td>
                    </tr>
                    <tr>
                        <th>Descuento</th>
                        <td id="descuento">$ 0.00</td>
                    </tr>
                    <tr>
                        <th>Subtotal con Desc.</th>
                        <td id="subtotalConDescuento">$ 0.00</td>
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
                <button type="submit">Crear Factura</button>
                <a href="{{ route('facturas.index') }}">Cancelar</a>
            </td>
        </tr>
    </table>
</form>

<script>
function cargarDetallesMovimiento() {
    const movimientoId = document.getElementById('idMovimiento').value;
    
    if (!movimientoId) {
        document.getElementById('detalles-container').innerHTML = '<p>Seleccione un movimiento de salida para cargar los productos.</p>';
        // Resetear totales
        document.getElementById('subtotal').textContent = '$ 0.00';
        calcularTotales();
        return;
    }
    
    fetch(`/facturas/movimiento/${movimientoId}/detalles`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            let html = '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
            html += '<tr><th>Producto</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr>';
            
            if (data.detalles && data.detalles.length > 0) {
                data.detalles.forEach(detalle => {
                    // Asegurar que los valores sean números
                    const precioUnitario = parseFloat(detalle.precioUnitario) || 0;
                    const subtotal = parseFloat(detalle.subtotal) || 0;
                    
                    html += `<tr>
                        <td>${detalle.producto || 'Producto no disponible'}</td>
                        <td>${detalle.cantidad || 0}</td>
                        <td>$ ${precioUnitario.toFixed(2)}</td>
                        <td>$ ${subtotal.toFixed(2)}</td>
                    </tr>`;
                });
            } else {
                html += '<tr><td colspan="4" style="text-align: center;">No hay productos en este movimiento</td></tr>';
            }
            
            html += '</table>';
            document.getElementById('detalles-container').innerHTML = html;
            
            // Actualizar totales
            const subtotal = parseFloat(data.subtotal) || 0;
            document.getElementById('subtotal').textContent = '$ ' + subtotal.toFixed(2);
            calcularTotales();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detalles-container').innerHTML = '<p style="color: red;">Error al cargar los detalles del movimiento: ' + error.message + '</p>';
            // Resetear totales en caso de error
            document.getElementById('subtotal').textContent = '$ 0.00';
            calcularTotales();
        });
}

function calcularTotales() {
    const subtotalText = document.getElementById('subtotal').textContent;
    const subtotal = parseFloat(subtotalText.replace('$', '').trim()) || 0;
    
    const descuento = parseFloat(document.getElementById('descuentoEfectivo').value) || 0;
    const subtotalConDescuento = Math.max(0, subtotal - descuento);
    const iva = subtotalConDescuento * 0.21;
    const total = subtotalConDescuento + iva;
    
    document.getElementById('descuento').textContent = '$ ' + descuento.toFixed(2);
    document.getElementById('subtotalConDescuento').textContent = '$ ' + subtotalConDescuento.toFixed(2);
    document.getElementById('iva').textContent = '$ ' + iva.toFixed(2);
    document.getElementById('total').textContent = '$ ' + total.toFixed(2);
}
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