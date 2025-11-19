<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function ventas(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : Carbon::now()->startOfMonth();
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : Carbon::now()->endOfMonth();

        $ventas = DB::table('facturas')
            ->join('clientes', 'facturas.idCliente', '=', 'clientes.idCliente')
            ->whereBetween('facturas.fechaFactura', [$fechaInicio, $fechaFin])
            ->where('facturas.estado', 'PAGADA')
            ->select(
                'facturas.numeroFactura',
                'facturas.fechaFactura',
                'clientes.razonSocial',
                'facturas.subtotal',
                'facturas.iva',
                'facturas.total',
                'facturas.descuentoEfectivo'
            )
            ->orderBy('facturas.fechaFactura', 'desc')
            ->get();

        $totalVentas = $ventas->sum('total');
        $totalIva = $ventas->sum('iva');
        $totalDescuentos = $ventas->sum('descuentoEfectivo');

        return view('reportes.ventas', compact('ventas', 'totalVentas', 'totalIva', 'totalDescuentos', 'fechaInicio', 'fechaFin'));
    }

    public function stock(Request $request)
    {
        $stock = DB::table('stockporalmacen')
            ->join('articulomarca', 'stockporalmacen.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->join('almacenes', 'stockporalmacen.idAlmacen', '=', 'almacenes.idAlmacen')
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                'almacenes.nombreAlmacen',
                'stockporalmacen.cantidadActual',
                'stockporalmacen.ultimaActualizacion'
            )
            ->orderBy('stockporalmacen.cantidadActual', 'asc')
            ->get();

        $stockBajo = $stock->where('cantidadActual', '<', 10);
        $stockTotal = $stock->sum('cantidadActual');
        $productosConStockBajo = $stockBajo->count();

        return view('reportes.stock', compact('stock', 'stockBajo', 'stockTotal', 'productosConStockBajo'));
    }

    public function movimientos(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : Carbon::now()->startOfMonth();
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : Carbon::now()->endOfMonth();

        $movimientos = DB::table('movimientos')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->whereBetween('movimientos.fechaMovimiento', [$fechaInicio, $fechaFin])
            ->select(
                'movimientos.idMovimiento',
                'movimientos.tipoMovimiento',
                'movimientos.fechaMovimiento',
                'almacenes.nombreAlmacen',
                'usuarios.usuario',
                'movimientos.observaciones'
            )
            ->orderBy('movimientos.fechaMovimiento', 'desc')
            ->get();

        // Obtener detalles de productos para cada movimiento
        foreach ($movimientos as $movimiento) {
            $movimiento->detalles = DB::table('detmovimiento')
                ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
                ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
                ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
                ->where('detmovimiento.idMovimiento', $movimiento->idMovimiento)
                ->select(
                    'articulos.nombreArticulo',
                    'marcas.nombreMarca',
                    'detmovimiento.cantidad'
                )
                ->get();
        }

        $entradas = $movimientos->where('tipoMovimiento', 'ENTRADA')->count();
        $salidas = $movimientos->where('tipoMovimiento', 'SALIDA')->count();

        return view('reportes.movimientos', compact('movimientos', 'entradas', 'salidas', 'fechaInicio', 'fechaFin'));
    }

    public function productosMasVendidos(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : Carbon::now()->startOfMonth();
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : Carbon::now()->endOfMonth();

        $productos = DB::table('detmovimiento')
            ->join('movimientos', 'detmovimiento.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->where('movimientos.tipoMovimiento', 'SALIDA')
            ->whereBetween('movimientos.fechaMovimiento', [$fechaInicio, $fechaFin])
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                DB::raw('SUM(detmovimiento.cantidad) as total_vendido')
            )
            ->groupBy('articulos.idArticulo', 'articulos.nombreArticulo', 'marcas.nombreMarca')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return view('reportes.productos-mas-vendidos', compact('productos', 'fechaInicio', 'fechaFin'));
    }

    public function dashboard()
    {
        // Estadísticas para el dashboard
        $stats = [
            'total_productos' => DB::table('articulos')->count(),
            'total_clientes' => DB::table('clientes')->where('estado', 'ACTIVO')->count(),
            'total_proveedores' => DB::table('proveedores')->count(),
            'stock_total' => DB::table('stockporalmacen')->sum('cantidadActual'),
            'ventas_mes' => DB::table('facturas')
                ->where('estado', 'PAGADA')
                ->whereMonth('fechaFactura', now()->month)
                ->sum('total'),
            'cotizaciones_pendientes' => DB::table('cotizaciones')
                ->where('estado', 'PENDIENTE')
                ->count(),
        ];

        // Movimientos recientes
        $movimientos_recientes = DB::table('movimientos')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->select('movimientos.*', 'usuarios.usuario', 'almacenes.nombreAlmacen')
            ->orderBy('fechaMovimiento', 'desc')
            ->limit(5)
            ->get();

        // Stock bajo
        $stock_bajo = DB::table('stockporalmacen')
            ->join('articulomarca', 'stockporalmacen.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('almacenes', 'stockporalmacen.idAlmacen', '=', 'almacenes.idAlmacen')
            ->where('cantidadActual', '<', 10)
            ->select('articulos.nombreArticulo', 'almacenes.nombreAlmacen', 'cantidadActual')
            ->get();

        // Productos más vendidos del mes
        $productos_mas_vendidos = DB::table('detmovimiento')
            ->join('movimientos', 'detmovimiento.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->where('movimientos.tipoMovimiento', 'SALIDA')
            ->whereMonth('movimientos.fechaMovimiento', now()->month)
            ->select(
                'articulos.nombreArticulo',
                DB::raw('SUM(detmovimiento.cantidad) as total_vendido')
            )
            ->groupBy('articulos.idArticulo', 'articulos.nombreArticulo')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'movimientos_recientes', 'stock_bajo', 'productos_mas_vendidos'));
    }
}