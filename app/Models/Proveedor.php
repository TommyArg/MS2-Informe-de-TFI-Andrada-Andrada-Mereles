<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'idProveedor';
    public $timestamps = false;

    protected $fillable = [
        'razonSocialProveedor',
        'cuitProveedor',
        'direccionProveedor',
        'telefonoProveedor',
        'correoProveedor',
        'webProveedor',
        'fechaAltaProveedor',
        'observaciones'
    ];

    public function ordenesCompra()
    {
        return $this->hasMany(OrdenCompra::class, 'idProveedor', 'idProveedor');
    }
}