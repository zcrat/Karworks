<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ArchivosPresupuesto extends Model
{
    use SoftDeletes;
    protected $table ='archivos_presupuesto';
    protected $fillable =[
        'Nombre','Presupuesto_id','Tipo_archivo_id'
    ];
}
