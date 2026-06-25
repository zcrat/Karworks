<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposVehiculoConcepto extends Model
{
    protected $table = 'tipos_vehiculo_concepto';
    protected $fillable = [
      'nombre',
      'cilindros'
    ];
}