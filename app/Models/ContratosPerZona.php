<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ContratosPerZona extends Model
{
    protected $table = 'contratos_modulo';

    protected $fillable = ['modulo_id','zona_id','contrato_id','anio','descripcion'];
}
