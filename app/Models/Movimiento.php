<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'movimientos';
    protected $primaryKey = 'idMovimiento';
    public $timestamps = false;

    protected $fillable = [
        'tipoMovimiento',
        'fechaMovimiento',
        'idUsuario',
        'idAlmacen',
        'observaciones'
    ];

    // Cambiar la relación para usar la tabla 'usuarios' en lugar de 'users'
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'idAlmacen', 'idAlmacen');
    }

    public function detalles()
    {
        return $this->hasMany(DetMovimiento::class, 'idMovimiento', 'idMovimiento');
    }

    public function remito()
    {
        return $this->hasOne(Remito::class, 'idMovimiento', 'idMovimiento');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'idMovimiento', 'idMovimiento');
    }
}