<?php

namespace App\Http\Controllers;

use App\Models\CategoriaArticulo;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = CategoriaArticulo::orderBy('nombreCatArticulo')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreCatArticulo' => 'required|string|max:100|unique:catarticulo,nombreCatArticulo',
            'descripcionCatArticulo' => 'nullable|string|max:200'
        ]);

        CategoriaArticulo::create($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function show($id)
    {
        $categoria = CategoriaArticulo::with(['articulos.articulosMarcas.marca'])->findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }

    public function edit($id)
    {
        $categoria = CategoriaArticulo::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombreCatArticulo' => 'required|string|max:100|unique:catarticulo,nombreCatArticulo,' . $id . ',idCatArticulo',
            'descripcionCatArticulo' => 'nullable|string|max:200'
        ]);

        $categoria = CategoriaArticulo::findOrFail($id);
        $categoria->update($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy($id)
    {
        $categoria = CategoriaArticulo::findOrFail($id);
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente');
    }
}