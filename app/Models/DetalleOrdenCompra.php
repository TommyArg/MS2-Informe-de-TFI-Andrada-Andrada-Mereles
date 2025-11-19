<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'detalleordencompra';
    protected $primaryKey = 'idDetOrdenCompra';
    public $timestamps = false;

    protected $fillable = [
        'idOrdenCompra',
        'idArticuloMarca',
        'cantidad',
        'precioUnitario'
    ];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'idOrdenCompra', 'idOrdenCompra');
    }

    public function articuloMarca()
    {
        return $this->belongsTo(ArticuloMarca::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}