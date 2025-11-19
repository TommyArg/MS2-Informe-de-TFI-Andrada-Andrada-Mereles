<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';
    protected $primaryKey = 'idFactura';
    public $timestamps = false;

    protected $fillable = [
        'idMovimiento',
        'numeroFactura',
        'fechaFactura',
        'tipoFactura',
        'idCliente',
        'subtotal',
        'iva',
        'total',
        'descuentoEfectivo',
        'estado'
    ];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'idMovimiento', 'idMovimiento');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }
}