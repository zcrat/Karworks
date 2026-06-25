<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RecepcionGeneralModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagosPresupuestos extends Model
{
    use SoftDeletes;
    protected $table = 'pagos_presupuestos';
    protected $fillable = [
      'descripcion',
      'nombre',
      'presupuesto_id',
      'importe',
      'fecha',
    ];
    
    public function Archivos(){
      return $this->hasMany(ArchivosPresupuesto::class, 'Presupuesto_id','presupuesto_id')->where('Tipo_archivo_id',10); 
    }
}
