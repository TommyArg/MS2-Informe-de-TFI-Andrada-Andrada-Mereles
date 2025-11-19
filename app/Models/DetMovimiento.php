<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetMovimiento extends Model
{
    use HasFactory;

    protected $table = 'detmovimiento';
    protected $primaryKey = 'idDetMovimiento';
    public $timestamps = false;

    protected $fillable = [
        'idMovimiento',
        'idArticuloMarca',
        'cantidad'
    ];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'idMovimiento', 'idMovimiento');
    }

    public function articuloMarca()
    {
        return $this->belongsTo(ArticuloMarca::class, 'idArticuloMarca', 'idArticuloMarca');
    }
}