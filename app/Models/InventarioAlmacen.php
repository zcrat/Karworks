<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Talleres;
use App\Models\ProductoAlmacen;
use App\Models\DetallesGenerales;
use App\Models\Proveedores;
use App\Models\ImagenesMovimientos;
use Illuminate\Database\Eloquent\SoftDeletes;
class InventarioAlmacen extends Model
{
    Use SoftDeletes;
    protected $table = 'inventario_almacen';
    protected $fillable = [
        'cantidad',
        'producto_almacen_id',
        'tipo',
        'proveedor_id',
        'descripcion',
        'orden_id',
        'precio',
        'has_iva',
        'taller_id'
    ];
    protected $cast=[
        'cantidad'=>'decimal:2',
        'has_iva'=>'boolean'
    ];

    function orden(){
        return $this->belongsTo(DetallesGenerales::class, 'orden_id'); 
    }
    function proveedor(){
        return $this->belongsTo(Proveedores::class, 'proveedor_id'); 
    }
    function producto(){
        return $this->belongsTo(ProductoAlmacen::class, 'producto_almacen_id'); 
    }
    function taller(){
        return $this->belongsTo(Talleres::class, 'taller_id'); 
    }
    function Imagenes(){
        return $this->hasMany(ImagenesMovimientos::class, 'movimiento_almacen_id'); 
    }
}
