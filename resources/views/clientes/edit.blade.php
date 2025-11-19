@extends('layout.base')

@section('title', 'Editar Cliente')

@section('content')
<h2>Editar Cliente</h2>

<a href="{{ route('clientes.index') }}">← Volver a Clientes</a>

<br><br>

<form method="POST" action="{{ route('clientes.update', $cliente->idCliente) }}">
    @csrf
    @method('PUT')
    
    <table>
        <tr>
            <td><label>Razón Social:</label></td>
            <td><input type="text" name="razonSocial" value="{{ old('razonSocial', $cliente->razonSocial) }}" required></td>
        </tr>
        <tr>
            <td><label>CUIT:</label></td>
            <td><input type="text" name="cuit" value="{{ old('cuit', $cliente->cuit) }}" maxlength="11" required></td>
        </tr>
        <tr>
            <td><label>Dirección:</label></td>
            <td><input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}"></td>
        </tr>
        <tr>
            <td><label>Teléfono:</label></td>
            <td><input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"></td>
        </tr>
        <tr>
            <td><label>Email:</label></td>
            <td><input type="email" name="correo" value="{{ old('correo', $cliente->correo) }}"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Actualizar Cliente</button>
                <a href="{{ route('clientes.index') }}">Cancelar</a>
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