@extends('layout.base')

@section('title', 'Marcas')

@section('content')
<h2>Gestión de Marcas</h2>

<a href="{{ route('productos.index') }}">← Volver a Productos</a> |
<a href="{{ route('marcas.create') }}">Nueva Marca</a>

<br><br>

<h3>Lista de Marcas ({{ $marcas->count() }})</h3>
@if($marcas->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    @foreach($marcas as $marca)
    <tr>
        <td>{{ $marca->idMarca }}</td>
        <td>{{ $marca->nombreMarca }}</td>
        <td>{{ $marca->descripcionMarca }}</td>
        <td>
            <a href="{{ route('marcas.show', $marca->idMarca) }}">Ver</a> |
            <a href="{{ route('marcas.edit', $marca->idMarca) }}">Editar</a> |
            <form action="{{ route('marcas.destroy', $marca->idMarca) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar esta marca?')">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@else
<p>No hay marcas registradas.</p>
@endif
@endsection