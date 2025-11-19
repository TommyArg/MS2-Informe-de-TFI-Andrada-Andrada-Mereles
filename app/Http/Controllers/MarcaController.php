<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::orderBy('nombreMarca')->get();
        return view('marcas.index', compact('marcas'));
    }

    public function create()
    {
        return view('marcas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreMarca' => 'required|string|max:100|unique:marcas,nombreMarca',
            'descripcionMarca' => 'nullable|string|max:200'
        ]);

        Marca::create($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca creada correctamente');
    }

    public function show($id)
    {
        $marca = Marca::with(['articulosMarcas.articulo.categoria'])->findOrFail($id);
        return view('marcas.show', compact('marca'));
    }

    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return view('marcas.edit', compact('marca'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombreMarca' => 'required|string|max:100|unique:marcas,nombreMarca,' . $id . ',idMarca',
            'descripcionMarca' => 'nullable|string|max:200'
        ]);

        $marca = Marca::findOrFail($id);
        $marca->update($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente');
    }

    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();
        return redirect()->route('marcas.index')->with('success', 'Marca eliminada correctamente');
    }
}