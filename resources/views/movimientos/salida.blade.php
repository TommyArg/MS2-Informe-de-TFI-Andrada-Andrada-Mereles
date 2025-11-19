@extends('layout.base')

@section('title', 'Nueva Salida')

@section('content')
<h2>Registrar Salida de Stock</h2>

<a href="{{ route('movimientos.index') }}">← Volver a Movimientos</a>

<br><br>

<form method="POST" action="{{ route('movimientos.salida.store') }}" id="salidaForm">
    @csrf
    
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <h3>Información del Movimiento</h3>
                <table>
                    <tr>
                        <td><label>Almacén:</label></td>
                        <td>
                            <select name="idAlmacen" id="idAlmacen" required onchange="actualizarStockDisponible()">
                                <option value="">Seleccionar almacén</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->idAlmacen }}">{{ $almacen->nombreAlmacen }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Observaciones:</label></td>
                        <td><textarea name="observaciones" rows="3" cols="40" placeholder="Motivo de la salida..."></textarea></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <h3>Productos a Retirar</h3>
                <div id="productos-container">
                    <div class="producto-item">
                        <table>
                            <tr>
                                <td>
                                    <select name="articulos[0][idArticuloMarca]" class="articulo-select" required onchange="actualizarStock(this)">
                                        <option value="">Seleccionar producto</option>
                                        @foreach($articulos as $articulo)
                                            <option value="{{ $articulo->idArticuloMarca }}" data-stock="{{ $articulo->stocks->sum('cantidadActual') }}">
                                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="articulos[0][cantidad]" min="1" value="1" required placeholder="Cantidad">
                                    <span class="stock-info" style="font-size: 0.8em; color: #666;"></span>
                                </td>
                                <td>
                                    <button type="button" onclick="eliminarProducto(this)" style="color: red;">✕</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <button type="button" onclick="agregarProducto()" style="margin-top: 10px;">+ Agregar otro producto</button>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <button type="submit">Registrar Salida</button>
                <a href="{{ route('movimientos.index') }}">Cancelar</a>
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
        <table>
            <tr>
                <td>
                    <select name="articulos[${productoCount}][idArticuloMarca]" class="articulo-select" required onchange="actualizarStock(this)">
                        <option value="">Seleccionar producto</option>
                        @foreach($articulos as $articulo)
                            <option value="{{ $articulo->idArticuloMarca }}" data-stock="{{ $articulo->stocks->sum('cantidadActual') }}">
                                {{ $articulo->articulo->nombreArticulo }} - {{ $articulo->marca->nombreMarca }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="articulos[${productoCount}][cantidad]" min="1" value="1" required placeholder="Cantidad">
                    <span class="stock-info" style="font-size: 0.8em; color: #666;"></span>
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
    } else {
        alert('Debe haber al menos un producto.');
    }
}

function actualizarStock(select) {
    const stock = select.selectedOptions[0].getAttribute('data-stock');
    const stockInfo = select.closest('tr').querySelector('.stock-info');
    stockInfo.textContent = `Stock total: ${stock}`;
}

function actualizarStockDisponible() {
    // Esta función se puede expandir para mostrar stock por almacén específico
    document.querySelectorAll('.articulo-select').forEach(select => {
        if (select.value) {
            actualizarStock(select);
        }
    });
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