<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacenes';
    protected $primaryKey = 'idAlmacen';
    public $timestamps = false;

    protected $fillable = [
        'nombreAlmacen',
        'direccionAlmacen',
        'descripcionAlmacen',
        'fechaAltaAlmacen',
        'estadoAlmacen'
    ];

    public function stocks()
    {
        return $this->hasMany(StockPorAlmacen::class, 'idAlmacen', 'idAlmacen');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'idAlmacen', 'idAlmacen');
    }
}