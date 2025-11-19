<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaArticulo extends Model
{
    use HasFactory;

    protected $table = 'catarticulo';
    protected $primaryKey = 'idCatArticulo';
    public $timestamps = false;

    protected $fillable = [
        'nombreCatArticulo',
        'descripcionCatArticulo'
    ];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'idCatArticulo', 'idCatArticulo');
    }
}