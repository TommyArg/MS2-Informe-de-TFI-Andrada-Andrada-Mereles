@extends('layout.base')

@section('title', 'Editar Factura')

@section('content')
<h2>Editar Factura: {{ $factura->numeroFactura }}</h2>

<a href="{{ route('facturas.index') }}">← Volver a Facturas</a> |
<a href="{{ route('facturas.show', $factura->idFactura) }}">Ver Factura</a>

<br><br>

<form method="POST" action="{{ route('facturas.update', $factura->idFactura) }}" id="facturaForm">
    @csrf
    @method('PUT')
    
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <h3>Información de la Factura</h3>
                <table>
                    <tr>
                        <td><label>Número Factura:</label></td>
                        <td>{{ $factura->numeroFactura }}</td>
                    </tr>
                    <tr>
                        <td><label>Fecha:</label></td>
                        <td>{{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><label>Movimiento:</label></td>
                        <td>#{{ $factura->movimiento->idMovimiento }} - {{ $factura->movimiento->almacen->nombreAlmacen }}</td>
                    </tr>
                    <tr>
                        <td><label>Cliente:</label></td>
                        <td>
                            <select name="idCliente" required>
                                <option value="">Seleccionar cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}" {{ $factura->idCliente == $cliente->idCliente ? 'selected' : '' }}>
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
                                <option value="A" {{ $factura->tipoFactura == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $factura->tipoFactura == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ $factura->tipoFactura == 'C' ? 'selected' : '' }}>C</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Descuento en Efectivo:</label></td>
                        <td>
                            <input type="number" name="descuentoEfectivo" step="0.01" min="0" value="{{ $factura->descuentoEfectivo }}" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Estado:</label></td>
                        <td>{{ $factura->estado }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top">
                <h3>Detalles de la Factura</h3>
                <table border="1" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                    @foreach($factura->movimiento->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->articuloMarca->articulo->nombreArticulo }} - {{ $detalle->articuloMarca->marca->nombreMarca }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>$ {{ number_format($detalle->articuloMarca->preciosVenta->last()->precioVenta ?? 0, 2) }}</td>
                        <td>$ {{ number_format($detalle->cantidad * ($detalle->articuloMarca->preciosVenta->last()->precioVenta ?? 0), 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                
                <br>
                <h3>Totales Actuales</h3>
                <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Subtotal</th>
                        <td>$ {{ number_format($factura->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Descuento</th>
                        <td>$ {{ number_format($factura->descuentoEfectivo, 2) }}</td>
                    </tr>
                    <tr>
                        <th>IVA (21%)</th>
                        <td>$ {{ number_format($factura->iva, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td style="font-weight: bold;">$ {{ number_format($factura->total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <button type="submit">Actualizar Factura</button>
                <a href="{{ route('facturas.show', $factura->idFactura) }}">Cancelar</a>
            </td>
        </tr>
    </table>
</form>

@if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="color: red;">
        {{ session('error') }}
    </div>
@endif
@endsection