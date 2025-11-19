@extends('layout.base')

@section('title', 'Ver Categoría')

@section('content')
<h2>Detalles de la Categoría</h2>

<a href="{{ route('categorias.index') }}">← Volver a Categorías</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <td>{{ $categoria->idCatArticulo }}</td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td>{{ $categoria->nombreCatArticulo }}</td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td>{{ $categoria->descripcionCatArticulo }}</td>
    </tr>
</table>

<br>

<h3>Productos en esta Categoría</h3>
@if($categoria->articulos->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Marca</th>
        <th>Descripción</th>
    </tr>
    @foreach($categoria->articulos as $articulo)
    <tr>
        <td>{{ $articulo->nombreArticulo }}</td>
        <td>
            @foreach($articulo->articulosMarcas as $articuloMarca)
                {{ $articuloMarca->marca->nombreMarca }}
            @endforeach
        </td>
        <td>{{ $articulo->descripcionArticulo }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay productos en esta categoría.</p>
@endif

<br>
<a href="{{ route('categorias.edit', $categoria->idCatArticulo) }}">Editar Categoría</a>
@endsection