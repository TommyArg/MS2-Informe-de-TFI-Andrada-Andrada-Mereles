<!DOCTYPE html>
<html>
<head>
    <title>Remito {{ $remito->numeroRemito }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .footer { margin-top: 50px; text-align: center; }
        .signature { margin-top: 80px; }
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
        <h1>REMITO {{ $remito->numeroRemito }}</h1>
        <h2>
            @if($remito->tipoRemito == 'ENTRADA')
                ENTRADA DE MERCADERÍA
            @else
                SALIDA DE MERCADERÍA
            @endif
        </h2>
    </div>

    <div class="info">
        <table width="100%">
            <tr>
                <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($remito->fechaRemito)->format('d/m/Y') }}</td>
                <td><strong>Almacén:</strong> {{ $remito->nombreAlmacen }}</td>
            </tr>
            <tr>
                <td><strong>Movimiento:</strong> #{{ $remito->idMovimiento }}</td>
                <td><strong>Usuario:</strong> {{ $remito->usuario }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Observaciones:</strong> {{ $remito->observaciones }}</td>
            </tr>
        </table>
    </div>

    <h3>DETALLE DE PRODUCTOS</h3>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Marca</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $index => $detalle)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detalle->nombreArticulo }}</td>
                <td>{{ $detalle->nombreMarca }}</td>
                <td>{{ $detalle->cantidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <table width="100%">
                <tr>
                    <td align="center">
                        <hr>
                        <strong>ENTREGADO POR</strong><br>
                        (Firma y Aclaración)
                    </td>
                    <td align="center">
                        <hr>
                        <strong>RECIBIDO POR</strong><br>
                        (Firma y Aclaración)
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>