<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $primaryKey = 'idMarca';
    public $timestamps = false;

    protected $fillable = [
        'nombreMarca',
        'descripcionMarca'
    ];

    public function articulosMarcas()
    {
        return $this->hasMany(ArticuloMarca::class, 'idMarca', 'idMarca');
    }
}