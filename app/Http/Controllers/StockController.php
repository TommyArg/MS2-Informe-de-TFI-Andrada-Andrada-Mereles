<?php

namespace App\Http\Controllers;

use App\Models\StockPorAlmacen;
use App\Models\Articulo;
use App\Models\Almacen;
use App\Models\ArticuloMarca;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockPorAlmacen::with([
            'articuloMarca.articulo', 
            'articuloMarca.marca', 
            'articuloMarca.preciosVenta',
            'almacen'
        ]);
        
        // Búsqueda por nombre de producto
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('articuloMarca.articulo', function($q) use ($request) {
                $q->where('nombreArticulo', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filtro por almacén
        if ($request->has('almacen') && $request->almacen != '') {
            $query->where('idAlmacen', $request->almacen);
        }
        
        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->whereHas('articuloMarca.articulo', function($q) use ($request) {
                $q->where('idCatArticulo', $request->categoria);
            });
        }
        
        // Stock bajo
        if ($request->has('stock_bajo')) {
            $query->where('cantidadActual', '<', 10);
        }
        
        $stock = $query->orderBy('idAlmacen')->orderBy('cantidadActual', 'desc')->get();
        $almacenes = Almacen::where('estadoAlmacen', 'ACTIVO')->get();
        $categorias = \App\Models\CategoriaArticulo::all();
        
        return view('stock.index', compact('stock', 'almacenes', 'categorias'));
    }

    public function porAlmacen($idAlmacen)
    {
        $almacen = Almacen::findOrFail($idAlmacen);
        $stock = StockPorAlmacen::with(['articuloMarca.articulo', 'articuloMarca.marca'])
            ->where('idAlmacen', $idAlmacen)
            ->orderBy('cantidadActual', 'desc')
            ->get();
            
        return view('stock.almacen', compact('stock', 'almacen'));
    }

    public function stockBajo()
    {
        $stockBajo = StockPorAlmacen::with(['articuloMarca.articulo', 'articuloMarca.marca', 'almacen'])
            ->where('cantidadActual', '<', 10)
            ->orderBy('cantidadActual', 'asc')
            ->get();
            
        return view('stock.bajo', compact('stockBajo'));
    }
}