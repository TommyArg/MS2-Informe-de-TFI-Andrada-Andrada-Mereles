<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Control Stock</title>
</head>
<body>
    <h1>Control de Stock</h1>
    
    @if(Auth::check())
    <div>
        <a href="/dashboard">Dashboard</a> |
        <a href="/productos">Productos</a> |
        <a href="/stock">Stock</a> |
        <a href="/movimientos">Movimientos</a> |
        <a href="/clientes">Clientes</a> |
        <a href="/cotizaciones">Cotizaciones</a> |
        <a href="/facturas">Facturas</a> |
        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
        
        <form id="logout-form" action="/logout" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <hr>
    @endif

    @if(session('success'))
        <div style="background: green; color: white; padding: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div style="background: blue; color: white; padding: 10px;">
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: red; color: white; padding: 10px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</body>
</html>