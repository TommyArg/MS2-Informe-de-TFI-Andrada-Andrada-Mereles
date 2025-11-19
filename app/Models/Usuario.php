<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'usuario',
        'contrasenhaHash'
    ];

    protected $hidden = [
        'contrasenhaHash'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'idUsuario', 'idUsuario');
    }
}