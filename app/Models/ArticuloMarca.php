<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloMarca extends Model
{
    use HasFactory;

    protected $table = 'articulomarca';
    protected $primaryKey = 'idArticuloMarca';
    public $timestamps = false;

    protected $fillable = [
        'idArticulo',
        'idMarca'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulo');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idMarca', 'idMarca');
    }

    public function stocks()
    {
        return $this->hasMany(StockPorAlmacen::class, 'idArticuloMarca', 'idArticuloMarca');
    }

    public function preciosVenta()
    {
        return $this->hasMany(PrecioVenta::class, 'idArticuloMarca', 'idArticuloMarca');
    }

    public function detalleMovimientos()
    {
        return $this->hasMany(DetMovimiento::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}