<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costo extends Model
{
    use HasFactory;

    protected $table = 'costos';
    protected $primaryKey = 'idCosto';
    public $timestamps = false;

    protected $fillable = [
        'idArticuloMarca',
        'fechaCosto',
        'precioVigente'
    ];

    public function articuloMarca()
    {
        return $this->belongsTo(ArticuloMarca::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}