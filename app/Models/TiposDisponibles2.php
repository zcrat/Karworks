<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposDisponibles2 extends Model
{
    protected $table = 'Tipos_disponibles2';
    protected $fillable = [
        'tipos_vehiculo_concepto_id',
        'modulo_id',
        'zona_id',
        'contrato_id',
        'anio',
    ];
}
