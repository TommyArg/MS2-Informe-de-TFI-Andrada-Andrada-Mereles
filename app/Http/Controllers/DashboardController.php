<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas básicas para el dashboard
        $stats = [
            'total_productos' => DB::table('articulos')->count(),
            'total_clientes' => DB::table('clientes')->where('estado', 'ACTIVO')->count(),
            'total_proveedores' => DB::table('proveedores')->count(),
            'stock_total' => DB::table('stockporalmacen')->sum('cantidadActual'),
            'ventas_mes' => DB::table('facturas')
                ->where('estado', 'PAGADA')
                ->whereMonth('fechaFactura', now()->month)
                ->sum('total') ?? 0,
            'cotizaciones_pendientes' => DB::table('cotizaciones')
                ->where('estado', 'PENDIENTE')
                ->count(),
            'ordenes_pendientes' => DB::table('ordencompra')
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