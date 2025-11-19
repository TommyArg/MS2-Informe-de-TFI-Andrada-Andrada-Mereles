<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'idEmpleado';
    public $timestamps = false;

    protected $fillable = [
        'nombreEmpleado',
        'apellidoEmpleado',
        'dniEmpleado',
        'telefonoEmpleado',
        'correoEmpleado',
        'direccionEmpleado',
        'fechaAltaEmpleado',
        'estadoEmpleado',
        'observaciones'
    ];

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'idEmpleado', 'idEmpleado');
    }
}