<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioVenta extends Model
{
    use HasFactory;

    protected $table = 'precios_venta';
    protected $primaryKey = 'idPrecioVenta';
    public $timestamps = false;

    protected $fillable = [
        'idArticuloMarca',
        'precioVenta',
        'tieneDescuento',
        'precioDescuento',
        'fechaActualizacion'
    ];

    public function articuloMarca()
    {
        return $this->belongsTo(ArticuloMarca::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}