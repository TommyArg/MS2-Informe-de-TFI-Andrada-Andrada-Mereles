<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';
    protected $primaryKey = 'idCotizacion';
    public $timestamps = false;

    protected $fillable = [
        'numeroCotizacion',
        'fechaCotizacion',
        'validezDias',
        'idCliente',
        'subtotal',
        'iva',
        'total',
        'observaciones',
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'idCotizacion', 'idCotizacion');
    }
}