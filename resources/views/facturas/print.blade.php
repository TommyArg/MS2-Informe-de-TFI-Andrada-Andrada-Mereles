<!DOCTYPE html>
<html>
<head>
    <title>Factura {{ $factura->numeroFactura }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .info { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .totals { width: 300px; margin-left: auto; }
        .footer { margin-top: 50px; text-align: center; font-size: 0.9em; }
        .signature { margin-top: 80px; }
        .no-print { 
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Cerrar</button>
    </div>

    <div class="header">
        <h1>FACTURA {{ $factura->numeroFactura }}</h1>
        <h2>Tuti Motos - Repuestos y Accesorios</h2>
        <p>Brandsen 175 - Tel: 3704-123456</p>
        <p>Email: contacto@tutimotos.com.ar - Web: www.tutimotos.com.ar</p>
    </div>

    <div class="info">
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($factura->fechaFactura)->format('d/m/Y') }}<br>
                    <strong>Tipo:</strong> {{ $factura->tipoFactura }}<br>
                    <strong>Estado:</strong> {{ $factura->estado }}
                </td>
                <td width="50%">
                    <strong>Cliente:</strong> {{ $factura->cliente->razonSocial }}<br>
                    <strong>CUIT:</strong> {{ $factura->cliente->cuit }}<br>
                    <strong>Dirección:</strong> {{ $factura->cliente->direccion }}<br>
                    <strong>Teléfono:</strong> {{ $factura->cliente->telefono }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Movimiento:</strong> #{{ $factura->movimiento->idMovimiento }} - {{ $factura->movimiento->almacen->nombreAlmacen }}
                </td>
            </tr>
        </table>
    </div>

    <h3>DETALLE DE PRODUCTOS</h3>
    <table class="table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="45%">Producto</th>
                <th width="15%">Marca</th>
                <th width="10%">Cantidad</th>
                <th width="15%">Precio Unitario</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->movimiento->detalles as $index => $detalle)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detalle->articuloMarca->articulo->nombreArticulo }}</td>
                <td>{{ $detalle->articuloMarca->marca->nombreMarca }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>$ {{ number_format($detalle->articuloMarca->preciosVenta->last()->precioVenta ?? 0, 2) }}</td>
                <td>$ {{ number_format($detalle->cantidad * ($detalle->articuloMarca->preciosVenta->last()->precioVenta ?? 0), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td><strong>Subtotal:</strong></td>
            <td>$ {{ number_format($factura->subtotal, 2) }}</td>
        </tr>
        @if($factura->descuentoEfectivo > 0)
        <tr>
            <td><strong>Descuento:</strong></td>
            <td>$ {{ number_format($factura->descuentoEfectivo, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Subtotal con Desc.:</strong></td>
            <td>$ {{ number_format($factura->subtotal - $factura->descuentoEfectivo, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td><strong>IVA (21%):</strong></td>
            <td>$ {{ number_format($factura->iva, 2) }}</td>
        </tr>
        <tr>
            <td><strong>TOTAL:</strong></td>
            <td><strong>$ {{ number_format($factura->total, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>Condiciones:</strong> {{ $factura->estado == 'PAGADA' ? 'FACTURA PAGADA' : 'PENDIENTE DE PAGO' }}</p>
        <div class="signature">
            <table width="100%">
                <tr>
                    <td align="center" width="50%">
                        <hr>
                        <strong>EMITIDO POR</strong><br>
                        Tuti Motos
                    </td>
                    <td align="center" width="50%">
                        <hr>
                        <strong>RECIBIDO POR</strong><br>
                        (Firma y Aclaración)
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        // Auto-close after print (optional)
        window.onafterprint = function() {
            // window.close(); // Descomentar si quieres que se cierre automáticamente después de imprimir
        };
    </script>
</body>
</html>