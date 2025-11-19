<?php

namespace App\Http\Controllers;

use App\Models\Remito;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemitoController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('remitos')
            ->join('movimientos', 'remitos.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->select(
                'remitos.*',
                'movimientos.idMovimiento',
                'almacenes.nombreAlmacen',
                'usuarios.usuario'
            );
        
        // Filtro por tipo de remito
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipoRemito', $request->tipo);
        }
        
        // Filtro por fecha
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fechaRemito', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fechaRemito', '<=', $request->fecha_hasta);
        }
        
        $remitos = $query->orderBy('fechaRemito', 'desc')->orderBy('idRemito', 'desc')->paginate(15);
        
        return view('remitos.index', compact('remitos'));
    }

    public function show($id)
    {
        $remito = DB::table('remitos')
            ->join('movimientos', 'remitos.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->where('remitos.idRemito', $id)
            ->select(
                'remitos.*',
                'movimientos.*',
                'almacenes.nombreAlmacen',
                'usuarios.usuario'
            )
            ->first();
            
        if (!$remito) {
            abort(404);
        }
        
        // Obtener detalles del movimiento
        $detalles = DB::table('detmovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->where('detmovimiento.idMovimiento', $remito->idMovimiento)
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                'detmovimiento.cantidad'
            )
            ->get();
        
        return view('remitos.show', compact('remito', 'detalles'));
    }

    public function print($id)
    {
        $remito = DB::table('remitos')
            ->join('movimientos', 'remitos.idMovimiento', '=', 'movimientos.idMovimiento')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->where('remitos.idRemito', $id)
            ->select(
                'remitos.*',
                'movimientos.*',
                'almacenes.nombreAlmacen',
                'usuarios.usuario'
            )
            ->first();
            
        if (!$remito) {
            abort(404);
        }
        
        // Obtener detalles del movimiento
        $detalles = DB::table('detmovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->where('detmovimiento.idMovimiento', $remito->idMovimiento)
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                'detmovimiento.cantidad'
            )
            ->get();
        
        return view('remitos.print', compact('remito', 'detalles'));
    }
}