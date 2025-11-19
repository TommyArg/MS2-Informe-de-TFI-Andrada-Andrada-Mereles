<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remito extends Model
{
    use HasFactory;

    protected $table = 'remitos';
    protected $primaryKey = 'idRemito';
    public $timestamps = false;

    protected $fillable = [
        'idMovimiento',
        'numeroRemito',
        'fechaRemito',
        'tipoRemito',
        'observaciones'
    ];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'idMovimiento', 'idMovimiento');
    }
}