<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\DetMovimiento;
use App\Models\ArticuloMarca;
use App\Models\Almacen;
use App\Models\StockPorAlmacen;
use App\Models\Remito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index()
    {
        // Usar consulta directa con JOIN para evitar problemas de relación
        $movimientos = DB::table('movimientos')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->leftJoin('remitos', 'movimientos.idMovimiento', '=', 'remitos.idMovimiento')
            ->select(
                'movimientos.*',
                'usuarios.usuario',
                'almacenes.nombreAlmacen',
                'remitos.numeroRemito',
                'remitos.idRemito'
            )
            ->orderBy('movimientos.fechaMovimiento', 'desc')
            ->paginate(10);

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

        return view('movimientos.index', compact('movimientos'));
    }

    public function createEntrada()
    {
        $almacenes = Almacen::where('estadoAlmacen', 'ACTIVO')->get();
        $articulos = ArticuloMarca::with(['articulo', 'marca'])->get();
        
        return view('movimientos.entrada', compact('almacenes', 'articulos'));
    }

    public function storeEntrada(Request $request)
    {
        $request->validate([
            'idAlmacen' => 'required|exists:almacenes,idAlmacen',
            'observaciones' => 'nullable|string|max:200',
            'articulos' => 'required|array|min:1',
            'articulos.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'articulos.*.cantidad' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // Obtener el ID del usuario desde la tabla usuarios (no desde users)
            // Como no hay autenticación en la tabla usuarios, usaremos el usuario por defecto (id 1)
            $idUsuario = 1; // jperez

            // Crear movimiento de entrada
            $movimiento = Movimiento::create([
                'tipoMovimiento' => 'ENTRADA',
                'idUsuario' => $idUsuario, // Cambiado de auth()->user()->id a $idUsuario
                'idAlmacen' => $request->idAlmacen,
                'observaciones' => $request->observaciones,
                'fechaMovimiento' => now()
            ]);

            // Resto del código permanece igual...
            foreach ($request->articulos as $articulo) {
                DetMovimiento::create([
                    'idMovimiento' => $movimiento->idMovimiento,
                    'idArticuloMarca' => $articulo['idArticuloMarca'],
                    'cantidad' => $articulo['cantidad']
                ]);
            }

            DB::commit();

            return redirect()->route('movimientos.show', $movimiento->idMovimiento)
                ->with('success', 'Entrada de stock registrada correctamente. Remito generado automáticamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la entrada: ' . $e->getMessage());
        }
    }

    public function createSalida()
    {
        $almacenes = Almacen::where('estadoAlmacen', 'ACTIVO')->get();
        $articulos = ArticuloMarca::with(['articulo', 'marca', 'stocks'])->get();
        
        return view('movimientos.salida', compact('almacenes', 'articulos'));
    }

    public function storeSalida(Request $request)
    {
        $request->validate([
            'idAlmacen' => 'required|exists:almacenes,idAlmacen',
            'observaciones' => 'nullable|string|max:200',
            'articulos' => 'required|array|min:1',
            'articulos.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'articulos.*.cantidad' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // Obtener el ID del usuario desde la tabla usuarios (no desde users)
            $idUsuario = 1; // jperez

            // Validar stock disponible antes de crear movimiento
            foreach ($request->articulos as $articulo) {
                $stock = StockPorAlmacen::where('idAlmacen', $request->idAlmacen)
                    ->where('idArticuloMarca', $articulo['idArticuloMarca'])
                    ->first();

                if (!$stock || $stock->cantidadActual < $articulo['cantidad']) {
                    $articuloInfo = ArticuloMarca::with(['articulo', 'marca'])->find($articulo['idArticuloMarca']);
                    $nombreArticulo = $articuloInfo->articulo->nombreArticulo . ' - ' . $articuloInfo->marca->nombreMarca;
                    $stockDisponible = $stock ? $stock->cantidadActual : 0;
                    
                    DB::rollBack();
                    return back()->with('error', "Stock insuficiente para $nombreArticulo. Stock disponible: $stockDisponible, solicitado: {$articulo['cantidad']}");
                }
            }

            // Crear movimiento de salida
            $movimiento = Movimiento::create([
                'tipoMovimiento' => 'SALIDA',
                'idUsuario' => $idUsuario, // Cambiado de auth()->user()->id a $idUsuario
                'idAlmacen' => $request->idAlmacen,
                'observaciones' => $request->observaciones,
                'fechaMovimiento' => now()
            ]);

            // Resto del código permanece igual...
            foreach ($request->articulos as $articulo) {
                DetMovimiento::create([
                    'idMovimiento' => $movimiento->idMovimiento,
                    'idArticuloMarca' => $articulo['idArticuloMarca'],
                    'cantidad' => $articulo['cantidad']
                ]);
            }

            DB::commit();

            return redirect()->route('movimientos.show', $movimiento->idMovimiento)
                ->with('success', 'Salida de stock registrada correctamente. Remito generado automáticamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Usar consulta directa para evitar problemas de relación
        $movimiento = DB::table('movimientos')
            ->join('usuarios', 'movimientos.idUsuario', '=', 'usuarios.idUsuario')
            ->join('almacenes', 'movimientos.idAlmacen', '=', 'almacenes.idAlmacen')
            ->leftJoin('remitos', 'movimientos.idMovimiento', '=', 'remitos.idMovimiento')
            ->where('movimientos.idMovimiento', $id)
            ->select(
                'movimientos.*',
                'usuarios.usuario',
                'almacenes.nombreAlmacen',
                'remitos.numeroRemito',
                'remitos.idRemito'
            )
            ->first();

        if (!$movimiento) {
            abort(404);
        }

        // Obtener detalles del movimiento
        $detalles = DB::table('detmovimiento')
            ->join('articulomarca', 'detmovimiento.idArticuloMarca', '=', 'articulomarca.idArticuloMarca')
            ->join('articulos', 'articulomarca.idArticulo', '=', 'articulos.idArticulo')
            ->join('marcas', 'articulomarca.idMarca', '=', 'marcas.idMarca')
            ->where('detmovimiento.idMovimiento', $id)
            ->select(
                'articulos.nombreArticulo',
                'marcas.nombreMarca',
                'detmovimiento.cantidad'
            )
            ->get();

        return view('movimientos.show', compact('movimiento', 'detalles'));
    }

    public function getStockArticulo($idAlmacen, $idArticuloMarca)
    {
        $stock = StockPorAlmacen::where('idAlmacen', $idAlmacen)
            ->where('idArticuloMarca', $idArticuloMarca)
            ->first();
            
        return response()->json([
            'stock' => $stock ? $stock->cantidadActual : 0
        ]);
    }
}