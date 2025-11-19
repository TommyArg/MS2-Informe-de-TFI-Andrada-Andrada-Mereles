@extends('layout.base')

@section('title', 'Nuevo Proveedor')

@section('content')
<h2>Crear Nuevo Proveedor</h2>

<a href="{{ route('proveedores.index') }}">← Volver a Proveedores</a>

<br><br>

<form method="POST" action="{{ route('proveedores.store') }}">
    @csrf
    
    <table>
        <tr>
            <td><label>Razón Social:</label></td>
            <td><input type="text" name="razonSocialProveedor" value="{{ old('razonSocialProveedor') }}" required></td>
        </tr>
        <tr>
            <td><label>CUIT:</label></td>
            <td><input type="text" name="cuitProveedor" value="{{ old('cuitProveedor') }}" maxlength="11" required></td>
        </tr>
        <tr>
            <td><label>Dirección:</label></td>
            <td><input type="text" name="direccionProveedor" value="{{ old('direccionProveedor') }}"></td>
        </tr>
        <tr>
            <td><label>Teléfono:</label></td>
            <td><input type="text" name="telefonoProveedor" value="{{ old('telefonoProveedor') }}"></td>
        </tr>
        <tr>
            <td><label>Email:</label></td>
            <td><input type="email" name="correoProveedor" value="{{ old('correoProveedor') }}"></td>
        </tr>
        <tr>
            <td><label>Web:</label></td>
            <td><input type="url" name="webProveedor" value="{{ old('webProveedor') }}"></td>
        </tr>
        <tr>
            <td><label>Observaciones:</label></td>
            <td><textarea name="observaciones" rows="3" cols="50">{{ old('observaciones') }}</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Crear Proveedor</button>
                <a href="{{ route('proveedores.index') }}">Cancelar</a>
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