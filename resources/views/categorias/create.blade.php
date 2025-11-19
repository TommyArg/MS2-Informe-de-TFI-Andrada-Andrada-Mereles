@extends('layout.base')

@section('title', 'Nueva Categoría')

@section('content')
<h2>Crear Nueva Categoría</h2>

<a href="{{ route('categorias.index') }}">← Volver a Categorías</a>

<br><br>

<form method="POST" action="{{ route('categorias.store') }}">
    @csrf
    
    <table>
        <tr>
            <td><label>Nombre de la Categoría:</label></td>
            <td><input type="text" name="nombreCatArticulo" value="{{ old('nombreCatArticulo') }}" required></td>
        </tr>
        <tr>
            <td><label>Descripción:</label></td>
            <td><textarea name="descripcionCatArticulo" rows="3" cols="50">{{ old('descripcionCatArticulo') }}</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Crear Categoría</button>
                <a href="{{ route('categorias.index') }}">Cancelar</a>
            </td>
        </tr>
    </table>
</form>

@if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection