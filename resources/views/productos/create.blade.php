@extends('layout.base')

@section('title', 'Nuevo Producto')

@section('content')
<h2>Crear Nuevo Producto</h2>

<a href="{{ route('productos.index') }}">← Volver a Productos</a>

<br><br>

<form method="POST" action="{{ route('productos.store') }}">
    @csrf
    
    <table>
        <tr>
            <td><label>Nombre del Producto:</label></td>
            <td><input type="text" name="nombreArticulo" value="{{ old('nombreArticulo') }}" required></td>
        </tr>
        <tr>
            <td><label>Categoría:</label></td>
            <td>
                <select name="idCatArticulo" required>
                    <option value="">Seleccionar categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->idCatArticulo }}" {{ old('idCatArticulo') == $categoria->idCatArticulo ? 'selected' : '' }}>
                            {{ $categoria->nombreCatArticulo }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Marca:</label></td>
            <td>
                <select name="idMarca" required>
                    <option value="">Seleccionar marca</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->idMarca }}" {{ old('idMarca') == $marca->idMarca ? 'selected' : '' }}>
                            {{ $marca->nombreMarca }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Descripción:</label></td>
            <td><textarea name="descripcionArticulo" rows="3" cols="50">{{ old('descripcionArticulo') }}</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><button type="submit">Crear Producto</button></td>
        </tr>
    </table>
</form>
@endsection