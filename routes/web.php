<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\RemitoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Productos
    Route::resource('productos', ProductoController::class);
    
    // Marcas
    Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
    Route::get('/marcas/create', [MarcaController::class, 'create'])->name('marcas.create');
    Route::post('/marcas', [MarcaController::class, 'store'])->name('marcas.store');
    Route::get('/marcas/{id}', [MarcaController::class, 'show'])->name('marcas.show');
    Route::get('/marcas/{id}/edit', [MarcaController::class, 'edit'])->name('marcas.edit');
    Route::put('/marcas/{id}', [MarcaController::class, 'update'])->name('marcas.update');
    Route::delete('/marcas/{id}', [MarcaController::class, 'destroy'])->name('marcas.destroy');
    
    // Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}', [CategoriaController::class, 'show'])->name('categorias.show');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    
    // Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/almacen/{id}', [StockController::class, 'porAlmacen'])->name('stock.almacen');
    Route::get('/stock/bajo', [StockController::class, 'stockBajo'])->name('stock.bajo');
    
    // Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::get('/movimientos/entrada', [MovimientoController::class, 'createEntrada'])->name('movimientos.entrada');
    Route::post('/movimientos/entrada', [MovimientoController::class, 'storeEntrada'])->name('movimientos.entrada.store');
    Route::get('/movimientos/salida', [MovimientoController::class, 'createSalida'])->name('movimientos.salida');
    Route::post('/movimientos/salida', [MovimientoController::class, 'storeSalida'])->name('movimientos.salida.store');
    Route::get('/movimientos/{id}', [MovimientoController::class, 'show'])->name('movimientos.show');
    Route::get('/movimientos/stock/{idAlmacen}/{idArticuloMarca}', [MovimientoController::class, 'getStockArticulo'])->name('movimientos.stock');
    
    // Remitos
    Route::get('/remitos', [RemitoController::class, 'index'])->name('remitos.index');
    Route::get('/remitos/{id}', [RemitoController::class, 'show'])->name('remitos.show');
    Route::get('/remitos/{id}/print', [RemitoController::class, 'print'])->name('remitos.print');
    
    // Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::post('/clientes/{id}/activate', [ClienteController::class, 'activate'])->name('clientes.activate');
    
    // Proveedores
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{id}', [ProveedorController::class, 'show'])->name('proveedores.show');
    Route::get('/proveedores/{id}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
    Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    
    // Cotizaciones
    Route::get('/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::post('/cotizaciones', [CotizacionController::class, 'store'])->name('cotizaciones.store');
    Route::get('/cotizaciones/{id}', [CotizacionController::class, 'show'])->name('cotizaciones.show');
    Route::get('/cotizaciones/{id}/edit', [CotizacionController::class, 'edit'])->name('cotizaciones.edit');
    Route::put('/cotizaciones/{id}', [CotizacionController::class, 'update'])->name('cotizaciones.update');
    Route::delete('/cotizaciones/{id}', [CotizacionController::class, 'destroy'])->name('cotizaciones.destroy');
    Route::post('/cotizaciones/{id}/estado/{estado}', [CotizacionController::class, 'cambiarEstado'])->name('cotizaciones.estado');
    Route::get('/cotizaciones/{id}/print', [CotizacionController::class, 'print'])->name('cotizaciones.print');
    Route::get('/cotizaciones/precio/{idArticuloMarca}', [CotizacionController::class, 'getPrecioArticulo'])->name('cotizaciones.precio');
    
    // Precios
    Route::get('/precios', [PrecioController::class, 'index'])->name('precios.index');
    Route::get('/precios/actualizar', [PrecioController::class, 'actualizar'])->name('precios.actualizar');
    Route::post('/precios', [PrecioController::class, 'store'])->name('precios.store');
    Route::post('/precios/{idArticuloMarca}/aplicar-descuento', [PrecioController::class, 'aplicarDescuento'])->name('precios.aplicar-descuento');
    Route::post('/precios/{idArticuloMarca}/quitar-descuento', [PrecioController::class, 'quitarDescuento'])->name('precios.quitar-descuento');
    
    // Ruta para editar precio individual
    Route::get('/precios/editar/{idArticuloMarca}', [PrecioController::class, 'editarIndividual'])->name('precios.editar');
    Route::post('/precios/actualizar-individual/{idArticuloMarca}', [PrecioController::class, 'actualizarIndividual'])->name('precios.actualizar-individual');
    
    // Facturas
    Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('/facturas/create', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturas/{id}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('/facturas/{id}/edit', [FacturaController::class, 'edit'])->name('facturas.edit');
    Route::put('/facturas/{id}', [FacturaController::class, 'update'])->name('facturas.update');
    Route::delete('/facturas/{id}', [FacturaController::class, 'destroy'])->name('facturas.destroy');
    Route::post('/facturas/{id}/estado/{estado}', [FacturaController::class, 'cambiarEstado'])->name('facturas.estado');
    Route::get('/facturas/{id}/print', [FacturaController::class, 'print'])->name('facturas.print');
    Route::get('/facturas/movimiento/{id}/detalles', [FacturaController::class, 'getMovimientoDetalles'])->name('facturas.movimiento.detalles');
    
    // Órdenes de Compra
    Route::get('/ordenes-compra', [OrdenCompraController::class, 'index'])->name('ordenes-compra.index');
    Route::get('/ordenes-compra/create', [OrdenCompraController::class, 'create'])->name('ordenes-compra.create');
    Route::post('/ordenes-compra', [OrdenCompraController::class, 'store'])->name('ordenes-compra.store');
    Route::get('/ordenes-compra/{id}', [OrdenCompraController::class, 'show'])->name('ordenes-compra.show');
    Route::get('/ordenes-compra/{id}/edit', [OrdenCompraController::class, 'edit'])->name('ordenes-compra.edit');
    Route::put('/ordenes-compra/{id}', [OrdenCompraController::class, 'update'])->name('ordenes-compra.update');
    Route::delete('/ordenes-compra/{id}', [OrdenCompraController::class, 'destroy'])->name('ordenes-compra.destroy');
    Route::post('/ordenes-compra/{id}/estado/{estado}', [OrdenCompraController::class, 'cambiarEstado'])->name('ordenes-compra.estado');
    Route::get('/ordenes-compra/{id}/print', [OrdenCompraController::class, 'print'])->name('ordenes-compra.print');
    Route::get('/ordenes-compra/precio/{idArticuloMarca}', [OrdenCompraController::class, 'getPrecioArticulo'])->name('ordenes-compra.precio');
    
    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('/reportes/stock', [ReporteController::class, 'stock'])->name('reportes.stock');
    Route::get('/reportes/movimientos', [ReporteController::class, 'movimientos'])->name('reportes.movimientos');
    Route::get('/reportes/productos-mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('reportes.productos-mas-vendidos');
});

// Redirección por defecto
Route::redirect('/home', '/dashboard');