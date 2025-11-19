<?php

namespace App\Http\Controllers;

use App\Models\PrecioVenta;
use App\Models\ArticuloMarca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrecioController extends Controller
{
    public function index(Request $request)
    {
        $query = ArticuloMarca::with(['articulo', 'marca', 'preciosVenta']);
        
        // Búsqueda por producto
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('articulo', function($q) use ($request) {
                $q->where('nombreArticulo', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filtro por productos con descuento
        if ($request->has('con_descuento')) {
            $query->whereHas('preciosVenta', function($q) {
                $q->where('tieneDescuento', 1);
            });
        }
        
        $articulos = $query->orderBy('idArticuloMarca')->paginate(20);
        
        return view('precios.index', compact('articulos'));
    }

    public function actualizar()
    {
        $articulos = ArticuloMarca::with(['articulo', 'marca', 'preciosVenta'])
            ->orderBy('idArticuloMarca')
            ->get();
            
        return view('precios.actualizar', compact('articulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'precios' => 'required|array',
            'precios.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'precios.*.precioVenta' => 'required|numeric|min:0',
            'precios.*.tieneDescuento' => 'sometimes|boolean',
            'precios.*.precioDescuento' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->precios as $precioData) {
                PrecioVenta::create([
                    'idArticuloMarca' => $precioData['idArticuloMarca'],
                    'precioVenta' => $precioData['precioVenta'],
                    'tieneDescuento' => $precioData['tieneDescuento'] ?? false,
                    'precioDescuento' => $precioData['precioDescuento'] ?? null,
                    'fechaActualizacion' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('precios.index')->with('success', 'Precios actualizados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar precios: ' . $e->getMessage());
        }
    }

    public function aplicarDescuento(Request $request, $idArticuloMarca)
    {
        $request->validate([
            'precioDescuento' => 'required|numeric|min:0',
        ]);

        $precioActual = PrecioVenta::where('idArticuloMarca', $idArticuloMarca)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();

        if (!$precioActual) {
            return back()->with('error', 'No existe un precio base para este producto');
        }

        PrecioVenta::create([
            'idArticuloMarca' => $idArticuloMarca,
            'precioVenta' => $precioActual->precioVenta,
            'tieneDescuento' => true,
            'precioDescuento' => $request->precioDescuento,
            'fechaActualizacion' => now()
        ]);

        return redirect()->route('precios.index')->with('success', 'Descuento aplicado correctamente');
    }

    public function quitarDescuento($idArticuloMarca)
    {
        $precioActual = PrecioVenta::where('idArticuloMarca', $idArticuloMarca)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();

        if (!$precioActual) {
            return back()->with('error', 'No existe un precio para este producto');
        }

        PrecioVenta::create([
            'idArticuloMarca' => $idArticuloMarca,
            'precioVenta' => $precioActual->precioVenta,
            'tieneDescuento' => false,
            'precioDescuento' => null,
            'fechaActualizacion' => now()
        ]);

        return redirect()->route('precios.index')->with('success', 'Descuento removido correctamente');
    }

    // MÉTODOS NUEVOS PARA EDICIÓN INDIVIDUAL
    public function editarIndividual($idArticuloMarca)
    {
        $articuloMarca = ArticuloMarca::with(['articulo', 'marca', 'preciosVenta'])
            ->findOrFail($idArticuloMarca);
            
        $precioActual = $articuloMarca->preciosVenta->sortByDesc('fechaActualizacion')->first();
        
        return view('precios.editar-individual', compact('articuloMarca', 'precioActual'));
    }

    public function actualizarIndividual(Request $request, $idArticuloMarca)
    {
        $request->validate([
            'precioVenta' => 'required|numeric|min:0',
            'tieneDescuento' => 'sometimes|boolean',
            'precioDescuento' => 'nullable|numeric|min:0',
        ]);

        try {
            PrecioVenta::create([
                'idArticuloMarca' => $idArticuloMarca,
                'precioVenta' => $request->precioVenta,
                'tieneDescuento' => $request->tieneDescuento ?? false,
                'precioDescuento' => $request->precioDescuento,
                'fechaActualizacion' => now()
            ]);

            return redirect()->route('precios.index')->with('success', 'Precio actualizado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el precio: ' . $e->getMessage());
        }
    }
}