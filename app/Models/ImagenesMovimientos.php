<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventarioAlmacen;

class ImagenesMovimientos extends Model
{
    protected $table = 'imagenes_movimientos';
    protected $fillable = [
      'foto',  
      'movimiento_almacen_id',
    ];
    function Movimiento(){
        return $this->belongsTo(InventarioAlmacen::class,'movimiento_almacen_id');
    }
}
