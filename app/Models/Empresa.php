<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa';
    protected $primaryKey = 'idEmpresa';
    public $timestamps = false;

    protected $fillable = [
        'razonSocial',
        'cuit',
        'direccionEmpresa',
        'telefonoEmpresa',
        'correoEmpresa',
        'webEmpresa'
    ];

    public function ordenesCompra()
    {
        return $this->hasMany(OrdenCompra::class, 'idEmpresa', 'idEmpresa');
    }
}