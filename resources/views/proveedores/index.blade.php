@extends('layout.base')

@section('title', 'Proveedores')

@section('content')
<h2>Gestión de Proveedores</h2>

<a href="{{ route('proveedores.create') }}">Nuevo Proveedor</a>

<br><br>

<h3>Búsqueda</h3>
<form method="GET" action="{{ route('proveedores.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Razón social, CUIT o email">
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('proveedores.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Proveedores ({{ $proveedores->total() }})</h3>
@if($proveedores->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Razón Social</th>
        <th>CUIT</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Acciones</th>
    </tr>
    @foreach($proveedores as $proveedor)
    <tr>
        <td>{{ $proveedor->idProveedor }}</td>
        <td>{{ $proveedor->razonSocialProveedor }}</td>
        <td>{{ $proveedor->cuitProveedor }}</td>
        <td>{{ $proveedor->telefonoProveedor }}</td>
        <td>{{ $proveedor->correoProveedor }}</td>
        <td>
            <a href="{{ route('proveedores.show', $proveedor->idProveedor) }}">Ver</a> |
            <a href="{{ route('proveedores.edit', $proveedor->idProveedor) }}">Editar</a> |
            <form action="{{ route('proveedores.destroy', $proveedor->idProveedor) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar este proveedor?')">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $proveedores->links() }}
</div>
@else
<p>No se encontraron proveedores.</p>
@endif
@endsection