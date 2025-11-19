<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $table = 'articulos';
    protected $primaryKey = 'idArticulo';
    public $timestamps = false;

    protected $fillable = [
        'idCatArticulo',
        'nombreArticulo',
        'descripcionArticulo'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaArticulo::class, 'idCatArticulo', 'idCatArticulo');
    }

    public function articulosMarcas()
    {
        return $this->hasMany(ArticuloMarca::class, 'idArticulo', 'idArticulo');
    }

    public function stocks()
    {
        return $this->hasManyThrough(
            StockPorAlmacen::class,
            ArticuloMarca::class,
            'idArticulo',
            'idArticuloMarca',
            'idArticulo',
            'idArticuloMarca'
        );
    }
}