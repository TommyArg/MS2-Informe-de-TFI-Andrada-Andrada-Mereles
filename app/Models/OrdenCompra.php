<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordencompra';
    protected $primaryKey = 'idOrdenCompra';
    public $timestamps = false;

    protected $fillable = [
        'comprobanteOC',
        'fechaOC',
        'estado',
        'idProveedor',
        'idEmpresa'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor', 'idProveedor');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa', 'idEmpresa');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrdenCompra::class, 'idOrdenCompra', 'idOrdenCompra');
    }
}