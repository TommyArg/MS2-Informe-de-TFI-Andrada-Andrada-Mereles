@extends('layout.base')

@section('title', 'Ver Marca')

@section('content')
<h2>Detalles de la Marca</h2>

<a href="{{ route('marcas.index') }}">← Volver a Marcas</a>

<br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <td>{{ $marca->idMarca }}</td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td>{{ $marca->nombreMarca }}</td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td>{{ $marca->descripcionMarca }}</td>
    </tr>
</table>

<br>

<h3>Productos de esta Marca</h3>
@if($marca->articulosMarcas->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>Producto</th>
        <th>Categoría</th>
        <th>Descripción</th>
    </tr>
    @foreach($marca->articulosMarcas as $articuloMarca)
    <tr>
        <td>{{ $articuloMarca->articulo->nombreArticulo }}</td>
        <td>{{ $articuloMarca->articulo->categoria->nombreCatArticulo ?? 'Sin categoría' }}</td>
        <td>{{ $articuloMarca->articulo->descripcionArticulo }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No hay productos asociados a esta marca.</p>
@endif

<br>
<a href="{{ route('marcas.edit', $marca->idMarca) }}">Editar Marca</a>
@endsection