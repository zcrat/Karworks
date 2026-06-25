<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConceptosPresupuestos;
use App\Models\RecepcionVehiculardemo;
use App\Models\FacturasEmisor;
class Modulo extends Model
{
    protected $table = 'modulos';
    protected $fillable = ['descripcion','factura_emisor_id'];

    function concepto(){
        return $this->hasMany(ConceptosPresupuestos::class,'CFE_id','id');
    }
    function RecepcionVehicular(){
        return $this->hasMany(RecepcionVehiculardemo::class,'modulo_id','id');
    }
     function FacturaEmisor(){
        return $this->belongsTo(FacturasEmisor::class,'factura_emisor_id','id');
    }
}
