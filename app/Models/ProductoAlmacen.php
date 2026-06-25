<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventarioAlmacen;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductoAlmacen extends Model
{
    Use SoftDeletes;
    protected $table = 'productos_almacen';
    protected $fillable = [
        'proveedor_id',
        'marca',
        'clave', 
        'descripcion', 
        'cantidad_min', 
        'cantidad_max',
        'tipo',  
    ];

    function Movimientos(){
        return $this->hasMany(InventarioAlmacen::class, 'producto_almacen_id'); 
    }
    function Inicial(){
        return $this->hasOne(InventarioAlmacen::class, 'producto_almacen_id')->where('tipo',2); 
    }
    function Last(){
        return $this->hasOne(InventarioAlmacen::class, 'producto_almacen_id')->where('tipo',1); 
    }
    function entradas(){
        return $this->hasMany(InventarioAlmacen::class, 'producto_almacen_id')->where('tipo',1); 
    }
    function salidas(){
        return $this->hasMany(InventarioAlmacen::class, 'producto_almacen_id')->where('tipo',0);
    }
}
