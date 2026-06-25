<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vehiculo;
use App\User;

class Talleres extends Model
{
    protected $table = 'talleres';
    protected $fillable = [
      'nombre',
      'externo'
    ];

    function Movimientos(){
      return $this->hasMany(InventarioAlmacen::class, 'taller_id'); 
  }
    function User(){
      return $this->hasMany(User::class, 'taller_id'); 
  }
}
