<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\ArticuloMarca;
use App\Models\Marca;
use App\Models\CategoriaArticulo;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Articulo::with(['categoria', 'articulosMarcas.marca', 'articulosMarcas.preciosVenta']);
        
        // Búsqueda por nombre
        if ($request->has('search') && $request->search != '') {
            $query->where('nombreArticulo', 'like', '%' . $request->search . '%');
        }
        
        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('idCatArticulo', $request->categoria);
        }
        
        $productos = $query->orderBy('nombreArticulo')->get();
        $categorias = CategoriaArticulo::all();
        
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = CategoriaArticulo::all();
        $marcas = Marca::all();
        return view('productos.create', compact('categorias', 'marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreArticulo' => 'required|string|max:100',
            'idCatArticulo' => 'required|exists:catarticulo,idCatArticulo',
            'descripcionArticulo' => 'nullable|string|max:200',
            'idMarca' => 'required|exists:marcas,idMarca'
        ]);

        // Crear el artículo
        $articulo = Articulo::create([
            'nombreArticulo' => $request->nombreArticulo,
            'idCatArticulo' => $request->idCatArticulo,
            'descripcionArticulo' => $request->descripcionArticulo,
        ]);

        // Crear la relación artículo-marca
        ArticuloMarca::create([
            'idArticulo' => $articulo->idArticulo,
            'idMarca' => $request->idMarca,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
    }

    public function show($id)
    {
        $producto = Articulo::with([
            'categoria', 
            'articulosMarcas.marca', 
            'articulosMarcas.preciosVenta',
            'stocks.almacen'
        ])->findOrFail($id);
        
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Articulo::with('articulosMarcas')->findOrFail($id);
        $categorias = CategoriaArticulo::all();
        $marcas = Marca::all();
        return view('productos.edit', compact('producto', 'categorias', 'marcas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombreArticulo' => 'required|string|max:100',
            'idCatArticulo' => 'required|exists:catarticulo,idCatArticulo',
            'descripcionArticulo' => 'nullable|string|max:200',
            'idMarca' => 'required|exists:marcas,idMarca'
        ]);

        $articulo = Articulo::findOrFail($id);
        $articulo->update([
            'nombreArticulo' => $request->nombreArticulo,
            'idCatArticulo' => $request->idCatArticulo,
            'descripcionArticulo' => $request->descripcionArticulo,
        ]);

        // Actualizar la relación artículo-marca
        $articuloMarca = $articulo->articulosMarcas->first();
        if ($articuloMarca) {
            $articuloMarca->update(['idMarca' => $request->idMarca]);
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
}