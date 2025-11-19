<!DOCTYPE html>
<html>
<head>
    <title>Orden de Compra {{ $orden->comprobanteOC }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .info { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .totals { width: 300px; margin-left: auto; }
        .footer { margin-top: 50px; text-align: center; font-size: 0.9em; }
        .no-print { display: none; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Cerrar</button>
    </div>

    <div class="header">
        <h1>ORDEN DE COMPRA {{ $orden->comprobanteOC }}</h1>
        <h2>{{ $orden->empresa->razonSocial ?? 'Tuti Motos' }}</h2>
        <p>{{ $orden->empresa->direccionEmpresa ?? 'Brandsen 175' }} - Tel: {{ $orden->empresa->telefonoEmpresa ?? '3704-123456' }}</p>
        <p>CUIT: {{ $orden->empresa->cuit ?? '30-71123456-7' }}</p>
    </div>

    <div class="info">
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($orden->fechaOC)->format('d/m/Y') }}<br>
                    <strong>Estado:</strong> {{ $orden->estado }}
                </td>
                <td width="50%">
                    <strong>Proveedor:</strong> {{ $orden->proveedor->razonSocialProveedor }}<br>
                    <strong>CUIT:</strong> {{ $orden->proveedor->cuitProveedor }}<br>
                    <strong>Dirección:</strong> {{ $orden->proveedor->direccionProveedor }}
                </td>
            </tr>
        </table>
    </div>

    <h3>DETALLE DE PRODUCTOS SOLICITADOS</h3>
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
            @php
                $total = 0;
            @endphp
            @foreach($orden->detalles as $index => $detalle)
            @php
                $subtotal = $detalle->cantidad * $detalle->precioUnitario;
                $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detalle->articuloMarca->articulo->nombreArticulo }}</td>
                <td>{{ $detalle->articuloMarca->marca->nombreMarca }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>$ {{ number_format($detalle->precioUnitario, 2) }}</td>
                <td>$ {{ number_format($subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td><strong>TOTAL:</strong></td>
            <td><strong>$ {{ number_format($total, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>Condiciones:</strong> Favor entregar según especificaciones. Contactar para coordinación de entrega.</p>
        <div style="margin-top: 50px;">
            <table width="100%">
                <tr>
                    <td align="center" width="50%">
                        <hr>
                        <strong>AUTORIZADO POR</strong><br>
                        {{ $orden->empresa->razonSocial ?? 'Tuti Motos' }}
                    </td>
                    <td align="center" width="50%">
                        <hr>
                        <strong>CONFORMADO POR</strong><br>
                        (Firma y Sello del Proveedor)
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>