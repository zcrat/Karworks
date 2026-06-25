<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ConceptoVale extends Model
{
    use SoftDeletes;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'concepto_vale';
    protected $fillable = [
        'Vale_Almacen_id',
        'Descripcion',
        'Cantidad',
        'Costos',
        'Tipo_Concepto',
        'Fecha',
        'Clave',
        'Costo_Has_Iva',
        'entregado_at',
    ];
    protected $casts = [
        'Fecha'=>'datetime:Y-m-d H:i:s',
        'entregado_at'=>'datetime:Y-m-d H:i:s',
    ];
    protected function serializeDate(\DateTimeInterface $date)
{
    return $date->format('Y-m-d H:i:s');
}
}
