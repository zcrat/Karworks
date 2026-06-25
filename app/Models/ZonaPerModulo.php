<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;
class ZonaPerModulo extends Model
{
    protected $table = 'zonas_modulo';

    protected $fillable = ['modulo_id','zona_id','anio'];
}
