<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Presupuesto;
class HojaConcepto extends Model
{
    protected $table = 'hojaconceptosbeta';
    protected $fillable = [
        'presupuesto_id',
        'fecha',
        'remplazo',
        'reparar',
        'clave',
        'descripcion',
        'partes',
        'manoobra',
        'subcontratados',
        'otros',
        'venta',
        'iva',
        'porcentaje_utilidad'
    ];
    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }
}
