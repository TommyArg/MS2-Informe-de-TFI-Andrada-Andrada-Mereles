<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    use HasFactory;

    protected $table = 'detalle_cotizacion';
    protected $primaryKey = 'idDetCotizacion';
    public $timestamps = false;

    protected $fillable = [
        'idCotizacion',
        'idArticuloMarca',
        'cantidad',
        'precioUnitario'
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'idCotizacion', 'idCotizacion');
    }

    public function articuloMarca()
    {
        return $this->belongsTo(ArticuloMarca::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}