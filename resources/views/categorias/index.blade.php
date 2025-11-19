@extends('layout.base')

@section('title', 'Categorías')

@section('content')
<h2>Gestión de Categorías</h2>

<a href="{{ route('productos.index') }}">← Volver a Productos</a> |
<a href="{{ route('categorias.create') }}">Nueva Categoría</a>

<br><br>

<h3>Lista de Categorías ({{ $categorias->count() }})</h3>
@if($categorias->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    @foreach($categorias as $categoria)
    <tr>
        <td>{{ $categoria->idCatArticulo }}</td>
        <td>{{ $categoria->nombreCatArticulo }}</td>
        <td>{{ $categoria->descripcionCatArticulo }}</td>
        <td>
            <a href="{{ route('categorias.show', $categoria->idCatArticulo) }}">Ver</a> |
            <a href="{{ route('categorias.edit', $categoria->idCatArticulo) }}">Editar</a> |
            <form action="{{ route('categorias.destroy', $categoria->idCatArticulo) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@else
<p>No hay categorías registradas.</p>
@endif
@endsection