@extends('layout.base')

@section('title', 'Clientes')

@section('content')
<h2>Gestión de Clientes</h2>

<a href="{{ route('clientes.create') }}">Nuevo Cliente</a> |
<a href="{{ route('cotizaciones.create') }}">Nueva Cotización</a>

<br><br>

<h3>Búsqueda y Filtros</h3>
<form method="GET" action="{{ route('clientes.index') }}">
    <table>
        <tr>
            <td>
                <label>Buscar:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Razón social, CUIT o email">
            </td>
            <td>
                <label>Estado:</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="ACTIVO" {{ request('estado') == 'ACTIVO' ? 'selected' : '' }}>Activos</option>
                    <option value="INACTIVO" {{ request('estado') == 'INACTIVO' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </td>
            <td>
                <button type="submit">Buscar</button>
                <a href="{{ route('clientes.index') }}">Limpiar</a>
            </td>
        </tr>
    </table>
</form>

<br>

<h3>Lista de Clientes ({{ $clientes->total() }})</h3>
@if($clientes->count() > 0)
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Razón Social</th>
        <th>CUIT</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    @foreach($clientes as $cliente)
    <tr>
        <td>{{ $cliente->idCliente }}</td>
        <td>{{ $cliente->razonSocial }}</td>
        <td>{{ $cliente->cuit }}</td>
        <td>{{ $cliente->telefono }}</td>
        <td>{{ $cliente->correo }}</td>
        <td>
            @if($cliente->estado == 'ACTIVO')
                <span style="color: green;">ACTIVO</span>
            @else
                <span style="color: red;">INACTIVO</span>
            @endif
        </td>
        <td>
            <a href="{{ route('clientes.show', $cliente->idCliente) }}">Ver</a> |
            <a href="{{ route('clientes.edit', $cliente->idCliente) }}">Editar</a>
            @if($cliente->estado == 'ACTIVO')
                | <form action="{{ route('clientes.destroy', $cliente->idCliente) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Desactivar este cliente?')">Desactivar</button>
                </form>
            @else
                | <form action="{{ route('clientes.activate', $cliente->idCliente) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Activar</button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach
</table>

<br>
<div>
    {{ $clientes->links() }}
</div>
@else
<p>No se encontraron clientes.</p>
@endif
@endsection