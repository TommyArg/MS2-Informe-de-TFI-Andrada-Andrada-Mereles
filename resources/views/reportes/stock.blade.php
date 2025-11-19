@extends('layout.base')

@section('title', 'Reporte de Stock')

@section('content')
<h2>Reporte de Stock</h2>

<a href="{{ route('reportes.index') }}">← Volver a Reportes</a> |
<a href="{{ route('stock.index') }}">Ver Stock</a>

<br><br>

<h3>Resumen del Stock</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Total Productos en Stock</th>
        <th>Stock Total Unidades</th>
        <th>Productos con Stock Bajo</th>
    </tr>
    <tr>
        <td align="center">{{ $stock->count() }}</td>
        <td align="center">{{ $stockTotal }}</td>
        <td align="center" style="color: {{ $productosConStockBajo > 0 ? 'red' : 'green' }}; font-weight: bold;">
            {{ $productosConStockBajo }}
        </td>
    </tr>
</table>

<br>

@if($stockBajo->count() > 0)
<h3 style="color: red;">⚠️ Alertas de Stock Bajo</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Almacén</th>
        <th>Stock Actual</th>
        <th>Última Actualización</th>
    </tr>
    @foreach($stockBajo as $item)
    <tr>
        <td>{{ $item->nombreArticulo }}</td>
        <td>{{ $item->nombreMarca }}</td>
        <td>{{ $item->nombreAlmacen }}</td>
        <td style="color: red; font-weight: bold;">{{ $item->cantidadActual }}</td>
        <td>{{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y H:i') }}</td>
    </tr>
    @endforeach
</table>
<br>
@endif

<h3>Stock Completo por Producto</h3>
@if($stock->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Almacén</th>
        <th>Stock Actual</th>
        <th>Última Actualización</th>
    </tr>
    @foreach($stock as $item)
    <tr>
        <td>{{ $item->nombreArticulo }}</td>
        <td>{{ $item->nombreMarca }}</td>
        <td>{{ $item->nombreAlmacen }}</td>
        <td 
            @if($item->cantidadActual < 10) 
                style="color: red; font-weight: bold;" 
            @elseif($item->cantidadActual < 20)
                style="color: orange;"
            @endif
        >
            {{ $item->cantidadActual }}
        </td>
        <td>{{ \Carbon\Carbon::parse($item->ultimaActualizacion)->format('d/m/Y H:i') }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay stock registrado.</p>
@endif
@endsection