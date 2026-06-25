<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventarioAlmacen;
use Illuminate\Database\Eloquent\SoftDeletes;
class Proveedores extends Model
{
    Use SoftDeletes;
    protected $table = 'proveedores';
    protected $fillable = [
        'nombre',
    ];
    protected $cast=[
        
    ];

    function Movimientos(){
        return $this->hasMany(InventarioAlmacen::class, 'proveedor_id'); 
    }
}
