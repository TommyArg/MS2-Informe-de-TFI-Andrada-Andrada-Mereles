<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Movimiento;
use App\Models\Cliente;
use App\Models\DetMovimiento;
use App\Models\PrecioVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with(['cliente', 'movimiento']);
        
        // Búsqueda por número de factura o cliente
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numeroFactura', 'like', '%' . $search . '%')
                  ->orWhereHas('cliente', function($q2) use ($search) {
                      $q2->where('razonSocial', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        
        // Filtro por tipo de factura
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipoFactura', $request->tipo);
        }
        
        $facturas = $query->orderBy('fechaFactura', 'desc')->paginate(15);
        
        return view('facturas.index', compact('facturas'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado', 'ACTIVO')->orderBy('razonSocial')->get();
        $movimientos = Movimiento::with(['almacen', 'detalles.articuloMarca.articulo', 'detalles.articuloMarca.marca'])
            ->where('tipoMovimiento', 'SALIDA')
            ->whereDoesntHave('factura')
            ->orderBy('fechaMovimiento', 'desc')
            ->get();
            
        return view('facturas.create', compact('clientes', 'movimientos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idMovimiento' => 'required|exists:movimientos,idMovimiento',
            'idCliente' => 'required|exists:clientes,idCliente',
            'tipoFactura' => 'required|in:A,B,C',
            'descuentoEfectivo' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $movimiento = Movimiento::with(['detalles.articuloMarca'])->findOrFail($request->idMovimiento);
            
            // Verificar que el movimiento no tenga factura
            if ($movimiento->factura) {
                throw new \Exception('Este movimiento ya tiene una factura asociada.');
            }

            // Calcular totales basados en los precios de venta actuales
            $subtotal = 0;
            foreach ($movimiento->detalles as $detalle) {
                $precioVenta = $this->getPrecioVenta($detalle->idArticuloMarca);
                $subtotal += $detalle->cantidad * $precioVenta;
            }

            // Aplicar descuento en efectivo si existe
            $descuentoEfectivo = $request->descuentoEfectivo ?? 0;
            if ($descuentoEfectivo > 0) {
                $subtotal = max(0, $subtotal - $descuentoEfectivo);
            }

            $iva = $subtotal * 0.21;
            $total = $subtotal + $iva;

            // Generar número de factura
            $ultimaFactura = Factura::orderBy('idFactura', 'desc')->first();
            $numeroFactura = 'F' . $request->tipoFactura . '-' . str_pad(($ultimaFactura ? $ultimaFactura->idFactura : 0) + 1, 6, '0', STR_PAD_LEFT);

            // Crear factura
            $factura = Factura::create([
                'idMovimiento' => $request->idMovimiento,
                'numeroFactura' => $numeroFactura,
                'fechaFactura' => now(),
                'tipoFactura' => $request->tipoFactura,
                'idCliente' => $request->idCliente,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'descuentoEfectivo' => $descuentoEfectivo,
                'estado' => 'PENDIENTE'
            ]);

            DB::commit();

            return redirect()->route('facturas.show', $factura->idFactura)
                ->with('success', 'Factura creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la factura: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $factura = Factura::with([
            'cliente',
            'movimiento.almacen',
            'movimiento.detalles.articuloMarca.articulo',
            'movimiento.detalles.articuloMarca.marca'
        ])->findOrFail($id);
        
        // Calcular precios actualizados para mostrar en la vista
        $detallesConPrecios = [];
        $subtotal = 0;
        
        foreach ($factura->movimiento->detalles as $detalle) {
            $precioVenta = $this->getPrecioVenta($detalle->idArticuloMarca);
            $subtotalDetalle = $detalle->cantidad * $precioVenta;
            $subtotal += $subtotalDetalle;
            
            $detallesConPrecios[] = [
                'detalle' => $detalle,
                'precio_actual' => $precioVenta,
                'subtotal_actual' => $subtotalDetalle
            ];
        }
        
        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;
        
        return view('facturas.show', compact('factura', 'detallesConPrecios', 'subtotal', 'iva', 'total'));
    }

    public function edit($id)
    {
        $factura = Factura::with(['movimiento'])->findOrFail($id);
        $clientes = Cliente::where('estado', 'ACTIVO')->orderBy('razonSocial')->get();
        
        return view('facturas.edit', compact('factura', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'tipoFactura' => 'required|in:A,B,C',
            'descuentoEfectivo' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $factura = Factura::with(['movimiento.detalles.articuloMarca'])->findOrFail($id);

            // Recalcular totales
            $subtotal = 0;
            foreach ($factura->movimiento->detalles as $detalle) {
                $precioVenta = $this->getPrecioVenta($detalle->idArticuloMarca);
                $subtotal += $detalle->cantidad * $precioVenta;
            }

            // Aplicar descuento en efectivo
            $descuentoEfectivo = $request->descuentoEfectivo ?? 0;
            if ($descuentoEfectivo > 0) {
                $subtotal = max(0, $subtotal - $descuentoEfectivo);
            }

            $iva = $subtotal * 0.21;
            $total = $subtotal + $iva;

            // Actualizar factura
            $factura->update([
                'idCliente' => $request->idCliente,
                'tipoFactura' => $request->tipoFactura,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'descuentoEfectivo' => $descuentoEfectivo,
            ]);

            DB::commit();

            return redirect()->route('facturas.show', $factura->idFactura)
                ->with('success', 'Factura actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la factura: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada correctamente');
    }

    public function cambiarEstado($id, $estado)
    {
        $factura = Factura::findOrFail($id);
        $factura->update(['estado' => $estado]);

        $estadoTexto = $estado == 'PAGADA' ? 'marcada como pagada' : 'cancelada';
        return redirect()->route('facturas.show', $id)->with('success', "Factura {$estadoTexto} correctamente");
    }

    public function print($id)
    {
        $factura = Factura::with([
            'cliente',
            'movimiento.almacen',
            'movimiento.detalles.articuloMarca.articulo',
            'movimiento.detalles.articuloMarca.marca'
        ])->findOrFail($id);
        
        return view('facturas.print', compact('factura'));
    }

    /**
     * Obtiene el precio de venta actual de un artículo-marca
     * Cambiado a público para poder usarse en las vistas
     */
    public function getPrecioVenta($idArticuloMarca)
    {
        $precio = PrecioVenta::where('idArticuloMarca', $idArticuloMarca)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();
            
        if ($precio && $precio->tieneDescuento && $precio->precioDescuento) {
            return (float) $precio->precioDescuento;
        }
        
        return $precio ? (float) $precio->precioVenta : 0;
    }

    public function getMovimientoDetalles($idMovimiento)
    {
        $movimiento = Movimiento::with(['detalles.articuloMarca.articulo', 'detalles.articuloMarca.marca'])->findOrFail($idMovimiento);
        
        $detalles = [];
        $subtotal = 0;
        
        foreach ($movimiento->detalles as $detalle) {
            $precioVenta = $this->getPrecioVenta($detalle->idArticuloMarca);
            $precioVenta = $precioVenta ? (float) $precioVenta : 0; // Asegurar que sea número
            $subtotalDetalle = $detalle->cantidad * $precioVenta;
            $subtotal += $subtotalDetalle;
            
            $detalles[] = [
                'producto' => $detalle->articuloMarca->articulo->nombreArticulo . ' - ' . $detalle->articuloMarca->marca->nombreMarca,
                'cantidad' => (int) $detalle->cantidad,
                'precioUnitario' => $precioVenta,
                'subtotal' => $subtotalDetalle
            ];
        }
        
        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;
        
        return response()->json([
            'detalles' => $detalles,
            'subtotal' => (float) $subtotal,
            'iva' => (float) $iva,
            'total' => (float) $total
        ]);
    }
}