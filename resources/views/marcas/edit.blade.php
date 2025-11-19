@extends('layout.base')

@section('title', 'Editar Marca')

@section('content')
<h2>Editar Marca</h2>

<a href="{{ route('marcas.index') }}">← Volver a Marcas</a>

<br><br>

<form method="POST" action="{{ route('marcas.update', $marca->idMarca) }}">
    @csrf
    @method('PUT')
    
    <table>
        <tr>
            <td><label>Nombre de la Marca:</label></td>
            <td><input type="text" name="nombreMarca" value="{{ old('nombreMarca', $marca->nombreMarca) }}" required></td>
        </tr>
        <tr>
            <td><label>Descripción:</label></td>
            <td><textarea name="descripcionMarca" rows="3" cols="50">{{ old('descripcionMarca', $marca->descripcionMarca) }}</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Actualizar Marca</button>
                <a href="{{ route('marcas.index') }}">Cancelar</a>
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