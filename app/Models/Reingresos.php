<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Reingresos extends Model
{
    protected $table = 'reingresos';
    protected $fillable = [
      'orden_original',
      'fecha_terminado',
      'fecha_verificado',
      'fecha_entregado',
    ];

    public function detallesGeneralesOriginales()
    {
      return $this->belongsTo(DetallesGenerales::class, 'orden_original');
    }
}
