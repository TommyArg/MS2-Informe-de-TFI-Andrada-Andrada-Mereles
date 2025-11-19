<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'idCliente';
    public $timestamps = false;

    protected $fillable = [
        'razonSocial',
        'cuit',
        'direccion',
        'telefono',
        'correo',
        'fechaAlta',
        'estado'
    ];

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'idCliente', 'idCliente');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'idCliente', 'idCliente');
    }
}