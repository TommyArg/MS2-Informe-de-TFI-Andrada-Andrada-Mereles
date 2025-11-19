@extends('layout.base')

@section('title', 'Login')

@section('content')
<h2>Iniciar Sesión</h2>

<form method="POST" action="/login">
    @csrf
    
    <div>
        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required>
    </div>

    <br>
    <button type="submit">Ingresar</button>
</form>

@if($errors->has('email'))
    <div style="color: red;">
        {{ $errors->first('email') }}
    </div>
@endif

<br>
<div>
    <strong>Credenciales de prueba:</strong><br>
    Email: admin@tutimotos.com<br>
    Contraseña: admin123
</div>
@endsection