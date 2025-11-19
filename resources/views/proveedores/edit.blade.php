@extends('layout.base')

@section('title', 'Editar Proveedor')

@section('content')
<h2>Editar Proveedor</h2>

<a href="{{ route('proveedores.index') }}">← Volver a Proveedores</a>

<br><br>

<form method="POST" action="{{ route('proveedores.update', $proveedor->idProveedor) }}">
    @csrf
    @method('PUT')
    
    <table>
        <tr>
            <td><label>Razón Social:</label></td>
            <td><input type="text" name="razonSocialProveedor" value="{{ old('razonSocialProveedor', $proveedor->razonSocialProveedor) }}" required></td>
        </tr>
        <tr>
            <td><label>CUIT:</label></td>
            <td><input type="text" name="cuitProveedor" value="{{ old('cuitProveedor', $proveedor->cuitProveedor) }}" maxlength="11" required></td>
        </tr>
        <tr>
            <td><label>Dirección:</label></td>
            <td><input type="text" name="direccionProveedor" value="{{ old('direccionProveedor', $proveedor->direccionProveedor) }}"></td>
        </tr>
        <tr>
            <td><label>Teléfono:</label></td>
            <td><input type="text" name="telefonoProveedor" value="{{ old('telefonoProveedor', $proveedor->telefonoProveedor) }}"></td>
        </tr>
        <tr>
            <td><label>Email:</label></td>
            <td><input type="email" name="correoProveedor" value="{{ old('correoProveedor', $proveedor->correoProveedor) }}"></td>
        </tr>
        <tr>
            <td><label>Web:</label></td>
            <td><input type="url" name="webProveedor" value="{{ old('webProveedor', $proveedor->webProveedor) }}"></td>
        </tr>
        <tr>
            <td><label>Observaciones:</label></td>
            <td><textarea name="observaciones" rows="3" cols="50">{{ old('observaciones', $proveedor->observaciones) }}</textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Actualizar Proveedor</button>
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