<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoArchivoPresupuesto extends Model
{
    protected $table = 'tipos_archivos_presupuestos';
    protected $fillable = [
        'Nombre',
        'Carpeta',
    ];
}
