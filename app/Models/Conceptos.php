<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PresupuestoCarrito;
use App\Models\TiposVehiculoConcepto;
use App\Models\CategoriasConceptos;
use App\Models\CategoriasSat;
use App\Models\UnidadSatModel;
use App\Models\Sucursales;
use App\Models\Modulo;
use App\Models\Contratos;
class Conceptos extends Model
{
    protected $table = 'conceptosnuevos';
    //protected $primaryKey = 'id';

    protected $fillable = ['Categorias_id','Tipos_id','producto_almacen_id','num','descripcion','p_refaccion','p_mo','p_total','modulo_id','contrato_id','zona_id','anio','g_tiempo','g_kilometros','Categoria_sat_id','unidades_sat_id'];

    function Carrito(){
        return $this->HasMany(PresupuestoCarrito::class,'Concepto_id');
    }
    function TipoVehiculo(){
        return $this->belongsTo(TiposVehiculoConcepto::class,'Tipos_id');
    }
    function Categoria(){
        return $this->belongsTo(CategoriasConceptos::class,'Categorias_id');
    }
    function CategoriaSat(){
        return $this->belongsTo(CategoriasSat::class,'Categoria_sat_id');
    }
    function UnidadSat(){
        return $this->belongsTo(UnidadSatModel::class,'unidades_sat_id');
    }
    function zona(){
        return $this->belongsTo(Sucursales::class,'zona_id','id');
    }
    function modulo(){
        return $this->belongsTo(Modulo::class,'modulo_id','id');
    }
    function contrato(){
        return $this->belongsTo(Contratos::class,'contrato_id','id');
    }
}
