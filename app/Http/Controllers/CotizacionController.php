<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Cliente;
use App\Models\ArticuloMarca;
use App\Models\PrecioVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::with(['cliente']);
        
        // Búsqueda por número de cotización o cliente
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numeroCotizacion', 'like', '%' . $search . '%')
                  ->orWhereHas('cliente', function($q2) use ($search) {
                      $q2->where('razonSocial', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        
        $cotizaciones = $query->orderBy('fechaCotizacion', 'desc')->paginate(15);
        
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado', 'ACTIVO')->orderBy('razonSocial')->get();
        $articulos = ArticuloMarca::with(['articulo', 'marca', 'preciosVenta' => function($query) {
            $query->orderBy('fechaActualizacion', 'desc');
        }])->get();
        
        return view('cotizaciones.create', compact('clientes', 'articulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'validezDias' => 'required|integer|min:1|max:365',
            'observaciones' => 'nullable|string',
            'articulos' => 'required|array|min:1',
            'articulos.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'articulos.*.cantidad' => 'required|integer|min:1',
            'articulos.*.precioUnitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Generar número de cotización
            $ultimaCotizacion = Cotizacion::orderBy('idCotizacion', 'desc')->first();
            $numeroCotizacion = 'COT-' . str_pad(($ultimaCotizacion ? $ultimaCotizacion->idCotizacion : 0) + 1, 4, '0', STR_PAD_LEFT);

            // Calcular totales
            $subtotal = 0;
            foreach ($request->articulos as $articulo) {
                $subtotal += $articulo['cantidad'] * $articulo['precioUnitario'];
            }
            $iva = $subtotal * 0.21; // 21% IVA
            $total = $subtotal + $iva;

            // Crear cotización
            $cotizacion = Cotizacion::create([
                'numeroCotizacion' => $numeroCotizacion,
                'fechaCotizacion' => now(),
                'validezDias' => $request->validezDias,
                'idCliente' => $request->idCliente,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'observaciones' => $request->observaciones,
                'estado' => 'PENDIENTE'
            ]);

            // Crear detalles de cotización
            foreach ($request->articulos as $articulo) {
                DetalleCotizacion::create([
                    'idCotizacion' => $cotizacion->idCotizacion,
                    'idArticuloMarca' => $articulo['idArticuloMarca'],
                    'cantidad' => $articulo['cantidad'],
                    'precioUnitario' => $articulo['precioUnitario']
                ]);
            }

            DB::commit();

            return redirect()->route('cotizaciones.show', $cotizacion->idCotizacion)
                ->with('success', 'Cotización creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la cotización: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::with([
            'cliente',
            'detalles.articuloMarca.articulo',
            'detalles.articuloMarca.marca'
        ])->findOrFail($id);
        
        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function edit($id)
    {
        $cotizacion = Cotizacion::with(['detalles'])->findOrFail($id);
        $clientes = Cliente::where('estado', 'ACTIVO')->orderBy('razonSocial')->get();
        $articulos = ArticuloMarca::with(['articulo', 'marca', 'preciosVenta' => function($query) {
            $query->orderBy('fechaActualizacion', 'desc');
        }])->get();
        
        return view('cotizaciones.edit', compact('cotizacion', 'clientes', 'articulos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'validezDias' => 'required|integer|min:1|max:365',
            'observaciones' => 'nullable|string',
            'articulos' => 'required|array|min:1',
            'articulos.*.idArticuloMarca' => 'required|exists:articulomarca,idArticuloMarca',
            'articulos.*.cantidad' => 'required|integer|min:1',
            'articulos.*.precioUnitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $cotizacion = Cotizacion::findOrFail($id);

            // Calcular totales
            $subtotal = 0;
            foreach ($request->articulos as $articulo) {
                $subtotal += $articulo['cantidad'] * $articulo['precioUnitario'];
            }
            $iva = $subtotal * 0.21;
            $total = $subtotal + $iva;

            // Actualizar cotización
            $cotizacion->update([
                'idCliente' => $request->idCliente,
                'validezDias' => $request->validezDias,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'observaciones' => $request->observaciones,
            ]);

            // Eliminar detalles antiguos
            DetalleCotizacion::where('idCotizacion', $id)->delete();

            // Crear nuevos detalles
            foreach ($request->articulos as $articulo) {
                DetalleCotizacion::create([
                    'idCotizacion' => $cotizacion->idCotizacion,
                    'idArticuloMarca' => $articulo['idArticuloMarca'],
                    'cantidad' => $articulo['cantidad'],
                    'precioUnitario' => $articulo['precioUnitario']
                ]);
            }

            DB::commit();

            return redirect()->route('cotizaciones.show', $cotizacion->idCotizacion)
                ->with('success', 'Cotización actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la cotización: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete();

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada correctamente');
    }

    public function cambiarEstado($id, $estado)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->update(['estado' => $estado]);

        $estadoTexto = $estado == 'APROBADA' ? 'aprobada' : 'rechazada';
        return redirect()->route('cotizaciones.show', $id)->with('success', "Cotización {$estadoTexto} correctamente");
    }

    public function print($id)
    {
        $cotizacion = Cotizacion::with([
            'cliente',
            'detalles.articuloMarca.articulo',
            'detalles.articuloMarca.marca'
        ])->findOrFail($id);
        
        return view('cotizaciones.print', compact('cotizacion'));
    }

    public function getPrecioArticulo($idArticuloMarca)
    {
        $precio = PrecioVenta::where('idArticuloMarca', $idArticuloMarca)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();
            
        return response()->json([
            'precio' => $precio ? $precio->precioVenta : 0,
            'precioConIva' => $precio ? $precio->precioVenta * 1.21 : 0
        ]);
    }

    /**
     * Obtiene el precio actual de un artículo-marca
     * Método público para usar en vistas
     */
    public function getPrecioActual($idArticuloMarca)
    {
        $precio = PrecioVenta::where('idArticuloMarca', $idArticuloMarca)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();
            
        if ($precio && $precio->tieneDescuento && $precio->precioDescuento) {
            return (float) $precio->precioDescuento;
        }
        
        return $precio ? (float) $precio->precioVenta : 0;
    }
}