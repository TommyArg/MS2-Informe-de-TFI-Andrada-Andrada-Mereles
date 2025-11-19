<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPorAlmacen extends Model
{
    use HasFactory;

    protected $table = 'stockporalmacen';
    protected $primaryKey = 'idStock';
    public $timestamps = false;

    protected $fillable = [
        'idAlmacen',
        'idArticuloMarca',
        'cantidadActual',
        'ultimaActualizacion'
    ];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'idAlmacen', 'idAlmacen');
    }

    public function articuloMarca()
    {
        return $this->belongsTo(ArticuloMarca::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}