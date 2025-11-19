<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();
        
        // Búsqueda por razón social o CUIT
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('razonSocialProveedor', 'like', '%' . $search . '%')
                  ->orWhere('cuitProveedor', 'like', '%' . $search . '%')
                  ->orWhere('correoProveedor', 'like', '%' . $search . '%');
            });
        }
        
        $proveedores = $query->orderBy('razonSocialProveedor')->paginate(15);
        
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'razonSocialProveedor' => 'required|string|max:100',
            'cuitProveedor' => 'required|string|size:11|unique:proveedores,cuitProveedor',
            'direccionProveedor' => 'nullable|string|max:150',
            'telefonoProveedor' => 'nullable|string|max:20',
            'correoProveedor' => 'nullable|email|max:100',
            'webProveedor' => 'nullable|url|max:100',
            'observaciones' => 'nullable|string|max:200',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente');
    }

    public function show($id)
    {
        $proveedor = Proveedor::with(['ordenesCompra'])->findOrFail($id);
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'razonSocialProveedor' => 'required|string|max:100',
            'cuitProveedor' => 'required|string|size:11|unique:proveedores,cuitProveedor,' . $id . ',idProveedor',
            'direccionProveedor' => 'nullable|string|max:150',
            'telefonoProveedor' => 'nullable|string|max:20',
            'correoProveedor' => 'nullable|email|max:100',
            'webProveedor' => 'nullable|url|max:100',
            'observaciones' => 'nullable|string|max:200',
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente');
    }
}